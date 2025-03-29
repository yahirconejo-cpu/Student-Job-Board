<?php
    function connectedPDO() {
        $dbPath = __DIR__ . '/Database/Website2.db';
        try {
            $pdo = new PDO('sqlite:' . $dbPath);
            return $pdo;
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }