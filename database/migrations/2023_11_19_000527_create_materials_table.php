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
        Schema::create('material_unit_sizes', function (Blueprint $table) {
            $table->id();
            $table->string('Unit_Size');
        });

        Schema::create('material_categories', function (Blueprint $table) {
            $table->id();
            $table->string('Name');
        });

        Schema::create('material_effective_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->nullable()->default(NULL)->constrained()->onDelete('cascade');
            $table->date('effective_date');
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('SKU');
            $table->string('Name');
            $table->foreignId('material_unit_size_id')->constrained();
            $table->integer('2023-01-01')->nullable()->default(NULL);
            $table->enum('2023-01-01-status', ['New', 'Active', 'Removed', 'Obsolete', 'Discontinued'])->nullable();
            $table->integer('2020-01-01')->nullable()->default(NULL);
            $table->enum('2020-01-01-status', ['New', 'Active', 'Removed', 'Obsolete', 'Discontinued'])->nullable();
            $table->integer('2017-01-01')->nullable()->default(NULL);
            $table->enum('2017-01-01-status', ['New', 'Active', 'Removed', 'Obsolete', 'Discontinued'])->nullable();
            $table->integer('2014-01-01')->nullable()->default(NULL);
            $table->enum('2014-01-01-status', ['New', 'Active', 'Removed', 'Obsolete', 'Discontinued'])->nullable();
            $table->boolean('Discountable');
            $table->foreignId('material_category_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
        Schema::dropIfExists('material_effective_dates');
        Schema::dropIfExists('material_categories');
        Schema::dropIfExists('material_unit_sizes');
    }
};
