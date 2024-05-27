<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateUtilisateursTable extends Migration
{
    public function up()
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50);
            $table->string('prenom', 50);
            $table->date('date_de_naissance');
            $table->string('adresse_mail', 50);
            $table->string('mot_de_passe', 255);
            $table->string('telephone', 20);
            $table->unsignedBigInteger('id_adresse');
            $table->string('id_role', 50);
            // Ajoute d'autres colonnes si n�cessaire

            $table->timestamps();
        });

        // Ajoute des cl�s �trang�res
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->foreign('id_adresse')->references('id')->on('adresses')->onDelete('cascade');
            $table->foreign('id_role')->references('identifiant')->on('roles');
        });
    }

    public function down()
    {
        Schema::dropIfExists('utilisateurs');
    }
}
