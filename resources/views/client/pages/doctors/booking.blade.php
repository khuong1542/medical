@extends('client.index')

@section('style')
<link rel="stylesheet" href="{{ asset('assets/client/css/pages/booking.css') }}">
@endsection

@section('content')

@include('client.layouts.breadcrumb')

<div class="container my-4">
	<div class="row">
		<div class="booking-wizard">
			<ul class="form-wizard-steps d-flex gap-4">
				<li class="progress-active">
					<div class="profile-step">
						<span class="multi-steps">1</span>
						<div class="step-section">
							<h6>Specialty</h6>
						</div>
					</div>
				</li>
				<li>
					<div class="profile-step">
						<span class="multi-steps">2</span>
						<div class="step-section">
							<h6>Appointment Type</h6>
						</div>
					</div>
				</li>
				<li>
					<div class="profile-step">
						<span class="multi-steps">3</span>
						<div class="step-section">
							<h6>Date &amp; Time</h6>
						</div>
					</div>
				</li>
			</ul>
		</div>
		<div class="booking-widget">
			<div class="d-flex flex-column gap-3">
				<div class="card doctor-info">
					<div class="card-body">
						<div class="d-flex gap-3 flex-wrap">
							<div class="avatar avatar-rounded">
								<img src="" alt="">
							</div>
							<div class="doctor-info">
								<h4 class="mb-1">Dr. Michael Brown <span class="badge bg-orange fs-12"><i class="fa-solid fa-star me-1"></i>5.0</span></h4>
								<p class="text-indigo mb-3 fw-medium">Psychologist</p>
								<p class="mb-0"><i class="isax isax-location me-2"></i>5th Street - 1011 W 5th St, Suite 120, Austin, TX 78703</p>
							</div>
						</div>
					</div>
				</div>
				<div class="card booking-card">
					<div class="card-body booking-body">
						<fieldset class="booking-step active">
							<div class="booking-speciality">
								<label for="">Select Speciality</label>
								<select name="" id="" class="form-control">
									<option value="">@lang('client/shared.select-item')</option>
								</select>
							</div>
							<div class="booking-service">
								<label for="">Service</label>
								<div class="row">
									<div class="col-lg-4 col-md-6">
										<div class="service-item active">
											<input class="form-check-input ms-0 mt-0" name="service1" type="checkbox" id="service1" checked="">
											<label class="form-check-label ms-2" for="service1">
												<span class="service-title d-block mb-1">Echocardiograms</span>
												<span class="fs-14 d-block">$310</span>
											</label>
										</div>
									</div>
									<div class="col-lg-4 col-md-6">
										<div class="service-item">
											<input class="form-check-input ms-0 mt-0" name="service1" type="checkbox" id="service2">
											<label class="form-check-label ms-2" for="service2">
												<span class="service-title d-block mb-1">Stress tests</span>
												<span class="fs-14 d-block">$754</span>
											</label>
										</div>
									</div>
								</div>
							</div>
						</fieldset>
						<fieldset class="booking-step"></fieldset>
						<fieldset class="booking-step"></fieldset>
					</div>
					<div class="card-footer"></div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection