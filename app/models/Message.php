<?php
// app/models/Message.php

class Message
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function send(int $senderId, int $receiverId, ?int $carId, string $messageText): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO messages (sender_id, receiver_id, car_id, message) VALUES (:sender_id, :receiver_id, :car_id, :message)"
        );
        $stmt->execute([
            'sender_id'   => $senderId,
            'receiver_id' => $receiverId,
            'car_id'      => $carId,
            'message'     => $messageText,
        ]);
        return (int) $this->db->lastInsertId();
    }

    // Returns a list of distinct conversations for a user, with the other party's info and the latest message
   public function getConversations(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                CASE WHEN m.sender_id = :user_id1 THEN m.receiver_id ELSE m.sender_id END AS other_user_id,
                u.full_name AS other_user_name,
                m.car_id,
                c.brand, c.model,
                MAX(m.created_at) AS last_message_at,
                SUM(CASE WHEN m.receiver_id = :user_id2 AND m.is_read = 0 THEN 1 ELSE 0 END) AS unread_count
             FROM messages m
             JOIN users u ON u.id = CASE WHEN m.sender_id = :user_id3 THEN m.receiver_id ELSE m.sender_id END
             LEFT JOIN cars c ON c.id = m.car_id
             WHERE m.sender_id = :user_id4 OR m.receiver_id = :user_id5
             GROUP BY other_user_id, m.car_id
             ORDER BY last_message_at DESC"
        );
        $stmt->execute([
            'user_id1' => $userId,
            'user_id2' => $userId,
            'user_id3' => $userId,
            'user_id4' => $userId,
            'user_id5' => $userId,
        ]);

        $conversations = $stmt->fetchAll();

        // Fetch last message separately per conversation (avoids repeated-param subquery issue)
        foreach ($conversations as &$conv) {
            $sql = "SELECT message FROM messages
                    WHERE ((sender_id = :uid1 AND receiver_id = :oid1) OR (sender_id = :oid2 AND receiver_id = :uid2))
                    AND (car_id <=> :car_id)
                    ORDER BY created_at DESC LIMIT 1";
            $stmt2 = $this->db->prepare($sql);
            $stmt2->execute([
                'uid1'   => $userId,
                'oid1'   => $conv['other_user_id'],
                'oid2'   => $conv['other_user_id'],
                'uid2'   => $userId,
                'car_id' => $conv['car_id'],
            ]);
            $lastMsg = $stmt2->fetch();
            $conv['last_message'] = $lastMsg['message'] ?? '';
        }

        return $conversations;
    }

    public function getThread(int $userId, int $otherUserId, ?int $carId): array
    {
        $sql = "SELECT m.*, u.full_name AS sender_name
                FROM messages m
                JOIN users u ON u.id = m.sender_id
                WHERE ((m.sender_id = :user_id1 AND m.receiver_id = :other_id1)
                    OR (m.sender_id = :other_id2 AND m.receiver_id = :user_id2))";

        $params = [
            'user_id1'  => $userId,
            'other_id1' => $otherUserId,
            'other_id2' => $otherUserId,
            'user_id2'  => $userId,
        ];

        if ($carId !== null) {
            $sql .= " AND m.car_id = :car_id";
            $params['car_id'] = $carId;
        } else {
            $sql .= " AND m.car_id IS NULL";
        }

        $sql .= " ORDER BY m.created_at ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function markThreadAsRead(int $userId, int $otherUserId, ?int $carId): void
    {
        $sql = "UPDATE messages SET is_read = 1 WHERE receiver_id = :user_id AND sender_id = :other_id";
        $params = ['user_id' => $userId, 'other_id' => $otherUserId];

        if ($carId !== null) {
            $sql .= " AND car_id = :car_id";
            $params['car_id'] = $carId;
        } else {
            $sql .= " AND car_id IS NULL";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }

    public function getUnreadCount(int $userId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM messages WHERE receiver_id = :user_id AND is_read = 0");
        $stmt->execute(['user_id' => $userId]);
        return (int) $stmt->fetch()['total'];
    }
}