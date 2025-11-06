<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('hero_sections', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('title');
    $table->string('subtitle')->nullable();
    $table->string('image_path')->nullable();
    $table->boolean('is_active')->default(true);
    $table->unsignedInteger('sort_order')->default(0);
    $table->timestamps();
});

}
public function down(): void { Schema::dropIfExists('hero_sections'); }

};
