<?php

namespace Database\Migrations;

use Database;

class UpdateImage implements Database\SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE images DROP COLUMN image_file_path',
            'ALTER TABLE images DROP COLUMN media_type'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE images ADD COLUMN image_file_path TEXT NOT NULL',
            'ALTER TABLE images ADD COLUMN media_type varchar(10) NOT NULL'
        ];
    }
}
