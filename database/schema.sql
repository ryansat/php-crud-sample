-- Create database if not exists
CREATE DATABASE IF NOT EXISTS crud_sample;
USE crud_sample;

-- Create Users table
CREATE TABLE IF NOT EXISTS Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer_service') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create Contacts table
CREATE TABLE IF NOT EXISTS Contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(255),
    email VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id)
);

-- Create Activation Forms table
CREATE TABLE IF NOT EXISTS ActivationForms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    activation_code VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id)
);

-- Add indexes for better performance
CREATE INDEX idx_user_role ON Users(role);
CREATE INDEX idx_contact_user ON Contacts(user_id);
CREATE INDEX idx_activation_user ON ActivationForms(user_id);