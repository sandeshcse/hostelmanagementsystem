# Hostel Management System (PHP)

A comprehensive web-based Hostel Management System built with PHP, designed to streamline hostel operations for administrators and students. This project supports room bookings, student management, complaints, leave requests, and more.

## Table of Contents
- [Features](#features)
- [Project Structure](#project-structure)
- [Setup Instructions](#setup-instructions)
- [Usage](#usage)
- [Database](#database)
- [Screenshots](#screenshots)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)

## Features
- Admin and Student login system
- Room booking and management
- Course and student management
- Complaint registration and tracking
- Leave request management
- Mess menu management
- Dashboard with statistics and analytics
- Profile management for students and admins
- Responsive UI with Bootstrap

## Project Structure
```
HostelManagement-PHP/
├── admin/                # Admin panel files
├── assets/               # CSS, JS, images, and libraries
├── DATABASE FILE/        # SQL files for database setup
├── includes/             # Common PHP includes (DB, navigation, etc.)
├── scss/                 # SCSS source files for styling
├── student/              # Student panel files
├── index.php             # Main entry point
├── package.json          # Node dependencies (for frontend assets)
└── README.md             # Project documentation
```

## Setup Instructions
1. **Clone the repository:**
   ```sh
   git clone https://github.com/sandeshcse/hostelmanagementsystem.git
   ```
2. **Copy to your web server directory:**
   Place the project folder in your web server root (e.g., `htdocs` for XAMPP).
3. **Database setup:**
   - Import the SQL file `DATABASE FILE/hostelmsphp.sql` into your MySQL server using phpMyAdmin or the MySQL CLI.
   - Update database credentials in `includes/dbconn.php` and `includes/pdoconfig.php` as needed.
4. **Install dependencies (optional):**
   If you want to use SCSS or frontend build tools, run:
   ```sh
   npm install
   ```
5. **Start your web server:**
   - For XAMPP, start Apache and MySQL.
   - Access the system at `http://localhost/HostelManagement-PHP/`.

## Usage
- **Admin Login:** `/admin/index.php`
- **Student Login:** `/index.php`
- Use the credentials provided in `01 LOGIN DETAILS & PROJECT INFO.txt` files for demo accounts.

## Database
- The main database schema is in `DATABASE FILE/hostelmsphp.sql`.
- Update connection settings in `includes/dbconn.php` and `includes/pdoconfig.php`.

## Screenshots
> Add screenshots of the dashboard, booking, and management pages here for better documentation.

## Contributing
Pull requests are welcome! For major changes, please open an issue first to discuss what you would like to change.

## License
This project is for educational purposes. Please check with the repository owner for licensing details.

## Contact
- **Author:** Sandesh (sandeshcse)
- **GitHub:** [https://github.com/sandeshcse/hostelmanagementsystem](https://github.com/sandeshcse/hostelmanagementsystem)
- For any queries, please open an issue on GitHub. 