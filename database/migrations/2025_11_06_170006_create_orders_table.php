<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('orders', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('code', 24)->unique();
    $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
    $table->unsignedBigInteger('total_amount')->default(0);
    $table->enum('status',['pending','paid','failed','refunded','cancelled'])->default('pending');
    $table->string('payment_method')->nullable();
    $table->dateTime('paid_at')->nullable();
    $table->json('meta')->nullable();
    $table->timestamps();
});

}
public function down(): void { Schema::dropIfExists('orders'); }

};
