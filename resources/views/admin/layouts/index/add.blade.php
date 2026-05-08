@if (isset($add['url']))
<a id="btn_add" href="{{ $add['url'] }}" class="btn btn-primary d-flex align-items-center ms-1">
	<i class="fa-solid fa-plus"></i><span class="ms-1">@lang('admin/shared.add', ['name' => ''])</span>
</a>
@else
<button id="btn_add" class="btn btn-primary d-flex align-items-center ms-1">
	<i class="fa-solid fa-plus"></i><span class="ms-1">@lang('admin/shared.add', ['name' => ''])</span>
</button>
@endif