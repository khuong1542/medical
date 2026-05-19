<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\Client\DoctorService;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
	public function __construct(private readonly DoctorService $service) {}
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$result = [
			'datas' => $this->service->index($request->all()),
			'facilities' => $this->service->getFacility(),
		];
		return view('client.pages.doctors.index', $result);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function detail(string $code)
	{
		$result = [
			'data' => $this->service->findBy(['code' => $code]),
		];
		return view('client.pages.doctors.detail', $result);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function booking(string $code)
	{
		return view('client.pages.doctors.booking', compact('code'));
	}
}
