<?php

namespace Database\Migrations;

use Database;

class UpdateImage implements Database\SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE images CHANGE image_hash hash varchar(64) NOT NULL',
            'ALTER TABLE images CHANGE image image_file_path TEXT NOT NULL',
            'ALTER TABLE images DROP COLUMN view_url',
            'ALTER TABLE images DROP COLUMN delete_url'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE images CHANGE hash image_hash varchar(64) NOT NULL',
            'ALTER TABLE images CHANGE image_file_path image MEDIUMBLOB NOT NULL',
            'ALTER TABLE images ADD COLUMN view_url TEXT NOT NULL',
            'ALTER TABLE images ADD COLUMN delete_url TEXT NOT NULL'
        ];
    }
}
