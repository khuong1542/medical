<style>
    .button-group{
        display: flex;
    }
    @media (max-width: 380px){
        .button-group{
            display: grid;
        }
    }
</style>
<div class="col-sm-auto button-group">
    @if(isset($order) && $order) @include('admin.layouts.index.order') @endif
    @if(isset($add)) @include('admin.layouts.index.add', $add) @endif
    @if(isset($delete)) @include('admin.layouts.index.delete') @endif
</div>