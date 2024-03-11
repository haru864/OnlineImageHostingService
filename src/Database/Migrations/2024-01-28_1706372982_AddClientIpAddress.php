<?php

namespace Database\Migrations;

use Database;

class AddClientIpAddress implements Database\SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE images ADD COLUMN client_ip_address VARCHAR(15) NOT NULL CHECK(is_ipv4(client_ip_address))"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE images DROP COLUMN client_ip_address"
        ];
    }
}
