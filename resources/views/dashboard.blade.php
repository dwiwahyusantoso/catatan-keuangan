@extends('layouts.app')

@section('tittle')
    Management
@endsection

@section('content')

<div class="row mt-3">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-header bg-success">
                @include('layouts.header')
            </div>
            <div class="card-body bg-dark">
                @include('layouts.create-navigation')
                @include('transaction.index')
            </div>
        </div>
    </div>
</div>
@endsection
