@extends('admin.index')

@section('content')
<div class="page-content">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row align-items-center">
				<div class="col-sm mb-2 mb-sm-0">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb breadcrumb-no-gutter">
							<li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('specialties.index') }}">@lang('admin/messages.specialty.title')</a></li>
							<li class="breadcrumb-item active" aria-current="page">{{ isset($data->id) ? __('admin/messages.specialty.form_title_edit') : __('admin/messages.specialty.form_title_add') }}</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
		<form action="{{ isset($data->id) ? route('specialties.update', $data->id) : route('specialties.store')}}" id="frm_add" class="mb-3" method="post" enctype="multipart/form-data" autocomplete="off">
			@csrf
			@if(isset($data->id))
				@method('PUT')
			@endif

			@include('admin.layouts.form.button', ['url' => route('specialties.index')])
			<div class="row my-3">
				<div class="col-md-8">
					<div class="card mb-0">
						<div class="card-body">
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
									<label for="type" class="required"><span>@lang('admin/messages.specialty.type')</span></label>
									<select name="type" id="type" class="form-control chzn-select {{ $errors->has('type') ? 'is-invalid' : '' }}">
										<option selected disabled>@lang('admin/shared.select-item')</option>
										@if(isset($types) && count($types) > 0)
											@foreach($types as $key => $item)
												<option value="{{$item->value}}" {{ old('type', isset($data->type) ? $data->type : null) == $item->value ? 'selected' : '' }}>{{$item->label()}}</option>
											@endforeach
										@endif
									</select>
									@error('type')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
								<div class="form-group">
									<label for="tax_code"><span>@lang('admin/messages.specialty.tax_code')</span></label>
									<input type="text" name="tax_code" id="tax_code" class="form-control {{ $errors->has('tax_code') ? 'is-invalid' : '' }}" placeholder="@lang('admin/messages.specialty.tax_code')" value="{{ old('tax_code', $data->tax_code ?? '') }}">
									@error('tax_code')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
							</div>
							<div class="mb-3 grid grid-2 gap-3">
								<div class="form-group">
									<label for="email"><span>@lang('admin/shared.email')</span></label>
									<input type="email" name="email" id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="@lang('admin/shared.email')" value="{{ old('email', $data->email ?? '') }}">
									@error('email')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
								<div class="form-group">
									<label for="phone"><span>@lang('admin/shared.phone')</span></label>
									<input type="text" name="phone" id="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" placeholder="@lang('admin/shared.phone')" value="{{ old('phone', $data->phone ?? '') }}">
									@error('phone')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
							</div>
							<div class="mb-3 grid grid-2 gap-3">
								<div class="form-group">
									<label for="website"><span>@lang('admin/messages.facility.website')</span></label>
									<input type="text" name="website" id="website" class="form-control {{ $errors->has('website') ? 'is-invalid' : '' }}" placeholder="@lang('admin/messages.facility.website')" value="{{ old('website', $data->website ?? '') }}">
									@error('website')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
								<div class="form-group">
									<label for="address"><span>@lang('admin/messages.facility.address')</span></label>
									<input type="text" name="address" id="address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" placeholder="@lang('admin/messages.facility.address')" value="{{ old('address', $data->address ?? '') }}">
									@error('address')
										<span class="invalid-feedback">{{ $message }}</span>
									@enderror
								</div>
							</div>
							<div class="mb-3">
								<div class="form-group">
									<label for="map_url"><span>@lang('admin/messages.facility.map_url')</span></label>
									<input type="text" name="map_url" id="map_url" class="form-control {{ $errors->has('map_url') ? 'is-invalid' : '' }}" placeholder="@lang('admin/messages.facility.map_url')" value="{{ old('map_url', $data->map_url ?? '') }}">
									@error('map_url')
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
			@include('admin.layouts.form.button', ['url' => route('specialties.index')])
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