<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        // The table is created by the earlier 2026_08_14 migration. This
        // migration was kept in production history, so it must be a no-op.
    }

    public function down()
    {
        // Do not drop the table owned by the earlier migration.
    }
};
