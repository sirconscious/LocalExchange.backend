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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produit_id');
            $table->unsignedBigInteger('acheteur_id');
            $table->unsignedBigInteger('vendeur_id');
            $table->decimal('prix', 10, 2);
            $table->timestamp('dateTransaction');
            $table->timestamps();

            // Foreign keys
            $table->foreign('produit_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('acheteur_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('vendeur_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
