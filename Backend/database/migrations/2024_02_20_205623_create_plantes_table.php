<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlantesTable extends Migration
{
    public function up()
    {
        Schema::create('plantes', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50);
            $table->longText('image');
            $table->string('description', 500);
            $table->string('conseil_entretien', 500);
            $table->unsignedBigInteger('id_session_de_garde')->nullable();
            $table->unsignedBigInteger('id_utilisateur'); // Ajout de la clé étrangère vers utilisateur
            $table->boolean('postee')->default(false); 
            // Ajoutez d'autres colonnes si nécessaire

            $table->timestamps();
        });

        // Ajoute des clés étrangères dans la table des sessions de garde et des utilisateurs
        Schema::table('plantes', function (Blueprint $table) {
            $table->foreign('id_session_de_garde')->references('id')->on('sessions_de_garde')->onDelete('cascade');
            $table->foreign('id_utilisateur')->references('id')->on('utilisateurs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('plantes');
    }
}
