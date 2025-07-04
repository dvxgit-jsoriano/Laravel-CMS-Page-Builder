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
        Schema::create('block_field_group_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_field_group_id')->constrained()->onDelete('cascade');
            $table->string('field_key');
            $table->text('field_value');
            $table->string('field_type')->default('text'); // <--- new field here
            $table->integer('position')->default(0);       // optional ordering within the group
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('block_field_group_items');
    }
};
