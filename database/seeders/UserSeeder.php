<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Repositories\UserRepository;
use App\Repositories\PersonRepository;
use App\Repositories\RoleRepository;

class UserSeeder extends Seeder
{
    /** @var UserRepository */
    protected $userRepository;

    /** @var PersonRepository */
    protected $personRepository;

    /** @var RoleRepository */
    protected $roleRepository;

    /** @var Storage */
    protected $storage;

    public function __construct(
        UserRepository $userRepository,
        PersonRepository $personRepository,
        RoleRepository $roleRepository,
        Storage $storage
    ) {
        $this->userRepository = $userRepository;
        $this->personRepository = $personRepository;
        $this->roleRepository = $roleRepository;
        $this->storage = $storage;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $roles = $this->roleRepository->all();

            $this->personRepository->all()
                ->whereNotIn('id', [1, 2])
                ->map(function ($person) use ($roles) {
                    $this->userRepository->createFactory(1, [
                        'person_id' => $person->id
                    ]);

                    /** @var \App\Models\User */
                    $user = $this->userRepository->getByAttribute('person_id', $person->id);

                    /** @var \Spatie\Permission\Models\Role */
                    $roleGraduate = $roles->where('name', 'graduado')->first();

                    /** Creating Graduate */
                    $user->roles()->attach($roleGraduate);
                });
        } catch (\Exception $th) {
            print($th->getMessage());
        }
    }
}
