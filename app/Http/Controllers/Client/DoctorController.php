<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
	public function __construct(DoctorService $service)
	{

	}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
		$doctors = \App\Models\Doctor::with('specialization')->get();
        return view('client.pages.doctor');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function booking(string $code)
    {
        return view('client.pages.booking', compact('code'));
    }
}
