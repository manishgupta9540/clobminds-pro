<ul class="nav nav-tabs pt-3" id="myIconTab" role="tablist">
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='') active @endif" href="{{url('/candidates')}}" > WIP</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='completed') active @endif" href="{{url('/candidates/completed')}}" > Completed</a></li>
    <li class="nav-item"><a class="nav-link @if(Request::segment(2)=='caseclosed') active @endif" href="{{url('/candidates/caseclosed')}}" > Aborted</a></li>
</ul>