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
        Schema::create('block_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id')->constrained();
            $table->string('field_key')->default('title');
            $table->text('field_value')->default('');
            $table->string('field_type')->default('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('block_fields');
    }
};
