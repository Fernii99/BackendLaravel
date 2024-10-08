<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Cars extends Migration
{
    public function up()
    {
        Schema::create('concesionaires_brands', function (Blueprint $table) {
            // Foreign key to Concessionaires
            $table->foreignId('concesionaire_id')->constrained()->onDelete('cascade');

            // Foreign key to Brands
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');

            // Unique constraint to avoid duplicate pairs
            $table->unique(['concesionaire_id', 'brand_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('concesionaires_brands');
    }
}
