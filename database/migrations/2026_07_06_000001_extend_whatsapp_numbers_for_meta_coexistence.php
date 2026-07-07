<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_numbers', function (Blueprint $table) {
            $table->string('waba_id')->nullable()->after('phone_number_id');
            $table->text('access_token')->nullable()->after('waba_id');
            $table->boolean('coexistence_enabled')->default(false)->after('access_token');
            $table->enum('connection_status', ['disconnected', 'pending', 'connected', 'error'])->default('disconnected')->after('coexistence_enabled');
            $table->timestamp('connected_at')->nullable()->after('connection_status');
            $table->text('last_error')->nullable()->after('connected_at');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_numbers', function (Blueprint $table) {
            $table->dropColumn([
                'waba_id', 'access_token', 'coexistence_enabled',
                'connection_status', 'connected_at', 'last_error',
            ]);
        });
    }
};
