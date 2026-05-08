@extends('admin.index')

@section('content')
<div class="page-content">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row align-items-center">
				<div class="col-sm mb-2 mb-sm-0">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb breadcrumb-no-gutter">
							<li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('doctors.index') }}">@lang('admin/messages.doctor.title')</a></li>
							<li class="breadcrumb-item active" aria-current="page">{{ isset($data->id) ? __('admin/messages.doctor.form_title_edit') : __('admin/messages.doctor.form_title_add') }}</li>
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
			@include('admin.layouts.form.button', ['url' => route('doctors.index')])
			<div class="row my-3">
				<div class="col-md-8">
					<div class="card mb-0">
						<div class="card-body">
							<div class="mb-3 grid grid-2 gap-3">
								<div class="form-group">
									<label for="facility_id" class="required"><span>@lang('admin/messages.doctor.facility')</span></label>
									<select name="facility_id" id="facility_id" class="form-control chzn-select {{ $errors->has('facility_id') ? 'is-invalid' : '' }}">
										<option selected disabled>@lang('admin/shared.select-item')</option>
										@if(isset($facilities) && count($facilities) > 0)
											@foreach($facilities as $key => $item)
												<option value="{{$item->id}}" @if(isset($data->facility_id) && $data->facility_id === $item->id) selected @endif>{{$item->name}}</option>
											@endforeach
										@endif
									</select>
									@error('facility_id')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
								<div class="form-group">
									<label for="specialty_id" class="required"><span>@lang('admin/messages.doctor.specialty')</span></label>
									<select name="specialty_id" id="specialty_id" class="form-control chzn-select {{ $errors->has('specialty_id') ? 'is-invalid' : '' }}">
										<option selected disabled>@lang('admin/shared.select-item')</option>
										@if(isset($specialties) && count($specialties) > 0)
											@foreach($specialties as $key => $item)
												<option value="{{$item->id}}" @if(isset($data->specialty_id) && $data->specialty_id === $item->id) selected @endif>{{$item->name}}</option>
											@endforeach
										@endif
									</select>
									@error('specialty_id')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
							</div>
							<div class="mb-3 grid grid-2 gap-3">
								<div class="form-group">
									<label for="code" class="required"><span>@lang('admin/shared.code')</span></label>
									<input type="text" name="code" id="code" class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" placeholder="@lang('admin/shared.code')" value="{{ old('code', $data->code ?? '') }}">
									@error('code')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
								<div class="form-group">
									<label for="name" class="required"><span>@lang('admin/shared.name')</span></label>
									<input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="@lang('admin/shared.name')" value="{{ old('name', $data->name ?? '') }}">
									@error('name')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
							</div>
							<div class="mb-3 grid grid-2 gap-3">
								<div class="form-group">
									<label for="email" class="required"><span>@lang('admin/shared.email')</span></label>
									<input type="email" name="email" id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="@lang('admin/shared.email')" value="{{ old('email', $data->email ?? '') }}">
									@error('email')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
								<div class="form-group">
									<label for="phone" class="required"><span>@lang('admin/shared.phone')</span></label>
									<input type="text" name="phone" id="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" placeholder="@lang('admin/shared.phone')" value="{{ old('phone', $data->phone ?? '') }}">
									@error('phone')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
							</div>
							<div class="mb-3">
								<div class="form-group">
									<label for="experience_years" class="required"><span>@lang('admin/messages.doctor.experience_years')</span></label>
									<input type="text" name="experience_years" id="experience_years" class="form-control {{ $errors->has('experience_years') ? 'is-invalid' : '' }}" placeholder="@lang('admin/messages.doctor.experience_years')" value="{{ old('experience_years', $data->experience_years ?? '') }}">
									@error('experience_years')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
							</div>
							<div class="mb-3">
								<div class="form-group">
									<label for="description"><span>@lang('admin/shared.description')</span></label>
									<textarea name="description" id="description" class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" rows="5" placeholder="@lang('admin/shared.description')">{!! old('description', $data->description ?? '') !!}</textarea>
									@error('description')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
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
								<label for="order" class="required"><span>@lang('admin/shared.order')</span></label>
								<input type="number" name="order" id="order" class="form-control {{ $errors->has('order') ? 'is-invalid' : '' }}" placeholder="@lang('admin/shared.order')" value="{{ old('order', $data->order ?? $order ?? '') }}">
								@error('order')
									<span class="invalid-feedback">{{ $message }}</span>
								@enderror
							</div>
							<div class="mb-3 form-group">
								<label for="status"><span>@lang('admin/shared.visibility')</span></label>
								<label class="form-control ps-0 mt-0 border-0 d-flex align-items-center gap-2">
									<input type="hidden" name="status" value="0">
									<input type="checkbox" name="status" id="status" {{ old('status', $data->status ?? 1) ? 'checked' : '' }}> {{ __('admin/shared.published') }}
								</label>
								@error('status')
									<span class="invalid-feedback d-block">{{ $message }}</span>
								@enderror
							</div>
						</div>
					</div>
				</div>
			</div>
			@include('admin.layouts.form.button', ['url' => route('doctors.index')])
		</form>
	</div>
</div>
@endsection

@section('script')
<script>
	$(document).ready(() => {
		$('label').addClass('user-select-none');
	});
</script>
@endsection
