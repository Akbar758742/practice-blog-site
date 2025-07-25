@extends('backend.layout.auth-layout')
@section('pageTitle', isset($title) ? $title : 'page title here')

@section('content')
    <div class="login-box bg-white box-shadow border-radius-10">
        <div class="login-title">
            <h2 class="text-center text-primary">Login</h2>
        </div>
        <form method="POST" action="{{ route('admin.loginHandler') }}">
            @csrf
            <x-form-alerts></x-form-alerts>

            <div class="input-group custom mb-1">
                <input type="text" name="login_id" value="{{ old('login_id') }}" class="form-control form-control-lg" placeholder="Username">
                <div class="input-group-append custom">
                    <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                </div>
            </div>
            @error('login_id')
            <span class="text-danger ml-1" role="alert">
                {{ $message }}
            </span>
            @enderror
            <div class="input-group custom mb-1 mt-2">
                <input type="password" name="password" class="form-control form-control-lg" placeholder="**********">
                <div class="input-group-append custom">
                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                </div>
            </div>
            @error('password')
            <span class="text-danger ml-1" role="alert">
               {{ $message }}
                </span>
            @enderror
            <div class="row pb-30">
                <div class="col-6">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                        <label class="custom-control-label" for="customCheck1">Remember</label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="forgot-password">
                        <a href="{{ route('admin.forgetPassword') }}">Forgot Password</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="input-group mb-0">
                        <!--
               use code for form submit
               <input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In">
              -->
                        <button class="btn btn-primary btn-lg btn-block" >Sign In</button>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection
