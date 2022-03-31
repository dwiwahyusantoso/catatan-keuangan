@extends('layouts.app')

@section('content')

<div class="row mt-3">
    <div class="col-md-8 offset-md-2 mb-3">
        <div class="card">
            <div class="card-header bg-success">
                Edit Transaksi
            </div>
            <div class="card-body bg-secondary">
                <div>
                    <form action="{{ route('update', ['id' => $record->id ])}}" method="post">
                    @csrf
                        @if (!$record->saving_id)
                            <div class="mb-3">
                                <label for="kategori" class="form-label mb-0">Kategori Transaksi</label>
                                <input class="form-control form-control-sm mt-0" list="datalist" id="kategori" name="kategori" value={{ $record->kategori }}>
                                <datalist id="datalist">
                                    @foreach ($transactions as $transaction)
                                    <option value="{{ $transaction->kategori }}">
                                    @endforeach
                                </datalist>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label for="jenis_transaksi" class="form-label mb-0">Jenis Trsansaksi</label>
                            <select class="form-select form-select-sm mt-0" id="jenis_transaksi" name="jenis_transaksi">
                                <option value="{{ $record->jenis_transaksi }}">Pilih jenis transaksi</option>
                                <option value="masuk">pemasukan</option>
                                <option value="keluar">pengeluaran</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nominal" class="form-label mb-0">Nominal</label>
                            <input type="text" class="form-control mt-0" id="nominal" name="nominal" value="{{$record->nominal}}">
                        </div>
                        @if (!$record->saving_id)
                        <div class="mb-3">
                            <label for="description" class="form-label mb-0">Deskripsi</label>
                            <textarea class="form-control mt-0" id="description" name="description" value="{{$record->description}}"></textarea>
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
