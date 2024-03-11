<?php

namespace Database\Migrations;

use Database;

class ChangeUrlColumns implements Database\SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE images MODIFY COLUMN view_url TEXT NOT NULL",
            "ALTER TABLE images MODIFY COLUMN delete_url TEXT NOT NULL"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE images MODIFY COLUMN view_url varchar(120) NOT NULL",
            "ALTER TABLE images MODIFY COLUMN delete_url varchar(120) NOT NULL"
        ];
    }
}