<?php

namespace Database\Migrations;

use Database;

class CreateImages implements Database\SchemaMigration
{
    public function up(): array
    {
        return [
            "CREATE TABLE IF NOT EXISTS `images` (
                `image_hash` VARCHAR(64) NOT NULL,
                `image` BLOB NOT NULL,
                `extension` varchar(10) NOT NULL,
                `uploaded_at` DATETIME NOT NULL,
                `accessed_at` DATETIME NOT NULL,
                `view_count` INT NOT NULL,
                `view_url` varchar(120) NOT NULL,
                `delete_url` varchar(120) NOT NULL,
                PRIMARY KEY (`image_hash`)
              )"
        ];
    }

    public function down(): array
    {
        return [
            "DROP TABLE snippet"
        ];
    }
}
