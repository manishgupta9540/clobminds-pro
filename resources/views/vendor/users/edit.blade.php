@extends('layouts.vendor')
@section('content')

<div class="main-content-wrap sidenav-open d-flex flex-column">
<div class="main-content"> 
    <!-- ============Breadcrumb ============= -->
    <div class="row">
      <div class="col-sm-11">
          <ul class="breadcrumb">
          <li>
          <a href="{{ url('/my/home') }}">Dashboard</a>
          </li>
          <li><a href="{{ url('/my/users') }}">User</a></li>
          <li>Edit</li>
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

<div class="card text-left">
   <div class="card-body" >

<div class="row">
   <div class="col-lg-12 margin-tb">
      <div class="pull-left">
         <h2>Edit user info</h2>
      </div>
      <div class="pull-right"> </div>
   </div>
</div>
@if (count($errors) > 0)
<div class="alert alert-danger">
   <strong>Whoops!</strong> There were some problems with your input.<br><br>
   <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
   </ul>
</div>
@endif
<form class="mt-2" method="post" id="addUserForm" action="{{ url('vendor/users/update',$user->id) }}">
   @csrf

<div class="row">
   <div class="col-xs-12 col-sm-8 col-md-8 offset-md-2">
    <div class="form-group">
        <label for="first_name">First Name <span class="text-danger">*</span></label>
        <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter first name" value="{{ $user->first_name }}">
        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-first_name"></p>
    </div>   
   </div>
   <div class="col-xs-12 col-sm-8 col-md-8 offset-md-2">
    <div class="form-group">
        <label for="last_name">Last Name <span class="text-danger">*</span></label>
        <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Enter last name" value="{{$user->last_name}}">
        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-last_name"></p>
    </div>   </div>
   <div class="col-xs-12 col-sm-8 col-md-8 offset-md-2">
    <div class="form-group">
        <label for="phone">Phone <span class="text-danger">*</span></label>
        <input type="hidden" id="code" name ="primary_phone_code" value="{{$user->phone_code}}" >
        <input type="hidden" id="iso" name ="primary_phone_iso" value="{{$user->phone_iso}}" >
        <input type="text" name="phone" class="form-control number_only" id="phone1" value="{{$user->phone}}">
        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-phone"></p>
    </div>   </div>
   <div class="col-xs-12 col-sm-8 col-md-8 offset-md-2">
    <div class="form-group">
        <label for="email">Email <span class="text-danger">*</span></label>
        <input type="text" name="email" class="form-control" id="email" placeholder="Enter Email Id" value="{{$user->email }}" readonly>
        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-email"></p>
    </div>
   </div>
   {{-- <div class="col-xs-12 col-sm-8 col-md-8 offset-md-2">
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" class="form-control" id="password" placeholder="Enter password"   value="{{ old('password') }}">
        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-password"></p>  
    </div>
   </div>
   <div class="col-xs-12 col-sm-8 col-md-8 offset-md-2">
    <div class="form-group">
        <label for="confirm-password">Confirm password</label>
        <input type="password" name="confirm-password" class="form-control" id="confirm-password" placeholder="Enter confirm password"  value="{{ old('password') }}">
        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-password"></p>  
    </div>
   </div>
   --}}
      {{-- <div class="col-xs-12 col-sm-8 col-md-8 offset-md-2"> --}}
         {{-- <div class="form-group">
            <strong>Select Services:</strong> <span class="text-danger">*</span>
          
             <div class="col-sm-12">
            <div class="form-group">
            @foreach($services as $service)
              <div class="form-check form-check-inline">
               <input class="form-check-input services_list" type="radio" name="services[]" value="{{ $service->id}}" id="inlineCheckbox-{{ $service->id}}" >
               <label class="form-check-label" for="inlineCheckbox-{{ $service->id}}">{{ $service->name  }}</label>
              </div>
            @endforeach
            </div>
         </div>
         <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>
         </div>
      </div> --}}

    
   
   {{-- <div class="col-xs-12 col-sm-8 col-md-8 offset-md-2">
      <div class="form-group"><strong>Role:</strong>{!! Form::select('roles[]', $roles,[], array('class' => 'form-control','multiple')) !!}</div>
   </div> --}}
   <div class="col-xs-12 col-sm-12 col-md-12 text-center"><button type="submit" class="btn btn-success">Update</button></div>
</div>
</form>
</div>
</div>
</div>
</div>
@endsection
