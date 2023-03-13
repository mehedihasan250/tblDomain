@extends('layouts.app')
@push('custom-scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<link href="{{ asset('toaster/css/toastr.min.css') }}" rel="stylesheet">
<script src="{{ asset('toaster/js/toastr.min.js') }}"></script>

<style>
    .profile-card {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    max-width: 300px;
    margin: auto;
    text-align: center;
    font-family: arial;
    }

    .profile-card .title {
    color: grey;
    font-size: 18px;
    }

    .profile-card button {
    border: none;
    outline: 0;
    display: inline-block;
    padding: 8px;
    color: white;
    background-color: #000;
    text-align: center;
    cursor: pointer;
    width: 100%;
    font-size: 18px;
    }

    .profile-card a {
    text-decoration: none;
    font-size: 22px;
    color: black;
    }

    .profile-card button:hover, a:hover {
    opacity: 0.7;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="profile-card pt-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
        </svg>
        <h3>{{Auth::user()->name}}</h3>
        <p class="title">{{Auth::user()->email}}</p>
        <p><button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop" >Change Password</button></p>
    </div>
</div>

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Change Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="password_form">
                @csrf
                <div class="mb-3 form-group d-none">
                  <label class="form-label"><b>Old password </b><span class="text-danger">*</span></label>
                  <input class="form-control" type="password" name="old_password" id="old_password" >
                </div>
                <div class="mb-3 form-group">
                    <label class="form-label"><b>New password </b><span class="text-danger">*</span></label>
                    <input class="form-control" type="password" name="new_password" id="new_password" minlength="6" required>
                </div>

                <div class="mb-3 form-group">
                    <label class="form-label"><b>Confirm Password </b><span class="text-danger">*</span></label>
                    <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" minlength="6" required>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
        {{-- <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Understood</button>
        </div> --}}
        </div>
    </div>
</div>
<script>

    $(document).ready(function(){
        // var _token = $('meta[name="csrf-token"]').attr('content');
        // console.log(_token);
        $.toastr.config({
            time: 4000,
        });

        var modal = '#staticBackdrop';
        var form = '#password_form';
        $(form).on('submit', function(event){
            event.preventDefault();

            let password = $('#new_password').val();
            let password2 = $('#password_confirmation').val();

            if(password != password2){
                $.toastr.error('The new password confirmation does not match.');
                return;
            }else{
                $.ajax({
                type:'POST',
                url:'{{route('password-reset')}}',
                data:$(form).serialize(),
                success:function(data) {
                    if(data.status=='success'){
                        $(form).trigger("reset");
                        $(modal).modal('hide');
                        $.toastr.success(data.html);
                    }else if(data.status=='validation-error'){
                        Object.keys(data.html).forEach(key => {
                            $.toastr.error(data.html[key]);
                        })
                    }else{
                        $.toastr.error(data.html);
                    }
                }
                });    
            }

            
        });

    });
</script>

@endsection
