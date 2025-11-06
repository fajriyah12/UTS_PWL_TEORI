<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('checkins', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('ticket_id')->constrained('tickets')->cascadeOnDelete();
    $table->foreignUuid('operator_id')->nullable()->constrained('users')->nullOnDelete();
    $table->string('gate')->nullable();
    $table->string('device_id')->nullable();
    $table->dateTime('checked_in_at');
    $table->timestamps();
});

}
public function down(): void { Schema::dropIfExists('checkins'); }

};
