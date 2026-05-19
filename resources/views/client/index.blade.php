<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Index - Medilab</title>
	@include('client.layouts.style')
</head>
<body>
	@include('client.layouts.header')

	<main class="main">
		@yield('content')
	</main>

	@include('client.layouts.footer')<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

	@include('client.layouts.script')
	@yield('script')
</body>
</html>
