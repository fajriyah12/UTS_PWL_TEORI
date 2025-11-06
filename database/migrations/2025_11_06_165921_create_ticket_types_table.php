<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('ticket_types', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('event_id')->constrained('events')->cascadeOnDelete();
    $table->string('name');
    $table->unsignedInteger('quota')->default(0);
    $table->unsignedInteger('sold')->default(0);
    $table->unsignedInteger('per_user_limit')->default(4);
    $table->unsignedBigInteger('price');
    $table->dateTime('sales_start')->nullable();
    $table->dateTime('sales_end')->nullable();
    $table->timestamps();
});

}
public function down(): void { Schema::dropIfExists('ticket_types'); }

};
