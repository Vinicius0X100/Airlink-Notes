<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->after('folder_id');
            $table->unsignedBigInteger('tag_id')->nullable()->after('sort_order');

            $table->index(['user_id', 'sort_order']);
            $table->index('tag_id');
        });
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'sort_order']);
            $table->dropIndex(['tag_id']);
            $table->dropColumn(['sort_order', 'tag_id']);
        });
    }
};
