<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateSessionsDeGardeTable extends Migration
{
    public function up()
    {
        Schema::create('sessions_de_garde', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_de_debut');
            $table->dateTime('date_de_fin');
            $table->unsignedBigInteger('id_utilisateur')->nullable();
            // Ajoute d'autres colonnes si n�cessaire

            $table->timestamps();
        });

        // Ajoute une cl� �trang�re dans la table des utilisateurs
        Schema::table('sessions_de_garde', function (Blueprint $table) {
            $table->foreign('id_utilisateur')->references('id')->on('utilisateurs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sessions_de_garde');
    }
}
