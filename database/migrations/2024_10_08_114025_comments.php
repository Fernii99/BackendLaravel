<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Comments extends Migration
{
    public function up()
    {
        Schema::create('cars_comments', function (Blueprint $table) {
            $table->id('comment_id'); // Auto-incrementing primary key
            $table->foreignId('id')->constrained('cars')->onDelete('cascade'); // Foreign key referencing cars
            $table->text('comment');
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('car_comments');
    }
}
