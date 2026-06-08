<?php

use App\Enums\GrindSize;
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
        Schema::create('recipes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('brewer_id')->constrained('brewers')->cascadeOnDelete();
            $table->foreignUuid('coffee_id')->constrained('coffees')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('coffee_weight', 5, 2)->comment('Coffee weight in grams');
            $table->decimal('water_weight', 5, 2)->comment('Total water weight in milliliters');
            $table->enum('grind_size', GrindSize::values());
            $table->smallInteger('temperature');
            $table->unsignedInteger('total_duration')->comment('Total duration in seconds');
            $table->boolean('is_published')->default(false);
            $table->text('description')->nullable();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
