<?php

namespace Database;

use Database\MySQLWrapper;

class DatabaseHelper
{
    public static function insertImage(string $hash, string $image, string $extension, string $uploadDate, string $view_url, string $delete_url): void
    {
        $db = new MySQLWrapper();
        $query = <<<SQL
        INSERT INTO
            images
        VALUES (
            ?, ?, ?, ?, ?, 0, ?, ?
        )
        SQL;
        $stmt = $db->prepare($query);
        $stmt->bind_param('sssssss', $hash, $image, $extension, $uploadDate, $uploadDate, $view_url, $delete_url);
        $stmt->execute();
        return;
    }
}
