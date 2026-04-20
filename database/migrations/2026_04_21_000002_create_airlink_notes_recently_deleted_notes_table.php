<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('airlink_notes_recently_deleted_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('original_note_id')->nullable();
            $table->unsignedBigInteger('folder_id')->nullable();
            $table->unsignedBigInteger('tag_id')->nullable();
            $table->string('title', 255)->nullable();
            $table->longText('content')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->timestamp('deleted_at');
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index(['user_id', 'deleted_at']);
            $table->index(['user_id', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('airlink_notes_recently_deleted_notes');
    }
};
