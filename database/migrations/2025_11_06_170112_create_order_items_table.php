<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('order_items', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('order_id')->constrained('orders')->cascadeOnDelete();
    $table->foreignUuid('ticket_type_id')->constrained('ticket_types')->restrictOnDelete();
    $table->unsignedInteger('qty')->default(1);
    $table->unsignedBigInteger('unit_price');
    $table->unsignedBigInteger('subtotal');
    $table->timestamps();
});

}
public function down(): void { Schema::dropIfExists('order_items'); }

};
