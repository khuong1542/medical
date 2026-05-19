@extends('client.index')

@section('content')

@include('client.layouts.breadcrumb')

<div class="container my-4">
	<div class="text-center mx-auto mb-5 wow fadeInUp">
		<p class="d-inline-block border rounded-pill py-1 px-4">Doctors</p>
		<h1>Our Experience Doctors</h1>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="doctor-search">
				<h3 class="search-filter-title">Search filters</h3>
				<div class="form-search">
					<fieldset class="filter-group input-group">
						<input type="text" class="form-control" name="keyword" id="keyword" autocomplete="off" onkeydown="if (event.key == 'Enter'){search();return false;}" placeholder="Search...">
						<span class="input-group-btn">
							<button type="button" class="btn btn-primary" id="btn_search"><i class="fa-solid fa-search"></i></button>
						</span>
					</fieldset>
					<fieldset class="filter-group">
						<legend>Healthcare Facility</legend>
						<div class="checkbox-filter">
							<label for="">
								<input type="checkbox">
								<span>Hopital</span>
							</label>
						</div>
						<div class="checkbox-filter">
							<label for="">
								<input type="checkbox">
								<span>Facility</span>
							</label>
						</div>
					</fieldset>
					<fieldset class="filter-group">
						<button class="btn btn-danger">Clear</button>
					</fieldset>
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="doctors">
				<div class="row gy-4">
					@if(isset($datas) && count($datas) > 0)
						@foreach($datas as $doctor)
							@php
								$images = json_decode(($doctor->images ?? ''), true);
							@endphp
							<div class="col-lg-6 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
								<div class="team-member d-flex align-items-start">
									<div class="pic">
										<a href="{{ route('client.doctors.detail', ['code' => $doctor->code]) }}">
											<img class="img-fluid" src="{{ $images['url'] ?? '' }}" alt="{{ $images['name'] ?? '' }}">
										</a>
									</div>
									<div class="member-info">
										<a href="{{ route('client.doctors.detail', ['code' => $doctor->code]) }}">
											<h4>{{ $doctor->name ?? '' }}</h4>
										</a>
										<span>{{ $doctor->specialty?->name ?? '' }}</span>
										<p>{{ $doctor->description ?? '' }}</p>
										<div class="mt-3">
											<button class="btn btn-primary">Book Now</button>
										</div>
									</div>
								</div>
							</div>
						@endforeach
					@endif

					<div class="col-lg-6 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
						<div class="team-member d-flex align-items-start">
							<div class="pic"><img src="assets/client/img/doctors/doctors-1.jpg" class="img-fluid" alt=""></div>
							<div class="member-info">
								<h4>Walter White</h4>
								<span>Chief Medical Officer</span>
								<p>Explicabo voluptatem mollitia et repellat qui dolorum quasi</p>
								<div class="mt-3">
									<button class="btn btn-primary">Book Now</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6 aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
						<div class="team-member d-flex align-items-start">
							<div class="pic"><img src="assets/client/img/doctors/doctors-2.jpg" class="img-fluid" alt=""></div>
							<div class="member-info">
								<h4>Sarah Jhonson</h4>
								<span>Anesthesiologist</span>
								<p>Aut maiores voluptates amet et quis praesentium qui senda para</p>
								<div class="mt-3">
									<button class="btn btn-primary">Book Now</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6 aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">
						<div class="team-member d-flex align-items-start">
							<div class="pic"><img src="assets/client/img/doctors/doctors-3.jpg" class="img-fluid" alt=""></div>
							<div class="member-info">
								<h4>William Anderson</h4>
								<span>Cardiology</span>
								<p>Quisquam facilis cum velit laborum corrupti fuga rerum quia</p>
								<div class="mt-3">
									<button class="btn btn-primary">Book Now</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6 aos-init aos-animate" data-aos="fade-up" data-aos-delay="400">
						<div class="team-member d-flex align-items-start">
							<div class="pic"><img src="assets/client/img/doctors/doctors-4.jpg" class="img-fluid" alt=""></div>
							<div class="member-info">
								<h4>Amanda Jepson</h4>
								<span>Neurosurgeon</span>
								<p>Dolorum tempora officiis odit laborum officiis et et accusamus</p>
								<div class="mt-3">
									<button class="btn btn-primary">Book Now</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection