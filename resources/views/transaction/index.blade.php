@if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show py-1 text-middle" role="alert">
        <strong>{{ session('success') }}</strong>
        <button type="button" class="btn-close pt-0" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show py-1 text-middle" role="alert">
        <strong>{{ session('error') }}</strong>
        <button type="button" class="btn-close pt-0" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<table class="table bg-success table-hover table-striped table-responsive-lg">
<thead>
    <tr>
        <th scope="col">No</th>
        <th scope="col">Tanggal</th>
        <th scope="col">Kategori</th>
        <th scope="col">Jenis</th>
        <th scope="col">Nominal</th>
        <th scope="col">Deskripsi</th>
        <th scope="col">Aksi</th>
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
        <td>
            <a href="{{ route('edit', ['id' => $transaction->id])}}"><button class="btn btn-info btn-sm px-1 py-0"><i class="bi bi-pencil-square"></i></button></a>
            <a href="{{ route('delete', ['id' => $transaction->id])}}"><button class="btn btn-danger btn-sm px-1 py-0"><i class="bi bi-trash"></i></button></a>
        </td>
    </tr>
    @endforeach
</tbody>
</table>
