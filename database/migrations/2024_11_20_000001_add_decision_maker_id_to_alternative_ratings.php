<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('alternative_ratings', function (Blueprint $table) {
            $table->foreignId('decision_maker_id')
                  ->nullable()
                  ->after('rating')
                  ->constrained('decision_makers')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alternative_ratings', function (Blueprint $table) {
            $table->dropForeign(['decision_maker_id']);
            $table->dropColumn('decision_maker_id');
        });
    }
};
