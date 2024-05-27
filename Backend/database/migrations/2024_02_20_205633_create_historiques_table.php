<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriquesTable extends Migration
{
    public function up()
    {
        Schema::create('historiques', function (Blueprint $table) {
            $table->id();
            $table->string('identifiant', 50);
            $table->string('etat_de_garde', 50);
            $table->dateTime('date_garde');
            $table->integer('note_garde');
            $table->unsignedBigInteger('id_utilisateur');
            // Ajoute d'autres colonnes si nécessaire

            $table->timestamps();
        });

        // Ajoute une clé étrangère dans la table des utilisateurs
        Schema::table('historiques', function (Blueprint $table) {
            $table->foreign('id_utilisateur')->references('id')->on('utilisateurs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('historiques');
    }
}
