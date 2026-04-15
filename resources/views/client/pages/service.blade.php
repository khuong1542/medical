@extends('client.index')

@section('content')
<div class="container-fluid page-header py-5 mb-5 wow fadeIn">
	<div class="container py-5">
		<h1 class="display-3 text-white mb-3  slideInDown">Services</h1>
		<nav aria-label="breadcrumb animated slideInDown">
			<ol class="breadcrumb text-uppercase mb-0">
				<li class="breadcrumb-item"><a class="text-white" href="#">Home</a></li>
				<li class="breadcrumb-item"><a class="text-white" href="#">Pages</a></li>
				<li class="breadcrumb-item text-primary active" aria-current="page">Services</li>
			</ol>
		</nav>
	</div>
</div>

<div class="container my-4">
	<div class="text-center mx-auto mb-5 wow fadeInUp">
		<p class="d-inline-block border rounded-pill py-1 px-4">Services</p>
		<h1>Our Experience Services</h1>
	</div>
	<div class="row services">
		<div class="col-xl-3 col-md-4 col-sm-6 col-12">
			<div class="service-item  position-relative">
				<div class="icon">
					<i class="fas fa-heartbeat"></i>
				</div>
				<a href="#" class="stretched-link">
					<h3>Nesciunt Mete</h3>
				</a>
				<p>Provident nihil minus qui consequatur non omnis maiores. Eos accusantium minus dolores iure perferendis tempore et consequatur.</p>
			</div>
		</div>
		<div class="col-xl-3 col-md-4 col-sm-6 col-12">
			<div class="service-item  position-relative">
				<div class="icon">
					<i class="fas fa-heartbeat"></i>
				</div>
				<a href="#" class="stretched-link">
					<h3>Nesciunt Mete</h3>
				</a>
				<p>Provident nihil minus qui consequatur non omnis maiores. Eos accusantium minus dolores iure perferendis tempore et consequatur.</p>
			</div>
		</div>
		<div class="col-xl-3 col-md-4 col-sm-6 col-12">
			<div class="service-item  position-relative">
				<div class="icon">
					<i class="fas fa-heartbeat"></i>
				</div>
				<a href="#" class="stretched-link">
					<h3>Nesciunt Mete</h3>
				</a>
				<p>Provident nihil minus qui consequatur non omnis maiores. Eos accusantium minus dolores iure perferendis tempore et consequatur.</p>
			</div>
		</div>
		<div class="col-xl-3 col-md-4 col-sm-6 col-12">
			<div class="service-item  position-relative">
				<div class="icon">
					<i class="fas fa-heartbeat"></i>
				</div>
				<a href="#" class="stretched-link">
					<h3>Nesciunt Mete</h3>
				</a>
				<p>Provident nihil minus qui consequatur non omnis maiores. Eos accusantium minus dolores iure perferendis tempore et consequatur.</p>
			</div>
		</div>
	</div>
</div>
@endsection
