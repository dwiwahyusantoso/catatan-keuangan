<form action="/tabungan" method="post" class="ms-2 me-2">
    @csrf
    <div class="mb-3">
        <label for="nama_tabungan" class="form-label mb-0">Nama Tabungan</label>
        <input class="form-control form-control-sm mt-0 @error('nama_tabungan') is-invalid @enderror" value="{{ old('nama_tabungan') }}" list="datalist" id="nama_tabungan" name="nama_tabungan" placeholder="Pilih Nama Tabungan">
        <datalist id="datalist">
            @foreach ($savings as $saving)
            <option value="{{ $saving->saving_name }}">
            @endforeach
        </datalist>
        @error('nama_tabungan')
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
    <button type="submit" class="btn btn-primary mb-2 mt-6">Submit</button>
</form>
