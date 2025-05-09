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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('image');
            $table->string('project_url')->nullable();
            $table->integer('project_order');
            $table->timestamps();
        });
        Schema::create('project_skill', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_id')->constrained();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_skill');
        Schema::dropIfExists('projects');
    }
};
