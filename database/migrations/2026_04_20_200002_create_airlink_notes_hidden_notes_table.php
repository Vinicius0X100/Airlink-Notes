<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('airlink_notes_hidden_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('original_note_id')->nullable();
            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();

            $table->index('user_id');
            $table->index('original_note_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('airlink_notes_hidden_notes');
    }
};
