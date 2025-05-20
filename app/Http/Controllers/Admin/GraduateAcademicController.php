<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepository;
use App\Repositories\RoleRepository;
use App\Services\S3UploadService;
use Illuminate\Http\Request;

class GraduateAcademicController extends Controller
{
    private $companyRepository;
    private $s3UploadService;
    private $role;

    public function __construct(CompanyRepository $companyRepository, RoleRepository $roleRepository, S3UploadService $s3UploadService)
    {
        $this->companyRepository = $companyRepository;
        $this->s3UploadService = $s3UploadService;

        $this->role = $roleRepository->getByAttribute('name', 'graduado');
    }

    public function index()
    {
        // Implementation of the index method
    }

    public function create()
    {
        // Implementation of the create method
    }

    public function store(Request $request)
    {
        // Implementation of the store method
    }

    public function show($id)
    {
        // Implementation of the show method
    }

    public function edit($id)
    {
        // Implementation of the edit method
    }

    public function update(Request $request, $id)
    {
        // Implementation of the update method
    }

    public function destroy($id)
    {
        // Implementation of the destroy method
    }
} 