@extends('layouts.app')

<div class="col-md-10 offset-md-1">
<form method="post" action="{{ route('register') }}">
    @csrf
    <div class="form-group">
        <label>First name</label>
        <input type="text" name="firstname" class="form-control">
    </div>
    <div class="form-group">
        <label>Lastname</label>
        <input type="text" name="lastname" class="form-control">
    </div>
    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" class="form-control">
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control">
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary btn-block enter-btn">Register</button>
    </div>

    <p class="sign-up text-center">Already have an Account?<a href="{{ route('login') }}"> Sign In</a></p>
</form>
</div>
