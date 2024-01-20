<?php

namespace Database;

use mysqli;
use Settings\Settings;

class MySQLWrapper extends mysqli
{
    public function __construct(?string $hostname = 'localhost', ?string $username = null, ?string $password = null, ?string $database = null, ?int $port = null, ?string $socket = null)
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $username = $username ?? Settings::env('DATABASE_USER');
        $password = $password ?? Settings::env('DATABASE_USER_PASSWORD');
        $database = $database ?? Settings::env('DATABASE_NAME');
        parent::__construct($hostname, $username, $password, $database, $port, $socket);
    }

    public function isRegistered(?string $title = null, ?string $isbn = null): bool
    {
        $sql = null;
        if ($title != null) {
            $sql = sprintf("SELECT count(*) FROM open_library_cache WHERE type = 'title' AND name = '%s'", $title);
        } else {
            $sql = sprintf("SELECT count(*) FROM open_library_cache WHERE type = 'isbn' AND name = '%s'", $isbn);
        }
        $count = $this->query($sql)->fetch_row()[0];
        return $count > 0;
    }
}
