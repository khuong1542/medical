@extends('admin.index')

@section('script')
<script src="{{ asset('assets/admin/js/pages/doctor.js') }}"></script>
<script>
	let baseUrl = "{{ url('') }}";
	let classJS = new JS_Doctor(baseUrl, 'admin', 'doctors');
	jQuery(document).ready(($) => classJS.loadIndex());
</script>
@endsection

@section('content')
<div class="page-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="page-title-box d-sm-flex align-items-center justify-content-between">
					<h4 class="mb-sm-0">@lang('admin/messages.sidebar.doctor')</h4>
					<div class="page-title-right">
						@include('admin.layouts.button.form', ['order' => true, 'add' => ['url' => route('doctors.create')], 'edit' => true, 'delete' => true])
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<form id="frm_index">
						<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
						<div class="card-header">
							<div class="row">
								<div class="col-md-6">
									{!! $categories ?? '' !!}
								</div>
								<div class="col-md-6">
									@include('admin.layouts.search.button')
								</div>
							</div>
						</div>
						<div class="card-body">
							<div id="table-container"></div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-focus="false"></div>
@endsection
