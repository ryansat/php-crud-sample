# PHP CRUD Sample Application

A PHP and MySQL-based CRUD application with user authentication and contact management features.

## Features

- User Authentication (Admin and Customer Service roles)
- Contact Management with CRUD operations
- Google Contacts Synchronization
- WhatsApp Integration
- Secure Password Handling

## Requirements

- XAMPP (Apache, PHP, MySQL)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer (for dependency management)

## Installation

1. Clone this repository to your XAMPP's `htdocs` directory:
```bash
git clone https://github.com/yourusername/php-crud-sample.git
cd php-crud-sample
```

2. Import the database schema:
- Start XAMPP and ensure MySQL service is running
- Create a new database named `crud_sample`
- Import the database schema from `database/schema.sql`

3. Install dependencies:
```bash
composer install
```

4. Configure the application:
- Copy `.env.example` to `.env`
- Update the database credentials in `.env`

5. Start the application:
- Access the application through your web browser: `http://localhost/php-crud-sample`

## Security

- Passwords are securely hashed using PHP's `password_hash()`
- Input validation and sanitization implemented
- Protection against SQL injection and XSS attacks

## Documentation

Detailed documentation for each feature can be found in the `docs` directory.

## License

MIT License