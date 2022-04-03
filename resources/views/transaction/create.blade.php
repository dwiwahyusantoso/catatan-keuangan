<form action="/transaction" method="post" class="ms-2 me-2">
    @csrf
    <div class="mb-3">
        <label for="kategori" class="form-label mb-0">Kategori Transaksi</label>
        <input class="form-control form-control-sm mt-0 @error('kategori') is-invalid @enderror" value="{{ old('kategori') }}" list="datalist" id="kategori" name="kategori" placeholder="Pilih Kategori Transaksi">
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
    <div class="mb-3">
        <label for="jenis_transaksi" class="form-label mb-0">Jenis Trsansaksi</label>
        <select class="form-select form-select-sm mt-0 @error('jenis_transaksi') is-invalid @enderror" value="{{ old('jenis_transaksi') }}" id="jenis_transaksi" name="jenis_transaksi">
            <option value="">Pilih jenis transaksi</option>
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
        <input type="text" class="form-control form-control-sm mt-0 @error('nominal') is-invalid @enderror" value="{{ old('nominal') }}" id="nominal" name="nominal" placeholder="Input nominal transaksi">
        @error('nominal')
        <div class="invalid-feedback bg-dark">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="description" class="form-label mb-0">Deskripsi</label>
        <textarea class="form-control form-control-sm mt-0" id="description" name="description" placeholder="Input deskripsi transaksi"></textarea>
    </div>
    <button type="submit" class="btn btn-primary mb-2 mt-6">Submit</button>
</form>
