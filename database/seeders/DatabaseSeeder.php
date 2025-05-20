<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,

            CompanySeeder::class,

            AcademicLevelSeeder::class,
            UniversitySeeder::class,
            FacultySeeder::class,
            ProgramSeeder::class,

            DocumentTypeSeeder::class,
            PersonSeeder::class,
            PersonAcademicSeeder::class,
            PersonCompanySeeder::class,

            RoleSeeder::class,
            AdminSeeder::class,
            UserSeeder::class,

            PostCategorySeeder::class,
            PostSeeder::class,
            PostImageSeeder::class,
            PostVideoSeeder::class,
            CarouselImageSeeder::class
        ]);
    }
}
