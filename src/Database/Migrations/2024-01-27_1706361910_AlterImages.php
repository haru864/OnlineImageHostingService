<?php

namespace Database\Migrations;

use Database;

class AlterImages implements Database\SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE images CHANGE COLUMN extension media_type VARCHAR(10) NOT NULL"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE images CHANGE COLUMN media_type extension VARCHAR(10) NOT NULL"
        ];
    }
}