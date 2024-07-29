<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAddTituloPaginaServicosEDestinos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servicos', function (Blueprint $table) {
            $table->string('titulo_pagina')->nullable()->after('horario');
        });

        Schema::table('categorias', function (Blueprint $table) {
            $table->string('titulo_pagina')->nullable()->after('slug');
        });

        Schema::table('destinos', function (Blueprint $table) {
            $table->string('titulo_pagina')->nullable()->after('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servicos', function (Blueprint $table) {
            $table->dropColumn('titulo_pagina');
        });

        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn('titulo_pagina');
        });

        Schema::table('destinos', function (Blueprint $table) {
            $table->dropColumn('titulo_pagina');
        });
    }
}
