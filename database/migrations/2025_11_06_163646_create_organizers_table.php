<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('organizers', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
    $table->string('name');
    $table->string('slug')->unique();
    $table->string('contact_email')->nullable();
    $table->string('contact_phone', 32)->nullable();
    $table->text('bio')->nullable();
    $table->string('logo_path')->nullable();
    $table->timestamps();
});


}
public function down(): void { Schema::dropIfExists('organizers'); }

};
