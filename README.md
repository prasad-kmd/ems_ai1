# Event Management System

An event management system built using **HTML**, **PHP**, **MySQL**, and **WampServer**. This system allows users to register, log in, book events, and manage event bookings. Admins can manage events, users, and clients.

## Try a [Demo](https://prasad-kmd.github.io/ems_ai1/)

## Features

- User registration and login system.
- Admin and user dashboards.
- Admin management of events, users, and clients.
- Users can book events.
- Customizable user profiles.
- Glassmorphism-style interface with background video.
- Hidden background music for ambiance.

## Installation

### Prerequisites

- **WampServer** (or any local PHP development environment)
- **PHP** (version 7.3 or higher)
- **MySQL** database
- **FTP client** (for hosting deployment, e.g., FileZilla)

### Steps to Set Up Locally

1. **Clone the repository**:

   ```bash
   git clone https://github.com/prasad-kmd/ems_ai1.git
   ```

2. **Start WampServer** and place the cloned repository in the `www` directory.

   ```
   C:/wamp64/www/ems_ai1/
   ```

3. **Create the database**:
   - Go to [phpMyAdmin](http://localhost/phpmyadmin) and create a new database, e.g., `ems_ai1`.
   - Import the SQL file (`database.sql`) to set up the necessary tables.
4. **Configure the database connection**:

   - Open `config.php` (or wherever your database connection is set).
   - Update the database credentials:

   ```php
   $conn = new mysqli('localhost', 'root', '', 'ems_ai1');
   ```

5. **Run the project**:
   - Open your browser and go to [http://localhost/ems_ai1](http://localhost/ems_ai1).
   - Register as a user or log in with admin credentials (if pre-configured).

## Deployment

### Free Hosting Providers

If you want to deploy this project online, consider using free hosting services like:

- **InfinityFree**: [https://infinityfree.net](https://infinityfree.net)
- **000WebHost**: [https://www.000webhost.com](https://www.000webhost.com)
- **Byet.Host**: [https://byet.host](https://byet.host)

To deploy:

1. Export the database from `phpMyAdmin` on your local environment.
2. Create a database on the hosting provider and import the exported SQL.
3. Upload all files via FTP to the hosting provider’s **public_html** directory.
4. Update the database connection settings in the `config.php` file.

## Project Structure

```bash
.
├── css/                     # CSS stylesheets
├── js/                      # JavaScript files
├── images/                  # Image assets
├── config.php               # Database configuration file
├── register.php             # User registration page
├── login.php                # User login page
├── admin_dashboard.php       # Admin dashboard for managing users, events, etc.
├── user_dashboard.php        # User dashboard for booking events
├── README.md                # Project documentation
└── database.sql             # SQL file for creating database tables
```

### Key Sections:

1. **Features**: A brief description of the system's functionality.
2. **Installation**: Step-by-step instructions for setting up the project locally.
3. **Deployment**: Instructions for deploying the project to a hosting provider.
4. **Project Structure**: Overview of the folder structure and key files.
5. **License**: Specify if there’s any licensing involved (e.g., MIT License).

### Additional Customization

- **Database setup**: You may want to add specific instructions for setting up the database and importing tables.
- **Admin credentials**
