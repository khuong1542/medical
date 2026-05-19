<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Facility\StoreFacilityRequest;
use App\Http\Requests\Admin\Facility\UpdateFacilityRequest;
use App\Http\Services\Admin\FacilityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class FacilityController extends Controller
{
	public function __construct(private readonly FacilityService $service) {}

	public function index()
	{
		$result = [];
		return view('admin.pages.facilities.index', $result);
	}

	/**
	 * Load list data
	 *
	 * @param Request $request
	 * @return array
	 *
	 * @throws Throwable
	 */
	public function loadList(Request $request)
	{
		$result = [
			'datas' => $this->service->loadList($request->all()),
		];
		return [
			'arrData' => view('admin.pages.facilities.list', $result)->render(),
			'perPage' => $request->offset ?? OFFSET,
		];
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return View
	 */
	public function create()
	{
		$result = [
			'types' => $this->service->getFacility(),
			'order' => $this->service->count() + 1,
		];
		return view('admin.pages.facilities.form', $result);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param StoreFacilityRequest $request
	 * @return RedirectResponse
	 *
	 * @throws Throwable
	 */
	public function store(StoreFacilityRequest $request)
	{
		$this->service->updateOrStore($request->validated());
		return redirect(route('facilities.index'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param string $id Resource identifier
	 * @return View
	 */
	public function show(string $id)
	{
		$data = $this->service->find($id);
		return view('admin.pages.facilities.show', compact('data'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param string $id Resource identifier
	 * @return View
	 */
	public function edit(string $id)
	{
		$result = [
			'data' => $this->service->find($id),
			'types' => $this->service->getFacility(),
			'order' => $this->service->count() + 1,
		];
		return view('admin.pages.facilities.form', $result);
	}

	/**
	 * Update resource and redirect to index.
	 *
	 * @param UpdateFacilityRequest $request
	 * @param string $id
	 * @return RedirectResponse
	 *
	 * @throws Throwable
	 */
	public function update(UpdateFacilityRequest $request, string $id)
	{
		$this->service->updateOrStore($request->validated(), $id);
		return redirect()->route('facilities.index');
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
