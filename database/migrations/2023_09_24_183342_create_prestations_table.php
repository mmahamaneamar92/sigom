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
        Schema::create('prestations', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->string('assure');
            $table->foreign('assure')->references('numero_assurer')->on('assures')->onDelete('cascade');
            $table->string('nature_examen');
            $table->string('renseignement');
            $table->string('prescripteur');
            $table->foreign('prescripteur')->references('code_prescripteur')->on('prescripteurs')->onDelete('cascade');
            $table->string('technicien');
            $table->foreign('technicien')->references('code_technicien')->on('techniciens')->onDelete('cascade');
            $table->string('provenance');
            $table->foreign('provenance')->references('code_hopital')->on('hopitals')->onDelete('cascade');
            $table->string('pharmacie');
            $table->foreign('pharmacie')->references('code_pharmacie')->on('pharmacies')->onDelete('cascade');
            $table->string('feuille');
            $table->string('controleur');
            $table->foreign('controleur')->references('email')->on('users')->onDelete('cascade');
            $table->string('ap1');
            $table->foreign('ap1')->references('code_ap')->on('aps')->onDelete('cascade');
            $table->string('ap2');
            $table->foreign('ap2')->references('code_ap')->on('aps')->onDelete('cascade');
            $table->string('ap3');
            $table->foreign('ap3')->references('code_ap')->on('aps')->onDelete('cascade');
            $table->string('ap4');
            $table->foreign('ap4')->references('code_ap')->on('aps')->onDelete('cascade');
            $table->string('ap5');
            $table->foreign('ap5')->references('code_ap')->on('aps')->onDelete('cascade');
            $table->string('ap6');
            $table->foreign('ap6')->references('code_ap')->on('aps')->onDelete('cascade');
            $table->string('ap7');
            $table->foreign('ap7')->references('code_ap')->on('aps')->onDelete('cascade');
            $table->string('ap8');
            $table->foreign('ap8')->references('code_ap')->on('aps')->onDelete('cascade');
            $table->string('ap9');
            $table->foreign('ap9')->references('code_ap')->on('aps')->onDelete('cascade');
            $table->string('ap10');
            $table->foreign('ap10')->references('code_ap')->on('aps')->onDelete('cascade');
            $table->string('type1');
            $table->string('type2');
            $table->string('type3');
            $table->string('type4');
            $table->string('type5');
            $table->string('type6');
            $table->string('type7');
            $table->string('type8');
            $table->string('type9');
            $table->string('type10');
            $table->integer('quantite1');
            $table->integer('quantite2');
            $table->integer('quantite3');
            $table->integer('quantite4');
            $table->integer('quantite5');
            $table->integer('quantite6');
            $table->integer('quantite7');
            $table->integer('quantite8');
            $table->integer('quantite9');
            $table->integer('quantite10');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestations');
    }
};
