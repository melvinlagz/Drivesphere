<?php
// app/models/Notification.php

class Notification
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(int $userId, string $type, string $title, string $body = ''): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO notifications (user_id, type, title, body) VALUES (:user_id, :type, :title, :body)"
        );
        $stmt->execute([
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'body'    => $body,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function getByUser(int $userId, int $limit = 30): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit"
        );
        $stmt->bindValue('user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUnreadCount(int $userId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM notifications WHERE user_id = :user_id AND is_read = 0");
        $stmt->execute(['user_id' => $userId]);
        return (int) $stmt->fetch()['total'];
    }

    public function markAllAsRead(int $userId): void
    {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = :user_id AND is_read = 0");
        $stmt->execute(['user_id' => $userId]);
    }

    public function markAsRead(int $notificationId, int $userId): void
    {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE id = :id AND user_id = :user_id");
        $stmt->execute(['id' => $notificationId, 'user_id' => $userId]);
    }
}