<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Restaurant Owner
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('address');
            $table->string('phone_number')->nullable();
            $table->string('cuisine_type')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_approved')->default(false); // Admin approval
            $table->boolean('is_open')->default(true); // Owner can toggle
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
