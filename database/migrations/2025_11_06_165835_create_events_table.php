<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void
{
    Schema::create('events', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('organizer_id')->constrained('organizers')->cascadeOnDelete();
    $table->foreignUuid('venue_id')->constrained('venues')->restrictOnDelete();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->string('banner_path')->nullable();
    $table->dateTime('start_at');
    $table->dateTime('end_at');
    $table->enum('status',['draft','published','archived','cancelled'])->default('draft');
    $table->timestamps();
});

}
public function down(): void { Schema::dropIfExists('events'); }

};
