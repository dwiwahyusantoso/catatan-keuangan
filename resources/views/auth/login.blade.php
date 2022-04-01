@extends('layouts.app')

<div class="col-md-10 offset-md-1">
<form method="post" action="{{ route('login') }}">
        @csrf
    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" class="form-control">
    </div>
    <div class="form-group">
        <label>Password *</label>
        <input type="password" name="password" class="form-control">
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary btn-block enter-btn">Login</button>
    </div>

    <p class="sign-up">Don't have an Account?<a href="{{ route('register') }}"> Sign Up</a></p>
</form>
</div>
