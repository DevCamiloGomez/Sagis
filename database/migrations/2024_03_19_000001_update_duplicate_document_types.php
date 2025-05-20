<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateDuplicateDocumentTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Actualizar personas con document_type_id = 4 a document_type_id = 1
        DB::table('people')
            ->where('document_type_id', 4)
            ->update(['document_type_id' => 1]);

        // Actualizar personas con document_type_id = 5 a document_type_id = 2
        DB::table('people')
            ->where('document_type_id', 5)
            ->update(['document_type_id' => 2]);

        // Actualizar personas con document_type_id = 6 a document_type_id = 3
        DB::table('people')
            ->where('document_type_id', 6)
            ->update(['document_type_id' => 3]);

        // Eliminar los tipos de documento duplicados
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