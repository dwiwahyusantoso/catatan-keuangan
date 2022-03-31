@extends('layouts.app')

@section('content')

<div class="row mt-3">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                Riwayat tabungan {{ $saving_name }}
                <a href="/dashboard"><button class="btn btn-info btn-sm">Back to Home</button></a>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Jenis</th>
                            <th scope="col">Nominal</th>
                            <th scope="col">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; ?>
                        @foreach ($transactions as $transaction)
                        <tr>
                            <th scope="row">{{ $no++ }}</th>
                            <td>{{ $transaction->date }}</td>
                            <td>{{ $transaction->kategori }}</td>
                            <td>{{ $transaction->jenis_transaksi }}</td>
                            @if ( $transaction->jenis_transaksi == "keluar")
                                <td style="color:red">Rp {{ $transaction->nominal }},-</td>
                            @else
                                <td>Rp {{ $transaction->nominal }},-</td>
                            @endif
                            <td>{{ $transaction->description }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
            </div>
        </div>
    </div>
</div>

@endsection
