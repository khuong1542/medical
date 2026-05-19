@extends('client.index')

@section('style')
<link rel="stylesheet" href="{{ asset('assets/client/css/pages/doctor.css') }}">
@endsection

@section('content')
<div class="container-fluid breadcrumb-banner py-5 mb-5 wow fadeIn">
	<div class="container py-5">
		<h1 class="display-3 mb-3 breadcrumb-title">Doctors</h1>
		<nav aria-label="breadcrumb animated">
			<ol class="breadcrumb custom-breadcrumb text-uppercase mb-0">
				<li class="breadcrumb-item"><a class="" href="#">Home</a></li>
				<li class="breadcrumb-item"><a class="" href="#">Pages</a></li>
				<li class="breadcrumb-item text-primary active" aria-current="page">Doctors</li>
			</ol>
		</nav>
	</div>
</div>

<div class="container my-4">
	<div class="text-center mx-auto mb-5 wow fadeInUp">
		<h1>Our Experience Doctors</h1>
	</div>
	<div class="row">
		<div class="doctor-search">
			<div class="form-search row">
				<fieldset class="filter-group col-md-3">
					<select name="facility" id="facility" class="form-control">
						<option value="">@lang('client/shared.select-item')</option>
						@foreach($facilities as $facility)
						<option value="{{ $facility->value }}">{{ $facility->label() }}</option>
						@endforeach
					</select>
				</fieldset>
				<fieldset class="filter-group col-md-3">
					<div class="input-group">
						<input type="text" class="form-control" name="keyword" id="keyword" autocomplete="off" onkeydown="if (event.key == 'Enter'){search();return false;}" placeholder="@lang('client/shared.placeholder-search')">
						<span class="input-group-btn">
							<button type="button" class="btn btn-primary" id="btn_search"><i class="fa-solid fa-search"></i></button>
						</span>
					</div>
				</fieldset>
				<fieldset class="filter-group col-md-1">
					<button class="btn btn-danger">Clear</button>
				</fieldset>
			</div>
		</div>
		<div class="doctors">
			<div class="row gy-4">
				@if(isset($datas) && count($datas) > 0)
					@foreach($datas as $doctor)
						@php
							$images = json_decode(($doctor->images ?? ''), true);
						@endphp
						<div class="col-lg-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
							<div class="doctor-inner">
								<div class="doctor-image">
									<a href="{{ route('client.doctors.detail', ['code' => $doctor->code]) }}">
										<img class="img-fluid" src="{{ $images['url'] ?? '' }}" alt="{{ $images['name'] ?? '' }}">
									</a>
								</div>
								<div class="doctor-info">
									<a href="{{ route('client.doctors.detail', ['code' => $doctor->code]) }}">
										<h4 class="doctor-name">{{ $doctor->name ?? '' }}</h4>
									</a>
									<span class="specialty">{{ $doctor->specialty?->name ?? '' }}</span>
									<div class="mt-3">
										<a href="{{ route('client.doctors.booking', ['code' => $doctor->code]) }}" class="btn btn-primary">Book Now</a>
									</div>
								</div>
							</div>
						</div>
					@endforeach
				@endif
			</div>
		</div>
	</div>
</div>
@endsection