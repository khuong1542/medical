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
    @if(isset($order) && $order) @include('admin.layouts.button.order') @endif
    @if(isset($add)) @include('admin.layouts.button.add', $add) @endif
    @if(isset($delete)) @include('admin.layouts.button.delete') @endif
</div>