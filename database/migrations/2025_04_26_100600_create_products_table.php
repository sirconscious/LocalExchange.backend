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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description');
            $table->float('prix');
            $table->string('localisation');
            $table->unsignedBigInteger('vendeur_id');
            // $table->unsignedBigInteger('categorie_id');
            $table->timestamp('dateDepot');
            $table->timestamps();

            // Foreign keys
            $table->foreign('vendeur_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('categorie_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
