<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateSeanceEntretiensTable extends Migration
{
    public function up()
    {
        Schema::create('seance_entretiens', function (Blueprint $table) {
            $table->id();
            $table->string('image', 100);
            $table->dateTime('date_entretien');
            $table->string('description', 500);
            $table->string('etat_plante', 50);
            $table->string('commentaire_botaniste', 500);
            $table->unsignedBigInteger('id_session_de_garde');
            // Ajoute d'autres colonnes si nécessaire

            $table->timestamps();
        });

        // Ajoute une clé étrangère dans la table des sessions de garde
        Schema::table('seance_entretiens', function (Blueprint $table) {
            $table->foreign('id_session_de_garde')->references('id')->on('sessions_de_garde')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('seance_entretiens');
    }
}
