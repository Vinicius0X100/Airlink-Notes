<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

return new class extends Migration
{
    public function up(): void
    {
        try {
            Schema::table('note_folders', function (Blueprint $table) {
                $table->dropUnique('note_folders_user_id_name_unique');
            });
        } catch (Throwable $e) {
        }

        if (! Schema::hasColumn('note_folders', 'name_unique')) {
            DB::statement("ALTER TABLE `note_folders` ADD COLUMN `name_unique` VARCHAR(191) GENERATED ALWAYS AS (CASE WHEN `deleted_at` IS NULL THEN `name` ELSE CONCAT(`name`, '#', `id`) END) STORED");
        }

        try {
            DB::statement('CREATE UNIQUE INDEX `note_folders_user_id_name_active_unique` ON `note_folders` (`user_id`, `name_unique`)');
        } catch (Throwable $e) {
        }
    }

    public function down(): void
    {
        try {
            DB::statement('DROP INDEX `note_folders_user_id_name_active_unique` ON `note_folders`');
        } catch (Throwable $e) {
        }

        if (Schema::hasColumn('note_folders', 'name_unique')) {
            try {
                DB::statement('ALTER TABLE `note_folders` DROP COLUMN `name_unique`');
            } catch (Throwable $e) {
            }
        }

        try {
            Schema::table('note_folders', function (Blueprint $table) {
                $table->unique(['user_id', 'name']);
            });
        } catch (Throwable $e) {
        }
    }
};
