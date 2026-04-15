<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Quản trị hệ thống</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="BullTheme" name="author">
	<!-- <link rel="shortcut icon" href="favicon.ico"> -->
	<link rel="shortcut icon" href="//ssl.gstatic.com/docs/spreadsheets/spreadsheets_2023q4.ico">

	<link rel="stylesheet" href="{{ asset('assets/admin/css/all.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/admin/css/app.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/admin/css/jstree.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendor/chosen/chosen.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/admin/css/toast.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/admin/css/jquery-confirm.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-datepicker.min.css') }}">

	<link rel="stylesheet" href="{{ asset('assets/admin/css/styles.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/admin/css/custom.css') }}">
	@yield('style')
</head>

<body data-sidebar="dark">
	<div class="main_loadding"></div>
	<div id="layout-wrapper">
		@include('admin.layouts.navbar')
		@include('admin.layouts.sidebar')
		<div class="main-content">
			@yield('content')
			@include('admin.layouts.footer')
		</div>
	</div>
	<div class="rightbar-overlay"></div>


	<script src="{{ asset('assets/admin/js/jquery.min.js') }}"></script>
	<script src="{{ asset('assets/admin/js/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('assets/admin/js/jstree.min.js') }}"></script>
	<script src="{{ asset('assets/vendor/chosen/chosen.min.js') }}"></script>
	<script src="{{ asset('assets/admin/js/toast.min.js') }}"></script>
	<script src="{{ asset('assets/admin/js/jquery-confirm.min.js') }}"></script>
	<script src="{{ asset('assets/admin/js/bootstrap-datepicker.min.js') }}"></script>
	<script src="{{ asset('assets/admin/js/Library.js') }}"></script>
	<script src="{{ asset('assets/admin/js/metisMenu.min.js') }}"></script>
	<script src="{{ asset('assets/admin/js/simplebar.min.js') }}"></script>
	<script src="{{ asset('assets/admin/js/waves.min.js') }}"></script>
	<script src="{{ asset('assets/admin/js/apexcharts.min.js') }}"></script>
	<script src="{{ asset('assets/admin/js/main.min.js') }}"></script>

	<script>
		$('.chzn-select').chosen({
			height: '100%',
			width: '100%'
		});
		$("#change-language").on('change', function() {
			$.ajax({
				url: "{{ url('') }}" + '/global/change-language/' + $("#change-language").val(),
				type: 'GET',
				success: function(result) {
					location.reload();
				}
			});
		});
	</script>
	<script>
		let msg_success = 'Thành công';
		let msg_warning = 'Cảnh báo';
		let msg_error = 'Lỗi';
		let msg_del = 'Xóa';
		let msg_accept = 'Xác nhận';
		let msg_confirm_delete = 'Bạn có chắc chắn muốn xóa bản ghi đã chọn bản ghi này không?';
		let msg_confirm_order = 'Bạn có chắc chắn muốn cập nhật lại tất cả các số thứ tự không?';
		let msg_close = 'Đóng';
	</script>
	@yield('script')
</body>

</html>
