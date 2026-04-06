<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Sessions table is already created by the base Laravel migration.
    }

    public function down(): void
    {
        // Keep the shared sessions table intact on rollback.
    }
};
