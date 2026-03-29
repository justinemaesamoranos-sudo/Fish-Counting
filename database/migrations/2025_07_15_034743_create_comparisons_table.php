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
    {Schema::create('comparisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('image_id')
                  ->constrained('images')
                  ->cascadeOnDelete();
            $table->unsignedInteger('manual');
            $table->unsignedInteger('ai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comparisons');
    }
};
