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
        $driver = DB::getDriverName();
        $foreignKeys = [];

        if ($driver === 'mysql') {
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'posts' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
                AND (COLUMN_NAME = 'post_category_id' OR COLUMN_NAME = 'title')
            ");
        } elseif ($driver === 'pgsql') {
            $foreignKeys = DB::select("
                SELECT constraint_name as \"CONSTRAINT_NAME\"
                FROM information_schema.key_column_usage
                WHERE table_name = 'posts'
                AND (column_name = 'post_category_id' OR column_name = 'title')
                AND constraint_name IN (
                    SELECT constraint_name 
                    FROM information_schema.table_constraints 
                    WHERE constraint_type = 'FOREIGN KEY'
                )
            ");
        }

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
        $driver = DB::getDriverName();
        $foreignKeys = [];

        if ($driver === 'mysql') {
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'posts' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
                AND (COLUMN_NAME = 'post_category_id' OR COLUMN_NAME = 'title')
            ");
        } elseif ($driver === 'pgsql') {
            $foreignKeys = DB::select("
                SELECT constraint_name as \"CONSTRAINT_NAME\"
                FROM information_schema.key_column_usage
                WHERE table_name = 'posts'
                AND (column_name = 'post_category_id' OR column_name = 'title')
                AND constraint_name IN (
                    SELECT constraint_name 
                    FROM information_schema.table_constraints 
                    WHERE constraint_type = 'FOREIGN KEY'
                )
            ");
        }

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