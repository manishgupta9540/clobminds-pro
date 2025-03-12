@extends('layouts.vendor')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
       <!-- ============Breadcrumb ============= -->
   <div class="row">
      <div class="col-sm-11">
          <ul class="breadcrumb">
          <li>
          <a href="{{ url('/my/home') }}">Dashboard</a>
          </li>
          <li>User</li>
         
          </ul>
      </div>
      <!-- ============Back Button ============= -->
      <div class="col-sm-1 back-arrow">
          <div class="text-right">
          <a href="{{ url()->previous() }}"> <i class="fas fa-arrow-circle-left fa-2x"></i></a>
          </div>
      </div>
  </div>   
      <div class="row">
      <div class="col-md-12">
         <div class="card text-left">
            <div class="card-body">
               <div class="row">
               @if ($message = Session::get('success'))
               <div class="col-md-12">   
                  <div class="alert alert-success">
                  <strong>{{ $message }}</strong> 
                  </div>
               </div>
               @endif
                  <div class="col-md-8">
                     <h4 class="card-title mb-1"> Users </h4>
                     <p> List of all Users </p>
                  </div>
                  <div class="col-md-4">
                     <div class="btn-group" style="float:right">
                        <a class="btn btn-success " href="{{ url('vendor/users/create') }}" > <i class="fa fa-plus"></i> Add New </a>             
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12">
                     <div class="table-responsive">
                        <table class="table table-bordered userTable">
                           <thead>
                              <tr>
                                 {{-- <th scope="col">#</th> --}}
                                 <th scope="col">Name </th>
                                 <th scope="col">Contact </th>
                                 <th scope="col">Email</th>
                                 {{-- <th scope="col">Role</th> --}}
                                 <th scope="col" class="text-center">Status</th>
                                 <th scope="col">Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              @if(count($users) > 0)
                              @foreach ($users as $key => $user)
                              <tr>
                                 
                                 <td>{{ucwords(strtolower($user->name)) }}
                                    <br>
                                    <small class="text-muted">Ref. No. <b> {{($user->display_id)}}
                                 </td>
                                 <td>{{ $user->phone }}</td>
                                 <td>{{ $user->email }}</td>
                                 {{-- <td>{{ Helper::get_role_name($user->role) }}</td> --}}
                                 <td class="text-center">
                                    <input data-id="{{base64_encode($user->id)}}" class="toggle-class" name="status" type="checkbox" {{ $user->status ? 'checked' : '' }}>
                                    <br>
                                    @if ($user->is_blocked=='1')
                                       <span class="badge badge-danger mt-2 " data-users_id="{{base64_encode($user->id)}}" >
                                          Blocked
                                       </span>
                                
                                    @endif
                                 </td>
                                 <td>
                                    <a class="btn btn-sm btn-primary" href="{{url('vendor/users/edit',$user->id)}}"><i class="far fa-edit"></i> Edit</a>
                                    <button class="btn btn-sm btn-danger deleteBtn" data-user_id="{{base64_encode($user->id)}}"><i class="far fa-trash-alt"></i> Delete</button>
                                    @if ($user->is_blocked=='1')
                                        <a class="btn btn-sm btn-primary unblockBtn text-wh" data-user="{{base64_encode($user->id)}}"><i class="fas fa-unlock-alt"></i> Unblock</a>
                                     @endif
                                 </td>
                              </tr>
                              @endforeach
                              @else
                              <tr>
                                 <td scope="row" colspan="7">
                                    <h3 class="text-center">No record!</h3>
                                 </td>
                              </tr>
                              @endif
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   </div>
</div>

<script>
//    //when click on delete button
$(document).on('click', '.deleteBtn', function (event) {
    
    var user_id = $(this).attr('data-user_id');
   //  alert(user_id);
    if(confirm("Are you sure want to delete this user ?")){
    $.ajax({
        type:'GET',
        url: "{{ url('/vendor/')}}"+"/users/delete",
        data: {'user_id':user_id},        
        success: function (response) {        
        console.log(response);
        
            if (response.status=='ok') { 

               toastr.success("User Deleted Successfully");
               // window.setTimeout(function(){
               //    location.reload();
               // },2000);
               $('table.userTable tr').find("[data-user_id='" + user_id + "']").parent().parent().fadeOut("slow");
            } else {
               // toastr.error("Firstly, Complete or Assign Task to any other user ");
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            alert("Error: " + errorThrown);
        }
    });

    }
    return false;

});

//when click on Unblock button
$(document).on('click', '.unblockBtn', function (event) {
    
    var user = $(this).attr('data-user');
   //  alert(user_id);
    if(confirm("Are you sure want to Unblock this user ?")){
    $.ajax({
        type:'GET',
        url: "{{ url('/vendor/')}}"+"/user/unblock",
        data: {'user_id':user},        
        success: function (response) {        
        console.log(response);
        
            if (response.status=='ok') { 

               toastr.success("User Unblock Successfully");
               // window.setTimeout(function(){
               //    location.reload();
               // },2000);
               $('table.userTable tr').find("[data-user='" + user + "']").fadeOut("slow");
               $('table.userTable tr').find("[data-users_id='" + user + "']").fadeOut("slow");
            } else {
                
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            alert("Error: " + errorThrown);
        }
    });

    }
    return false;

});
$(document).on('click', '.toggle-class', function() {
       var status = $(this).prop('checked') ==  true ? 1 : 0;
       var id = $(this).data('id');
      //  alert(status);
        console.log(status);
       $.ajax({
           type: "GET",
           dataType: "json",
           url: '{{url('/vendor/user/status')}}',
           data: {'status': status, 'id': id},
           success: function(data){
             console.log(data.success)
           }
       });
});
</script>
@endsection
