<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('airlink_notes_login_sessions', function (Blueprint $table) {
            $table->boolean('onboarding_completed')->default(false)->after('user_agent');
            $table->index(['user_id', 'onboarding_completed']);
        });
    }

    public function down(): void
    {
        Schema::table('airlink_notes_login_sessions', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'onboarding_completed']);
            $table->dropColumn('onboarding_completed');
        });
    }
};
