# DriveSphere

DriveSphere is a full-stack PHP car marketplace web application, built from scratch on a custom MVC architecture (no framework). It covers the complete lifecycle of buying, selling, and renting vehicles — listings, rentals, purchases, payments, reviews, messaging, and admin management.

## Tech Stack

- **Backend:** PHP 8.2, custom MVC architecture
- **Database:** MySQL (PDO)
- **Frontend:** Bootstrap 5, custom CSS
- **Local environment:** WampServer 3.3.x

## Features

### Authentication & Roles
- Secure auth with bcrypt password hashing, CSRF protection, and session-based sessions
- Role-based access and redirects for Customer, Seller, and Admin

### Seller
- Full CRUD for vehicle listings with multi-image upload

### Public / Customer
- Car browsing with search, filtering, and pagination
- Favorites / wishlist
- Buyer–seller messaging
- Rental bookings and purchase/reservation flow with seller-set booking fees
- Simulated payments with receipts
- Verified-buyer reviews and ratings
- Side-by-side car comparison tool

### Admin
- Vehicle approval workflow
- User, seller, payment, report, rental, and sales management

### Other
- In-app notifications tied to key events across the platform
- Role-specific profiles, settings, and dashboards
- Homepage with hero section, brand pills, FAQ/accordion, car grids, testimonials, and newsletter signup

## Database

A 15-table schema covering: `users`, `roles`, `sellers`, `cars`, `car_images`, `rentals`, `rental_bookings`, `purchases`, `payments`, `favorites`, `reviews`, `messages`, `notifications`, `activity_logs`.

## Getting Started

### Prerequisites
- PHP 8.2+
- MySQL
- WampServer (or equivalent local stack)

### Setup
1. Clone the repo:
   ```bash
   git clone https://github.com/melvinlagz/Drivesphere.git
   ```
2. Import the database schema into MySQL.
3. Configure your database credentials and `BASE_URL` in the project config.
4. Point your local VirtualHost (e.g. `drivesphere.local`) at the project's `public` directory.
5. Visit the site in your browser.

## Author

Built by [Melvin Langat](https://github.com/melvinlagz).
