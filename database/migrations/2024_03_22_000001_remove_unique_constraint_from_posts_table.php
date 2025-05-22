<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RemoveUniqueConstraintFromPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Primero obtenemos el nombre de la foreign key que está usando el índice
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'posts' 
            AND REFERENCED_TABLE_NAME IS NOT NULL
            AND (COLUMN_NAME = 'post_category_id' OR COLUMN_NAME = 'title')
        ");

        // Eliminamos cada foreign key encontrada
        foreach ($foreignKeys as $foreignKey) {
            Schema::table('posts', function (Blueprint $table) use ($foreignKey) {
                $table->dropForeign($foreignKey->CONSTRAINT_NAME);
            });
        }

        // Ahora podemos eliminar el índice único
        Schema::table('posts', function (Blueprint $table) {
            $table->dropUnique(['post_category_id', 'title']);
        });

        // Recreamos las foreign keys si es necesario
        foreach ($foreignKeys as $foreignKey) {
            if (strpos($foreignKey->CONSTRAINT_NAME, 'post_category_id') !== false) {
                Schema::table('posts', function (Blueprint $table) {
                    $table->foreign('post_category_id')
                          ->references('id')
                          ->on('post_categories')
                          ->onUpdate('cascade')
                          ->onDelete('restrict');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Primero obtenemos el nombre de la foreign key que estaba usando el índice
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'posts' 
            AND REFERENCED_TABLE_NAME IS NOT NULL
            AND (COLUMN_NAME = 'post_category_id' OR COLUMN_NAME = 'title')
        ");

        // Eliminamos cada foreign key encontrada
        foreach ($foreignKeys as $foreignKey) {
            Schema::table('posts', function (Blueprint $table) use ($foreignKey) {
                $table->dropForeign($foreignKey->CONSTRAINT_NAME);
            });
        }

        // Recreamos el índice único
        Schema::table('posts', function (Blueprint $table) {
            $table->unique(['post_category_id', 'title']);
        });

        // Recreamos las foreign keys si es necesario
        foreach ($foreignKeys as $foreignKey) {
            if (strpos($foreignKey->CONSTRAINT_NAME, 'post_category_id') !== false) {
                Schema::table('posts', function (Blueprint $table) {
                    $table->foreign('post_category_id')
                          ->references('id')
                          ->on('post_categories')
                          ->onUpdate('cascade')
                          ->onDelete('restrict');
                });
            }
        }
    }
} 