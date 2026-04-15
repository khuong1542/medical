<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Admin\FacilityService;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
	public function __construct(private FacilityService $service) {}

	public function index()
	{
		$result = [];
		return view('admin.pages.facilities.index', $result);
	}

	public function loadList(Request $request)
	{
		$result = [
			'datas' => $this->service->loadList($request->all()),
		];
		return [
			'arrData' => view('admin.pages.facilities.loadList', $result)->render(),
			'perPage' => $request->offset ?? OFFSET,
		];
	}

	public function create()
	{
		$result = [
		'checked' => "checked=true",
		'order' => $this->service->count() + 1,
		];
		return view('admin.pages.facilities.form', $result);
	}

	public function store(Request $request)
	{
		try {
			$result = $this->service->updateOrStore($request->all());
			return redirect(route('facilities.index'));
		} catch (\Exception $e) {
			return array('status' => false, 'message' => $e->getMessage());
		}
	}

	public function show(string $id)
	{
		$data = $this->service->find($id);
		return view('admin.pages.facilities.show', compact('{$data}'));
	}

	public function edit(string $id)
	{
		$result = [
			'data' => $this->service->find($id),
			'checked' => "checked=true",
			'order' => $this->service->count() + 1,
		];
		return view('admin.pages.facilities.form', $result);
	}

	public function update(Request $request, string $id)
	{
		try {
			$result = $this->service->updateOrStore($request->all(), $id);
			return redirect(route('facilities.index'));
		} catch (\Exception $e) {
			return array('status' => false, 'message' => $e->getMessage());
		}
	}

	public function destroy(Request $request)
	{
		try {
			return $this->service->destroy($request->all());
		} catch (\Exception $e) {
			return array('status' => false, 'message' => $e->getMessage());
		}
	}

	public function updateOrder(Request $request)
	{
		try {
			return $this->service->updateOrder($request->all());
		} catch (\Exception $e) {
			return array('status' => false, 'message' => $e->getMessage());
		}
	}

	public function changeStatus(Request $request, string $id)
	{
		try {
			$result = $this->service->changeStatus($request->all(), $id);
			return $result;
		} catch (\Exception $e) {
			return array('status' => false, 'message' => $e->getMessage());
		}
	}
}