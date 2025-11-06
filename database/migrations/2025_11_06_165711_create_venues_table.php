<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('venues', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('organizer_id')->constrained('organizers')->cascadeOnDelete();
    $table->string('name');
    $table->string('slug')->unique();
    $table->string('city')->nullable();
    $table->string('address')->nullable();
    $table->integer('capacity')->default(0);
    $table->decimal('lat', 10, 7)->nullable();
    $table->decimal('lng', 10, 7)->nullable();
    $table->timestamps();
});

}
public function down(): void { Schema::dropIfExists('venues'); }

};
