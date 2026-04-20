<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('note_folders', function (Blueprint $table) {
            $table->string('icon_emoji', 16)->nullable()->after('name');
            $table->index('icon_emoji');
        });
    }

    public function down(): void
    {
        Schema::table('note_folders', function (Blueprint $table) {
            $table->dropIndex(['icon_emoji']);
            $table->dropColumn('icon_emoji');
        });
    }
};
