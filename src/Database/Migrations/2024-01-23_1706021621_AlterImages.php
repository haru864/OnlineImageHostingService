<?php

namespace Database\Migrations;

use Database;

class AlterImages implements Database\SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return ["ALTER TABLE images MODIFY image MEDIUMBLOB NOT NULL"];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return ["ALTER TABLE images MODIFY image BLOB"];
    }
}
