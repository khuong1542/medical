@extends('admin.index')

@section('content')
<div class="page-content">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row align-items-center">
				<div class="col-sm mb-2 mb-sm-0">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb breadcrumb-no-gutter">
							<li class="breadcrumb-item"><a class="breadcrumb-link" href="ecommerce-products.html">Products</a></li>
							<li class="breadcrumb-item active" aria-current="page">Add Product</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
		<form action="{{ isset($data->id) ? route('doctors.update', $data->id) : route('doctors.store')}}" id="frm_add" class="mb-3" method="post" enctype="multipart/form-data" autocomplete="off">
			@csrf
			@if(isset($data->id))
			@method('PUT')
			@endif
			<div class="d-flex mb-3">
				<button type="submit" class="btn btn-primary">@lang('admin/shared.save')</button>
				<button type="submit" class="btn btn-secondary ms-2">@lang('admin/shared.close')</button>
			</div>
			<div class="row">
				<div class="col-md-8">
					<div class="card mb-3">
						<div class="card-body">
							<div class="mb-3 grid grid-2 gap-3">
								<div class="form-group">
									<label class="required"><span>@lang('admin/messages.doctor.facility')</span></label>
									<select name="facility_id" id="facility_id" class="form-control chzn-select">
										<option selected disabled>Select a item</option>
										@if(isset($facilities) && count($facilities) > 0)
										@foreach($facilities as $key => $item)
										<option value="{{$item->id}}" @if(isset($data->facility_id) && $data->facility_id === $item->id) selected @endif>{{$item->name}}</option>
										@endforeach
										@endif
									</select>
								</div>
								<div class="form-group">
									<label class="required"><span>@lang('admin/messages.doctor.specialty')</span></label>
									<select name="facility_id" id="facility_id" class="form-control chzn-select">
										<option selected disabled>Select a item</option>
										@if(isset($facilities) && count($facilities) > 0)
										@foreach($facilities as $key => $item)
										<option value="{{$item->id}}" @if(isset($data->facility_id) && $data->facility_id === $item->id) selected @endif>{{$item->name}}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="mb-3 grid grid-2 gap-3">
								<div class="form-group">
									<label class="required"><span>@lang('admin/shared.code')</span></label>
									<input type="text" name="code" id="code" class="form-control" placeholder="@lang('admin/shared.code')" value="{{ $data->code ?? '' }}">
								</div>
								<div class="form-group">
									<label class="required"><span>@lang('admin/shared.name')</span></label>
									<input type="text" name="name" id="name" class="form-control" placeholder="@lang('admin/shared.name')" value="{{ $data->name ?? '' }}">
								</div>
							</div>
							<div class="mb-3 grid grid-2 gap-3">
								<div class="form-group">
									<label class="required"><span>@lang('admin/shared.email')</span></label>
									<input type="email" name="email" id="email" class="form-control" placeholder="@lang('admin/shared.email')" value="{{ $data->email ?? '' }}">
								</div>
								<div class="form-group">
									<label class="required"><span>@lang('admin/shared.phone')</span></label>
									<input type="text" name="phone" id="phone" class="form-control" placeholder="@lang('admin/shared.phone')" value="{{ $data->phone ?? '' }}">
								</div>
							</div>
							<div class="mb-3">
								<div class="form-group">
									<label class="required"><span>@lang('admin/messages.doctor.experience_years')</span></label>
									<input type="text" name="experience_years" id="experience_years" class="form-control" placeholder="@lang('admin/messages.doctor.experience_years')" value="{{ $data->experience_years ?? '' }}">
								</div>
							</div>
							<div class="mb-3">
								<div class="form-group">
									<label><span>@lang('admin/shared.description')</span></label>
									<textarea name="description" id="description" class="form-control" rows="5" placeholder="@lang('admin/shared.description')">{!! $data->description ?? '' !!}</textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="card mb-3">
						<div class="card-body">
							<div class="mb-3 form-group">
								@php
								$images = json_decode(($data->images ?? ''), true);
								@endphp
								<label class="required"><span>@lang('admin/shared.image')</span></label>
								<label for="images" class="btn btn-default">@lang('admin/shared.choose-image')</label>
								<div class="upload-wrapper">
									<input hidden type="file" name="images" id="images" onchange="showImage(this)">
									<label class="upload-preview" for="images">
										<div class="preview-image {{ isset($images['url']) ? 'preview' : '' }}">
											@if(isset($images['url']))
											<img src="{{ $images['url'] }}" alt="{{ $images['name'] }}">
											@endif
										</div>
										<div class="upload-context">
											<div class="upload-context-title text-primary">
												<i class="fa-solid fa-file-image"></i>
												<span>@lang('admin/shared.choose-image')</span>
											</div>
											<div class="upload-context-note">@lang('admin/shared.max-file') 10MB</div>
										</div>
									</label>
								</div>
							</div>
							<div class="mb-3 form-group">
								<label class="required"><span>@lang('admin/shared.order')</span></label>
								<input type="number" name="order" id="order" class="form-control" placeholder="@lang('admin/shared.order')" value="{{ $data->order ?? ( $order ?? '' ) }}">
							</div>
							<div class="mb-3 form-group">
								<label><span>@lang('admin/shared.visibility')</span></label>
								<label class="form-control ps-0 mt-0 border-0 d-flex align-items-center gap-2"><input type="checkbox" name="status" id="status" {{ $checked ?? '' }}> {{ __('admin/shared.published') }}</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="d-flex">
				<button type="submit" class="btn btn-primary">@lang('admin/shared.save')</button>
				<a href="{{ route('doctors.index') }}" class="btn btn-secondary ms-2">@lang('admin/shared.close')</a>
			</div>
		</form>
	</div>
</div>
@endsection
