<div class="form-search">
    <div class="input-group">
        <input type="text" class="form-control" name="keyword" id="keyword" autocomplete="off" onkeydown="if (event.key == 'Enter'){search();return false;}" placeholder="@lang('admin/shared.placeholder-search')">
        <span class="input-group-btn">
            <button type="button" class="btn btn-primary" id="btn_search">@lang('admin/shared.search')</button>
        </span>
    </div>
</div>