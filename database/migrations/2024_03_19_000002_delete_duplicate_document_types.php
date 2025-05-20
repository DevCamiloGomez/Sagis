<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class DeleteDuplicateDocumentTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Eliminar los tipos de documento duplicados (IDs 4, 5 y 6)
        DB::table('document_types')
            ->whereIn('id', [4, 5, 6])
            ->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No podemos revertir la eliminación de los registros duplicados
        // ya que no tenemos la información original
    }
} 