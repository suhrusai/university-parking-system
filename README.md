
# University Parking System

This PHP-based application is designed to manage university parking permits, personal information, vehicle data, and payment details. It provides an authentication system for users and administrators, allowing for secure access to various functionalities.

## Features

- **User Authentication**: Login, logout, and session management for secure user and admin access.
- **Permit Management**: Allows users to apply for, view, and manage their parking permits.
- **Vehicle Information**: Users can add and manage vehicle details associated with their parking permits.
- **Payment Information**: Handles payment details related to parking permits.
- **Admin Interface**: An admin panel for managing users, parking permits, and system settings.
- **Security**: Unauthorized access is restricted with the `unauthorized.php` page for security purposes.

## Folder Structure

- `admin/`: Contains the admin panel interface for managing users, permits, and payments.
- `assets/`: Includes all frontend assets such as CSS, images, and JavaScript files.
- `authentication/`: Handles user login, logout, and session management.
- `dbConfig.php`: The database configuration file for connecting to the backend database.
- `homepage.php`: The main landing page of the system after login.
- `login.php`: The login page for users and admins.
- `logout.php`: Handles user logout and session destruction.
- `payment-information/`: Module handling user payments for parking permits.
- `permits/`: Handles all functionality related to parking permits (viewing, applying, and managing).
- `personal-information/`: Users can manage their personal data.
- `vehicle-information/`: Users can add and manage vehicle information.
- `unauthorized.php`: Displays an unauthorized access message when users try to access restricted pages.

## Prerequisites

- PHP 7.x or above
- MySQL or any other relational database for backend data management
- A web server (Apache, Nginx, etc.)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/university-parking-system.git
   ```

2. Import the database:
   - Locate the SQL file in the repository (if available) and import it into your MySQL database.
   - Modify the `dbConfig.php` file with your database credentials.

3. Run the application on your local server:
   - Place the project files in your web server's root directory (e.g., `htdocs` for XAMPP).
   - Access the system by navigating to `http://localhost/university-parking-system`.

## Usage

1. Navigate to the **login page** (`login.php`) to log in as a user or admin.
2. Users can apply for parking permits, add vehicle information, and view payment details.
3. Admins can manage the system via the **admin panel** (`admin/`).

## License

This project is licensed under the MIT License.
