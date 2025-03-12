@extends('layouts.verify')
@section('content')
    <section class="welcome p-4">
        <div class="heading pt-2">
            <h2 class="font-weight-bold">Welcome</h2>
        </div>
        <div class="small-text">
            <p>Take a minutes to digitally verify your address</p>
        </div>
        <button type="button" class="btn desc-btn">ENGLISH</button>
        <div class="main-content pt-5">
            <h5>Terms & conditions</h5>
            <p class="para paragraph text-justify pb-3">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type </p>
            <div class="action-perform align-items-center d-flex pb-3 pt-3">
                <a class="align-items-center btn btn-light d-flex justify-content-center mr-3">Decline</a>
                <a class="theme-btn theme-btn-linear d-flex align-items-center justify-content-center" href="{{route('candidate-login')}}">Agree</a>
            </div>
        </div>
    </section>
@endsection