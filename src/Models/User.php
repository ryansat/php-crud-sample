<?php

namespace App\Models;

// Include the Database class
require_once __DIR__ . '/../Config/Database.php';

use App\Config\Database;

class User {
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($username, $password, $role) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare(
                "INSERT INTO Users (username, password, role) VALUES (?, ?, ?)"
            );
            $stmt->execute([$username, $hashedPassword, $role]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new PDOException("Error creating user: " . $e->getMessage());
        }
    }

    public function authenticate($username, $password) {
        try {
            $stmt = $this->db->prepare(
                "SELECT id, username, password, role FROM Users WHERE username = ?"
            );
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                unset($user['password']);
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            throw new PDOException("Authentication error: " . $e->getMessage());
        }
    }

    public function findById($id) {
        try {
            $stmt = $this->db->prepare(
                "SELECT id, username, role FROM Users WHERE id = ?"
            );
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new PDOException("Error finding user: " . $e->getMessage());
        }
    }

    public function createActivationForm($userId) {
        try {
            $activationCode = bin2hex(random_bytes(32));
            $stmt = $this->db->prepare(
                "INSERT INTO ActivationForms (user_id, activation_code) VALUES (?, ?)"
            );
            $stmt->execute([$userId, $activationCode]);
            return $activationCode;
        } catch (PDOException $e) {
            throw new PDOException("Error creating activation form: " . $e->getMessage());
        }
    }

    public function activateUser($activationCode, $password) {
        try {
            $stmt = $this->db->prepare(
                "SELECT user_id FROM ActivationForms WHERE activation_code = ?"
            );
            $stmt->execute([$activationCode]);
            $result = $stmt->fetch();

            if ($result) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $this->db->prepare(
                    "UPDATE Users SET password = ? WHERE id = ?"
                );
                $stmt->execute([$hashedPassword, $result['user_id']]);

                $stmt = $this->db->prepare(
                    "DELETE FROM ActivationForms WHERE activation_code = ?"
                );
                $stmt->execute([$activationCode]);
                return true;
            }
            return false;
        } catch (PDOException $e) {
            throw new PDOException("Error activating user: " . $e->getMessage());
        }
    }
}