@extends('layouts.app')

@section('tittle')
    Management
@endsection

@section('content')

<div class="row mt-3">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-header bg-success">
                <div class="mt-2 mb-3"><mark class="rounded py-2 px-2">Saldo Utama: Rp {{ $saldo->saldo ?? "" }},-</mark></div>
                @if (isset($savings))
                @foreach ($savings as $saving)
                <div class="row">
                    <div class="col-md-4">Uang Tabungan {{$saving->saving_name ?? ""}}: Rp {{$saving->saldo ?? ""}},-</div>
                    <div class="col-md-4"><a style="text-decoration: none" href="{{ route('show', ['id' => $saving->id, 'saving_name' => $saving->saving_name])}}"><button class="rounded btn btn-sm btn-info px-1 py-0">Riwayat tabungan {{ $saving->saving_name }}</button></a></div>
                </div>
                @endforeach
                @endif
            </div>
            <div class="card-body bg-dark">
            @include('transaction.create-transaction')
            @include('transaction.index')
            </div>
        </div>
    </div>
</div>
@endsection
