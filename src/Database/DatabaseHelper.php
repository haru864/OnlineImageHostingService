<?php

namespace Database;

use Database\MySQLWrapper;

class DatabaseHelper
{
    public static function insertSnippet(string $hash_value, string $snippet, string $language, int $term_minute): void
    {
        $db = new MySQLWrapper();
        $stmt = $db->prepare("INSERT INTO snippet VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?) ON DUPLICATE KEY UPDATE registered_at = CURRENT_TIMESTAMP, expired_at = ?");
        if ($term_minute == -1) {
            $date = new \DateTime('9999-12-31 23:59:59');
            $expired_date_str = $date->format('Y-m-d H:i:s');
        } else {
            $current_date_str = date('Y-m-d H:i:s');
            $expired_date_int = strtotime("+{$term_minute} minutes", strtotime($current_date_str));
            $expired_date_str = date('Y-m-d H:i:s', $expired_date_int);
        }
        $stmt->bind_param('sssss', $hash_value, $snippet, $language, $expired_date_str, $expired_date_str);
        $stmt->execute();
        return;
    }

    public static function getSnippetAndLanguageByHashValue(string $hash_value): mixed
    {
        $db = new MySQLWrapper();
        $stmt = $db->prepare("SELECT snippet, language FROM snippet WHERE hash_value = ? AND expired_at > CURRENT_TIMESTAMP");
        $stmt->bind_param('s', $hash_value,);
        $stmt->execute();
        $result = $stmt->get_result();
        $arr = $result->fetch_row();
        return $arr;
    }

    public static function deleteExpiredSnippets(): void
    {
        $db = new MySQLWrapper();
        $stmt = $db->prepare("DELETE FROM snippet WHERE expired_at <= CURRENT_TIMESTAMP");
        $stmt->execute();
    }
}
