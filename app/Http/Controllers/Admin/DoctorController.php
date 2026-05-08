<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Admin\DoctorService;
use Illuminate\Http\Request;
use Throwable;

class DoctorController extends Controller
{
	public function __construct(private readonly DoctorService $service) {}
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		return view('admin.pages.doctors.index');
	}

	/**
	 * @throws Throwable
	 */
	public function loadList(Request $request)
	{
		$result = [
			'datas' => $this->service->loadList($request->all()),
		];
		return [
			'arrData' => view('admin.pages.doctors.list', $result)->render(),
			'perPage' => $request->offset ?? OFFSET,
		];
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		$result = [
			'checked' => "checked=true",
			'order' => $this->service->count() + 1,
		];
		return view('admin.pages.doctors.form', $result);
	}

	/**
	 * Store a newly created resource in storage.
	 * @throws Throwable
	 */
	public function store(Request $request)
	{
		$result = $this->service->updateOrStore($request->all());
		return redirect(route('doctors.index'));
	}

	/**
	 * Display the specified resource.
	 */
	public function show(string $id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(string $id)
	{
		$data = $this->service->find($id);
		$result = [
			'data' => $data,
			'checked' => "checked=true",
			'order' => $this->service->count() + 1,
		];
		return view('admin.pages.doctors.form', $result);
	}

	/**
	 * Update the specified resource in storage.
	 * @throws Throwable
	 */
	public function update(Request $request, string $id)
	{
		$result = $this->service->updateOrStore($request->all(), $id);
		return redirect(route('doctors.index'));
	}

	/**
	 * Delete multiple records.
	 *
	 * @param Request $request
	 * @return array
	 *
	 * @throws Throwable
	 */
	public function destroy(Request $request)
	{
		return $this->service->destroy($request->all());
	}

	/**
	* Normalize order field for all records (1 → N).
	*
	* @param Request $request
	* @return array
	*
	* @throws Throwable
	*/
	public function updateOrder(Request $request)
	{
		return $this->service->updateOrder($request->all());
	}

	/**
	* Update resource status.
	*
	* @param Request $request
	* @param string $id
	* @return array
	*
	* @throws Throwable
	*/
	public function changeStatus(Request $request, string $id)
	{
		return $this->service->changeStatus($request->all(), $id);
	}
}
