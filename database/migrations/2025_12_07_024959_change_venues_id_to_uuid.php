<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create mapping of old integer IDs to new UUIDs
        $venueMappings = [];
        $venues = DB::table('venues')->get();
        foreach ($venues as $venue) {
            $newUuid = Str::uuid();
            $venueMappings[$venue->id] = $newUuid;
        }

        // Update events table to use new UUIDs
        foreach ($venueMappings as $oldId => $newUuid) {
            DB::table('events')
                ->where('venue_id', $oldId)
                ->update(['venue_id' => $newUuid]);
        }

        // Drop foreign key constraint dari events table
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['venue_id']);
        });

        // Tambah kolom uuid sementara
        Schema::table('venues', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique();
        });

        // Set UUID values
        foreach ($venueMappings as $oldId => $newUuid) {
            DB::table('venues')
                ->where('id', $oldId)
                ->update(['uuid' => $newUuid]);
        }

        // Set uuid menjadi not null
        Schema::table('venues', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
        });

        // Drop primary key
        Schema::table('venues', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('id');
        });

        // Rename uuid menjadi id dan set sebagai primary key
        Schema::table('venues', function (Blueprint $table) {
            $table->renameColumn('uuid', 'id');
            $table->primary('id');
        });

        // Recreate foreign key constraint
        Schema::table('events', function (Blueprint $table) {
            $table->foreign('venue_id')->references('id')->on('venues')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert ke integer ID (jika diperlukan)
        Schema::table('venues', function (Blueprint $table) {
            $table->dropPrimary();
        });

        Schema::table('venues', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->id(); // Tambah integer id kembali
        });
    }
};
