<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobOfferController extends Controller
{
    public function index()
    {
        $jobOffers = JobOffer::latest()->get();
        return view('admin.pages.job_offers.index', compact('jobOffers'));
    }

    public function create()
    {
        return view('admin.pages.job_offers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'description' => 'required',
            'contact_email' => 'nullable|email',
        ]);

        $data = $request->all();
        $data['admin_id'] = Auth::guard('admin')->id();
        $data['is_active'] = $request->has('is_active');

        JobOffer::create($data);

        return redirect()->route('admin.job-offers.index')->with('success', 'Oferta creada exitosamente.');
    }

    public function edit(JobOffer $jobOffer)
    {
        return view('admin.pages.job_offers.create', compact('jobOffer'));
    }

    public function update(Request $request, JobOffer $jobOffer)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'description' => 'required',
            'contact_email' => 'nullable|email',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $jobOffer->update($data);

        return redirect()->route('admin.job-offers.index')->with('success', 'Oferta actualizada exitosamente.');
    }

    public function destroy(JobOffer $jobOffer)
    {
        $jobOffer->delete();
        return redirect()->route('admin.job-offers.index')->with('success', 'Oferta eliminada exitosamente.');
    }

    public function toggleStatus(JobOffer $jobOffer)
    {
        $jobOffer->is_active = !$jobOffer->is_active;
        $jobOffer->save();

        return redirect()->route('admin.job-offers.index')->with('success', 'Estado actualizado.');
    }
}