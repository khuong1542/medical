@extends('client.index')

@section('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/chosen/chosen.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/client/css/pages/booking.css') }}">
@endsection

@section('content')

@include('client.layouts.breadcrumb')

<div class="container my-4">
	<div class="row">
		<div class="booking-wizard">
			<ul class="form-wizard-steps d-flex gap-4">
				@php
					$steps = [
						'Specialty',
						'Appointment Type',
						'Date & Time',
						'Basic Information',
						'Payment',
						'Confirmation',
					];
				@endphp
				@foreach ($steps as $index => $step)
				<li class="progress-step {{ $index === 0 ? 'progress-active' : '' }}">
					<div class="profile-step">
						<span class="multi-steps">{{ $index + 1 }}</span>

						<div class="step-section">
							<h6>{{ $step }}</h6>
						</div>
					</div>
				</li>
				@endforeach
			</ul>
		</div>
		<div class="booking-widget">
			<div class="d-flex flex-column gap-3">
				<div class="card doctor-card">
					<div class="card-body">
						<div class="d-flex gap-3 flex-wrap">
							@php
								$images = $doctor ? json_decode(($doctor->images ?? ''), true) : [];
							@endphp
							<div class="doctor-avatar avatar-rounded">
								<img src="{{ $images['url'] ?? '' }}" alt="{{ $images['name'] }}">
							</div>
							<div class="doctor-content">
								<h4 class="mb-1 doctor-name">Dr. Michael Brown <span class="badge bg-orange fs-12 doctor-rating"><i class="fa-solid fa-star me-1"></i>5.0</span></h4>
								<p class="text-indigo mb-3 fw-medium doctor-specialty">Psychologist</p>
								<p class="mb-0 doctor-address">
									<i class="fa-regular fa-location-dot"></i>
									<i class="fa-solid fa-location-dot me-2"></i>5th Street - 1011 W 5th St, Suite 120, Austin, TX 78703
								</p>
							</div>
						</div>
					</div>
				</div>
				<div class="card booking-card">
					<div class="card-body booking-body">
						@for ($step = 1; $step <= 6; $step++)
							<fieldset class="booking-step {{ $step == 4 ? 'active' : '' }}">
							@include("client.pages.doctors.booking-step.step-$step")
							</fieldset>
						@endfor
					</div>
					<div class="card-footer">
						<div class="d-flex booking-actions">
							<button type="button" class="btn btn-secondary btn-back-step"><i class="fa-solid fa-arrow-left"></i> Back</button>
							<button type="button" class="btn btn-primary btn-next-step">Next <i class="fa-solid fa-arrow-right"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/vendor/chosen/chosen.min.js') }}"></script>
<script src="{{ asset('assets/client/js/pages/booking.js') }}"></script>
@endsection