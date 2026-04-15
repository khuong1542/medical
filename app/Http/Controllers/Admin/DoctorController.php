<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Admin\DoctorService;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
	public function __construct(private DoctorService $service) {}
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		return view('admin.pages.doctors.index');
	}

	public function loadList(Request $request)
	{
		$result = [
			'datas' => $this->service->loadList($request->all()),
		];
		return [
			'arrData' => view('admin.pages.doctors.loadList', $result)->render(),
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
	 */
	public function store(Request $request)
	{
		$result = $this->service->store($request->all());
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
	 */
	public function update(Request $request, string $id)
	{
		$result = $this->service->store($request->all(), $id);
		return redirect(route('doctors.index'));
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		//
	}

	/**
	 * Cập nhật trạng thái
	 */
	public function changeStatus(Request $request, $id)
	{
		\DB::beginTransaction();
		try {
			$result = $this->service->changeStatus($request->all(), $id);
			\DB::commit();
			return $result;
		} catch (\Exception $e) {
			\DB::rollback();
			return array('status' => false, 'message' => $e->getMessage());
		}
	}
}
