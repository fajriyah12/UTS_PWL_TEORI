<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('organizers', function (Blueprint $table) {
            // Add new columns
            $table->string('company_name')->nullable()->after('user_id');
            $table->string('phone')->nullable()->after('company_name');
            $table->text('address')->nullable()->after('phone');
            $table->boolean('is_verified')->default(false)->after('address');
            $table->string('bank_account')->nullable()->after('is_verified');
            $table->string('bank_name')->nullable()->after('bank_account');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizers', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'phone', 'address', 'is_verified', 'bank_account', 'bank_name']);
        });
    }
};
