@php
use App\Http\Helpers\SidebarHelper;
@endphp
<div class="vertical-menu">
    <div data-simplebar="" class="h-100">
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                @if(isset($sidebarItems))
                @foreach($sidebarItems as $key => $value)
                    @php
                        echo SidebarHelper::sidebar($key, $value);
                    @endphp
                @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>