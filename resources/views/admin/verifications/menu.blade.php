<ul class="nav nav-tabs nav-tabs-bottom">
    {{-- <li class="nav-item"><a href="{{ route('/idChecks') }}" class="nav-link @if(Request::segment(1)=='idChecks') active @endif">Instant ID Checks</a></li> --}}
    <li class="nav-item"><a href="{{ route('/idChecks') }}" class="nav-link @if(Request::segment(1)=='idChecks') active @endif">Instant ID Checks</a></li>
    <li class="nav-item"><a href="{{ route('/bulkVerifications') }}" class="nav-link @if(Request::segment(1)=='bulkVerifications') active @endif">Instant Bulk Verifications</a></li>
    <li class="nav-item"><a href="{{ route('/verifications') }}" class="nav-link @if(Request::segment(1)=='verifications') active @endif">Manual Verifications</a></li>
    <li class="nav-item"><a href="{{ route('/bulk/criminal') }}" class="nav-link @if(Request::segment(2)=='criminal') active @endif">Bulk Criminal Verifications</a></li>
</ul>