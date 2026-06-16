<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Convert old values FIRST (before changing enum)
        DB::statement("UPDATE leads SET status = 'accepted' WHERE status = 'qualified'");
        DB::statement("UPDATE leads SET status = 'new' WHERE status NOT IN ('new','contacted','accepted','denied')");

        // 2) Now safely change enum
        DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM('new','contacted','accepted','denied') NOT NULL DEFAULT 'new'");
    }

    public function down(): void
    {
        // 1) Expand enum first (so values won't truncate)
        DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM('new','contacted','qualified') NOT NULL DEFAULT 'new'");

        // 2) Map values back (best-effort)
        DB::statement("UPDATE leads SET status = 'qualified' WHERE status = 'accepted'");
        DB::statement("UPDATE leads SET status = 'contacted' WHERE status = 'denied'");
    }
};
