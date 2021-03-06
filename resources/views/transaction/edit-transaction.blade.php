@extends('layouts.app')

@section('tittle')
    Edit
@endsection

@section('content')

<div class="row mt-3">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-header bg-success">
                @include('layouts.header')
            </div>
            <div class="card-body bg-dark">
                <div class="text-left col-md-2 offset-md-1">
                    <mark class="bg-info px-2">Edit Transaksi</mark>
                </div>
                <div class="bg-secondary col-md-10 offset-md-1 py-2 px-2">
                    <form action="{{ route('update', ['id' => $record->id ])}}" method="post">
                    @csrf

                        @if (!$record->saving_id)
                            <div class="mb-3">
                                <label for="kategori" class="form-label mb-0">Kategori Transaksi</label>
                                <input class="form-control form-control-sm mt-0 @error('kategori') is-invalid @enderror" list="datalist" id="kategori" name="kategori" value={{ $record->kategori }}>
                                <datalist id="datalist">
                                    @foreach ($transactions as $transaction)
                                    <option value="{{ $transaction->kategori }}">
                                    @endforeach
                                </datalist>
                                @error('kategori')
                                    <div class="invalid-feedback bg-dark">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="jenis_transaksi" class="form-label mb-0">Jenis Trsansaksi</label>
                            <select class="form-select form-select-sm mt-0 @error('jenis_transaksi') is-invalid @enderror" id="jenis_transaksi" name="jenis_transaksi">
                                <option value="{{ $record->jenis_transaksi }}">Pilih jenis transaksi</option>
                                <option value="masuk">pemasukan</option>
                                <option value="keluar">pengeluaran</option>
                            </select>
                            @error('jenis_transaksi')
                            <div class="invalid-feedback bg-dark">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nominal" class="form-label mb-0">Nominal</label>
                            <input type="text" class="form-control mt-0 @error('nominal') is-invalid @enderror" id="nominal" name="nominal" value="{{$record->nominal}}">
                            @error('nominal')
                            <div class="invalid-feedback bg-dark">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        @if (!$record->saving_id)
                            <div class="mb-3">
                                <label for="description" class="form-label mb-0">Deskripsi</label>
                                <textarea class="form-control mt-0" id="description" name="description">{{$record->description}}</textarea>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
