<?php

namespace Database;

use Database\MySQLWrapper;

class DatabaseHelper
{
    public static function insertImage(string $hash, string $image, string $extension, string $uploadDate, string $view_url, string $delete_url): void
    {
        $db = new MySQLWrapper();
        try {
            $db->begin_transaction();
            $query = "INSERT INTO images VALUES (?, ?, ?, ?, ?, 0, ?, ?)";
            $stmt = $db->prepare($query);
            if (!$stmt) {
                throw new \Exception("Statement preparation failed: " . $db->error);
            }
            $stmt->bind_param('sssssss', $hash, $image, $extension, $uploadDate, $uploadDate, $view_url, $delete_url);
            if (!$stmt->execute()) {
                throw new \Exception("Execute failed: " . $stmt->error);
            }
            $db->commit();
        } catch (\Exception $e) {
            $db->rollback();
            throw $e;
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }

    public static function selectImage(string $hash): string
    {
        $db = new MySQLWrapper();
        try {
            $query = "SELECT image FROM images WHERE image_hash = ?";
            $stmt = $db->prepare($query);
            if (!$stmt) {
                throw new \Exception("Statement preparation failed: " . $db->error);
            }
            $stmt->bind_param('s', $hash);
            if (!$stmt->execute()) {
                throw new \Exception("Execute failed: " . $stmt->error);
            }
            $result = $stmt->get_result();
            $image = $result->fetch_row()[0];
            return $image;
        } catch (\Exception $e) {
            throw $e;
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }

    public static function selectViewCount(string $hash): int
    {
        $db = new MySQLWrapper();
        try {
            $query = "SELECT view_count FROM images WHERE image_hash = ?";
            $stmt = $db->prepare($query);
            if (!$stmt) {
                throw new \Exception("Statement preparation failed: " . $db->error);
            }
            $stmt->bind_param('s', $hash);
            if (!$stmt->execute()) {
                throw new \Exception("Execute failed: " . $stmt->error);
            }
            $result = $stmt->get_result();
            $view_count = $result->fetch_row()[0];
            return $view_count;
        } catch (\Exception $e) {
            throw $e;
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }

    public static function selectExtension(string $hash): string
    {
        $db = new MySQLWrapper();
        try {
            $query = "SELECT extension FROM images WHERE image_hash = ?";
            $stmt = $db->prepare($query);
            if (!$stmt) {
                throw new \Exception("Statement preparation failed: " . $db->error);
            }
            $stmt->bind_param('s', $hash);
            if (!$stmt->execute()) {
                throw new \Exception("Execute failed: " . $stmt->error);
            }
            $result = $stmt->get_result();
            $extension = $result->fetch_row()[0];
            return $extension;
        } catch (\Exception $e) {
            throw $e;
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }

    public static function incrementViewCount(string $hash): void
    {
        $db = new MySQLWrapper();
        try {
            $db->begin_transaction();
            $query = "UPDATE images SET view_count = view_count + 1 WHERE image_hash = ? AND view_count < 2147483647";
            $stmt = $db->prepare($query);
            if (!$stmt) {
                throw new \Exception("Statement preparation failed: " . $db->error);
            }
            $stmt->bind_param('s', $hash);
            if (!$stmt->execute()) {
                throw new \Exception("Execute failed: " . $stmt->error);
            }
            $db->commit();
        } catch (\Exception $e) {
            $db->rollback();
            throw $e;
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }
}
