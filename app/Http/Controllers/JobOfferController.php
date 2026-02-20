<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use Illuminate\Http\Request;

class JobOfferController extends Controller
{
    public function index()
    {
        $jobOffers = JobOffer::latest()->paginate(10);
        return view('pages.job_offers.index', compact('jobOffers'));
    }

    public function show(JobOffer $jobOffer)
    {
        return view('pages.job_offers.show', compact('jobOffer'));
    }
}