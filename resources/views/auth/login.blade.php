@extends('layouts.app')

@section('tittle')
    Login
@endsection

@section('content')

<div class="row mt-3">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-header bg-success">
                Login
            </div>
            <div class="card-body bg-dark">
                <div class="bg-secondary col-md-10 offset-md-1 py-2 px-2">
                    @if (session()->has('errorlogin'))
                    <div class="alert alert-danger alert-dismissible fade show py-0" role="alert">
                        <strong>{{ session('errorlogin') }}</strong>
                        <button type="button" class="btn-close pt-0" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if (session()->has('success-registration'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session('success-registration') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <form method="post" action="{{ route('login') }}">
                            @csrf
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}">
                            @error('username')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-block enter-btn">Login</button>
                        </div>

                        <p class="sign-up text-center">Don't have an Account?<a href="{{ route('register') }}" style="color: black; text-decoration: none"> Registration</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

