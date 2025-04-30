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
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auteur_id');
            $table->unsignedBigInteger('cible_id');
            $table->integer('note');
            $table->text('commentaire')->nullable();
            $table->timestamp('dateAvis');
            $table->timestamps();

            // Foreign keys
            $table->foreign('auteur_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cible_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};
