<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdressesTable extends Migration
{
    public function up()
    {
        Schema::create('adresses', function (Blueprint $table) {
            $table->id();
            $table->string('ville', 50);
            $table->integer('code_postal');
            $table->string('nom_voie', 50);
            $table->string('numero_voie', 10);
            // Ajoute d'autres colonnes si nécessaire

            $table->timestamps();
        });

        // Ajoute une clé étrangère dans la table des utilisateurs
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->foreign('id_adresse')->references('id')->on('adresses')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('adresses');
    }
}
