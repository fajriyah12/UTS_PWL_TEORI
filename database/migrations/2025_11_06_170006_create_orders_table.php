<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('ticket_type_id')->constrained('ticket_types')->cascadeOnDelete();

            $table->string('buyer_name');
            $table->string('buyer_email');
            $table->integer('quantity');
            $table->decimal('total_price', 12, 0);
            $table->string('order_code')->unique();


            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
