<table id="table-data" class="table table-lg table-borderless table-thead-bordered table-bordered table-striped">
	<colgroup>
		<col width="5%">
		<col width="20%">
		<col width="20%">
		<col width="15%">
		<col width="15%">
		<col width="10%">
		<col width="10%">
		<col width="5%">
	</colgroup>
	<thead>
		<tr>
			<th><input type="checkbox" name="chk_all_item_id" onclick="checkbox_all_item_id(document.forms[0].chk_item_id);"></th>
			<th>@lang('admin/shared.name')</th>
			<th>@lang('admin/messages.doctor.facilities')</th>
			<th>@lang('admin/messages.doctor.specialty')</th>
			<th>@lang('admin/shared.image')</th>
			<th>@lang('admin/shared.order')</th>
			<th>@lang('admin/shared.status')</th>
			<th>#</th>
		</tr>
	</thead>
	<tbody id="data_body">
		@if(isset($datas) && count($datas) > 0)
		@foreach($datas as $key => $data)
		@php
			$id = $data->id;
			$images = json_decode(($data->images ?? ''), true);
		@endphp
		<tr id="tr_row_{{ $key }}">
			<td align="center" class="align-middle"><input type="checkbox" ondblclick="" onclick="{select_checkbox_row(this);}" name="chk_item_id" value="{{$id}}"></td>
			<td class="align-middle text-break">{{ $data->name ?? '' }}</td>
			<td class="align-middle text-break">{{ $data->facilities?->name ?? '' }}</td>
			<td class="align-middle text-break">{{ $data->specialty?->name ?? '' }}</td>
			<td align="center" class="align-middle">
				@if(isset($images))
				<img src="{{ $images['url'] ?? '' }}" alt="{{ $images['name'] }}" width="100px">
				@endif
			</td>
			<td align="center" class="align-middle">{{ $data->order ?? '' }}</td>
			<td align="center" class="align-middle">
				<label class="custom-control custom-checkbox p-0 m-0 pointer " style="cursor: pointer;">
					<input type="checkbox" hidden class="custom-control-input toggle-status" id="status_{{$id}}" data-id="{{$id}}" {{ $data->status == 1 ? 'checked' : '' }}>
					<span class="custom-control-indicator p-0 m-0" onclick="classJS.changeStatus('{{$id}}')"></span>
				</label>
			</td>
			<td align="center" class="align-middle">
				<a href="{{ route('doctors.edit', $id)}}" class="btn btn-warning btn-sm"><i class='fa-solid fa-edit'></i></a>
			</td>
		</tr>
		@endforeach
		@endif
	</tbody>
	<tfoot>
		@if(isset($datas) && count($datas) > 0)
		<tr>
			<td colspan="10">{{ $datas->links('admin.pagination.default') }}</td>
		</tr>
		@else
		<tr>
			<td align="center" colspan="10">Không tìm thấy dữ liệu!</td>
		</tr>
		@endif
	</tfoot>
</table>