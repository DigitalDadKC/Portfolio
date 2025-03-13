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
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('abbr');
            $table->string('state');
        });
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo');
            $table->string('address');
            $table->string('city');
            $table->foreignId('state_id')->constrained();
        });
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->integer('number')->unique();
            $table->string('address');
            $table->string('city');
            $table->foreignId('state_id')->constrained();
            $table->float('total');
            $table->string('formatted_total');
            $table->integer('days')->nullable()->default(NULL);
            $table->timestamp('start_date')->nullable()->default(NULL);
            $table->string('notes')->nullable()->default(NULL);
            $table->timestamps();
        });

        Schema::create('scopes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default(NULL);
            $table->float('area')->nullable()->default(NULL);
            $table->integer('days')->nullable()->default(NULL);
            $table->float('total')->nullable()->default(NULL);
            $table->string('formatted_total');
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('unit_of_measurements', function (Blueprint $table) {
            $table->id();
            $table->string('UOM');
        });

        Schema::create('lines', function (Blueprint $table) {
            $table->id();
            $table->integer('order');
            $table->string('description');
            $table->foreignId('unit_of_measurement_id')->constrained();
            $table->integer('days')->nullable()->default(NULL);
            $table->float('price');
            $table->float('quantity');
            $table->float('total');
            $table->string('formatted_total');
            $table->foreignId('scope_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lines');
        Schema::dropIfExists('unit_of_measurements');
        Schema::dropIfExists('scopes');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('states');
    }
};
