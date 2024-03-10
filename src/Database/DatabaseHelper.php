<?php

namespace Database;

use Database\MySQLWrapper;

class DatabaseHelper
{
    public static function insertImage(string $hash, string $client_ip_address): void
    {
        $db = new MySQLWrapper();
        try {
            $db->begin_transaction();
            $query = "INSERT INTO images VALUES (?, NOW(), NOW(), 0, ?)";
            $stmt = $db->prepare($query);
            if (!$stmt) {
                throw new \Exception("Statement preparation failed: " . $db->error);
            }
            $stmt->bind_param('ss', $hash, $client_ip_address);
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

    public static function selectViewCount(string $hash): ?int
    {
        $db = new MySQLWrapper();
        try {
            $query = "SELECT view_count FROM images WHERE hash = ?";
            $stmt = $db->prepare($query);
            if (!$stmt) {
                throw new \Exception("Statement preparation failed: " . $db->error);
            }
            $stmt->bind_param('s', $hash);
            if (!$stmt->execute()) {
                throw new \Exception("Execute failed: " . $stmt->error);
            }
            $result = $stmt->get_result();
            $row = $result->fetch_row();
            $view_count = $row ? $row[0] : null;
            return $view_count;
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
            $query = "UPDATE images SET view_count = view_count + 1 WHERE hash = ? AND view_count < 2147483647";
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

    public static function updateAccessedDate(string $hash, string $accessDate): void
    {
        $db = new MySQLWrapper();
        try {
            $db->begin_transaction();
            $query = "UPDATE images SET accessed_at = ? WHERE hash = ?";
            $stmt = $db->prepare($query);
            if (!$stmt) {
                throw new \Exception("Statement preparation failed: " . $db->error);
            }
            $stmt->bind_param('ss', $accessDate, $hash);
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

    public static function deleteRow(string $hash): void
    {
        $db = new MySQLWrapper();
        try {
            $db->begin_transaction();
            $query = "DELETE FROM images WHERE hash = ?";
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

    public static function getImageHashesForClientByTime(string $clientIpAddress, int $minutesAgo): ?array
    {
        $db = new MySQLWrapper();
        try {
            $query = "SELECT hash FROM images WHERE client_ip_address = ? AND uploaded_at >= ?";
            $stmt = $db->prepare($query);
            if (!$stmt) {
                throw new \Exception("Statement preparation failed: " . $db->error);
            }
            $currDateTime = new \DateTime();
            $dateInterval = \DateInterval::createFromDateString("{$minutesAgo} minutes");
            $timeWindowStart = $currDateTime->sub($dateInterval)->format('Y-m-d H:i:s');
            $stmt->bind_param('ss', $clientIpAddress, $timeWindowStart);
            if (!$stmt->execute()) {
                throw new \Exception("Execute failed: " . $stmt->error);
            }
            $result = $stmt->get_result();
            $rows = $result->fetch_row();
            return $rows;
        } catch (\Exception $e) {
            throw $e;
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }

    public static function deleteNotAccessedImages(int $imageStorageDays): int
    {
        $db = new MySQLWrapper();
        try {
            $db->begin_transaction();
            $query = "DELETE FROM images WHERE accessed_at <= NOW() - INTERVAL ? DAY";
            $stmt = $db->prepare($query);
            if (!$stmt) {
                throw new \Exception("Statement preparation failed: " . $db->error);
            }
            $stmt->bind_param('i', $imageStorageDays);
            if (!$stmt->execute()) {
                throw new \Exception("Execute failed: " . $stmt->error);
            }
            $db->commit();
            return $stmt->affected_rows;
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
