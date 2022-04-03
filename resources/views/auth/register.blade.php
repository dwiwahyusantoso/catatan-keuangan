@extends('layouts.app')

@section('tittle')
    Register
@endsection

@section('content')

<div class="row mt-3">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-header bg-success">
                Register
            </div>
            <div class="card-body bg-dark">
                <div class="bg-secondary col-md-10 offset-md-1 py-2 px-2">
                    <form method="post" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group">
                            <label>First name</label>
                            <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror" value="{{ old('firstname') }}">
                            @error('firstname')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Lastname</label>
                            <input type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror" value="{{ old('lastname') }}">
                            @error('lastname')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
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
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-block enter-btn">Register</button>
                        </div>

                        <p class="sign-up text-center">Already have an Account?<a href="{{ route('login') }}" style="color: black; text-decoration: none"> Login </a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

