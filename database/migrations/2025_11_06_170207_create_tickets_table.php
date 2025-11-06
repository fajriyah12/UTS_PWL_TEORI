<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('tickets', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('order_item_id')->constrained('order_items')->cascadeOnDelete();
    $table->foreignUuid('ticket_type_id')->constrained('ticket_types')->restrictOnDelete();
    $table->string('serial', 32)->unique();
    $table->string('qr_token', 64)->unique();
    $table->enum('status',['issued','checked_in','refunded','void'])->default('issued');
    $table->string('holder_name')->nullable();
    $table->string('holder_email')->nullable();
    $table->timestamps();
});

}
public function down(): void { Schema::dropIfExists('tickets'); }

};
