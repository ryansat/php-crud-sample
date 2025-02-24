<?php

namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;

class Contact {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($userId, $name, $phone, $email) {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO Contacts (user_id, name, phone, email) VALUES (?, ?, ?, ?)"
            );
            $stmt->execute([$userId, $name, $phone, $email]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new PDOException("Error creating contact: " . $e->getMessage());
        }
    }

    public function update($id, $name, $phone, $email) {
        try {
            $stmt = $this->db->prepare(
                "UPDATE Contacts SET name = ?, phone = ?, email = ? WHERE id = ?"
            );
            return $stmt->execute([$name, $phone, $email, $id]);
        } catch (PDOException $e) {
            throw new PDOException("Error updating contact: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM Contacts WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new PDOException("Error deleting contact: " . $e->getMessage());
        }
    }

    public function findById($id) {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM Contacts WHERE id = ?"
            );
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new PDOException("Error finding contact: " . $e->getMessage());
        }
    }

    public function findByUserId($userId) {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM Contacts WHERE user_id = ? ORDER BY created_at DESC"
            );
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new PDOException("Error finding contacts: " . $e->getMessage());
        }
    }

    public function getWhatsAppLink($phone) {
        // Remove any non-numeric characters from the phone number
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        return "https://wa.me/" . $cleanPhone;
    }
}