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
        Schema::create('alds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code_ald')->unique();
            $table->string('designation_ald');
            $table->integer('prix_rembousement_ald');
            $table->integer('prix_public_ald');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alds');
    }
};
