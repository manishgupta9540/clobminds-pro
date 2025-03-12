@php
    $EDIT_ACCESS   = false;
    $VIEW_PROFILE_ACCESS = false;
    $VIEW_ACCESS = false;
    $EDIT_ACCESS   = Helper::can_access('Edit Vendors','');
    $VIEW_PROFILE_ACCESS = Helper::can_access('View Vendor profile','');
    $VIEW_ACCESS = Helper::can_access('View Vendors List','');
    // $REPORT_ACCESS   = false;
    // $VIEW_ACCESS   = false;
@endphp 
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            @if ($VIEW_ACCESS)
                <table class="table table-bordered vendorTable">
                    <thead>
                        <tr>
                            {{-- <th scope="col">#</th> --}}
                            <th scope="col">Name </th>
                            <th scope="col">Email ID</th>
                            <th scope="col">Phone Number</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="vendorList">
                        @if( count($vendor) > 0 )
                            @foreach($vendor as $item)
                                <tr>
                                    {{-- <th scope="row">{{ $item->id }}</th> --}}
                                    <td>{{ucwords(strtolower($item->name)) }} <br>
                                        <small class="text-muted">Vendor: <b>{{$item->company_name!=null?$item->company_name:'Individual' }}</b></small> <br>
                                        <small class="text-muted">Vendor ID: <b>{{$item->display_id }}</b></small>
                                    </td>
                                    <td>{{ $item->email }}</td>
                                    <td>+91-{{ $item->phone }}</td>
                                    <td>
                                        @if($item->status == '1')
                                            <span data-dc="{{base64_encode($item->id)}}" class="badge badge-danger d-none">Inactive</span>
                                            <span data-ac="{{base64_encode($item->id)}}" class="badge badge-success">Active</span>
                                        @else
                                            <span data-dc="{{base64_encode($item->id)}}" class="badge badge-danger">Inactive</span>
                                            <span data-ac="{{base64_encode($item->id)}}" class="badge badge-success d-none">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($EDIT_ACCESS)
                                            <a href="{{ route('/admin/vendor/edit',['id'=>base64_encode($item->id)]) }}">
                                                <button class="btn btn-info" type="button"><i class='fa fa-edit'></i> Edit</button>
                                            </a>
                                        @endif
                                        @if ($VIEW_PROFILE_ACCESS)
                                            <a href="{{ route('/admin/vendor/profile',['id'=>base64_encode($item->id)]) }}">
                                                <button class="btn btn-success" type="button"><i class='fa fa-eye'></i> View</button>
                                            </a> 
                                        @endif

                                        @if($item->status==1)
                                            <span data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-dark status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('disable')}}" data-name="{{$item->name}}" title="Deactivate"><i class="far fa-times-circle"></i> Deactivate</a></span>
                                            <span data-a="{{base64_encode($item->id)}}" class="d-none"><a href="javascript:;" class="btn btn-success status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('enable')}}" data-name="{{$item->name}}" title="Active"><i class="far fa-check-circle"></i> Activate</a></span>
                                        @else
                                            <span class="d-none" data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-dark status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('disable')}}" data-name="{{$item->name}}" title="Deactivate"><i class="far fa-times-circle"></i> Deactivate</a></span>
                                            <span data-a="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-success status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('enable')}}" data-name="{{$item->name}}"  title="Active"><i class="far fa-check-circle"></i> Activate</a></span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td scope="row" colspan="6"><h3 class="text-center">No record!</h3></td>
                            </tr>
                        @endif
                    
                    </tbody>
                </table>
            @else
                <span><h3 class="text-center">You have no access to View Vendor lists</h3></span>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info" role="status" aria-live="polite"></div>
    </div>
    <div class="col-sm-12 col-md-7">
      <div class=" paging_simple_numbers" >            
          {!! $vendor->render() !!}
      </div>
    </div>
 </div>