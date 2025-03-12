<ul class="nav nav-tabs pt-3" id="myIconTab" role="tablist">
    <li class="nav-item"><a class="nav-link @if(Request::segment(3)=='') active @endif"   href="{{url('/candidates/create')}}" > Default</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(4)=='create') active @endif" href="{{ url('/candidates/bulk/sla/create') }}">Bulk Uploads</a></li>
</ul>