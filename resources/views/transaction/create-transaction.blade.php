<div>
    <ul class="nav nav-tabs pb-2">
        <li class="pe-3">
            <button class="btn-info rounded" data-bs-toggle="tab" data-bs-target="#formTransaction">+ Transaksi</button>
        </li>
        <li>
            <button class="btn-info rounded" data-bs-toggle="tab" data-bs-target="#formTabungan">+ Tabungan</button>
        </li>
    </ul>

    <div class="tab-content bg-secondary col-md-10 offset-md-1 mb-3">
        <div class="tab-pane fade" id="formTransaction">
            <form action="/transaction" method="post" class="ms-2 me-2">
            @csrf
                <div class="mb-3">
                    <label for="kategori" class="form-label mb-0">Kategori Transaksi</label>
                    <input class="form-control form-control-sm mt-0" list="datalist" id="kategori" name="kategori" placeholder="Pilih Kategori Transaksi">
                    <datalist id="datalist">
                        @foreach ($transactions as $transaction)
                        <option value="{{ $transaction->kategori }}">
                        @endforeach
                    </datalist>
                </div>
                <div class="mb-3">
                    <label for="jenis_transaksi" class="form-label mb-0">Jenis Trsansaksi</label>
                    <select class="form-select form-select-sm mt-0" id="jenis_transaksi" name="jenis_transaksi">
                        <option>Pilih jenis transaksi</option>
                        <option value="masuk">pemasukan</option>
                        <option value="keluar">pengeluaran</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="nominal" class="form-label mb-0">Nominal</label>
                    <input type="text" class="form-control form-control-sm mt-0" id="nominal" name="nominal" placeholder="Input nominal transaksi">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label mb-0">Deskripsi</label>
                    <textarea class="form-control form-control-sm mt-0" id="description" name="description" placeholder="Input deskripsi transaksi"></textarea>
                </div>
                <button type="submit" class="btn btn-primary mb-2 mt-6">Submit</button>
            </form>
        </div>

        <div class="tab-pane fade" id="formTabungan">
            <form action="/tabungan" method="post" class="ms-2 me-2">
            @csrf
                <div class="mb-3">
                    <label for="nama_tabungan" class="form-label mb-0">Nama Tabungan</label>
                    <input class="form-control form-control-sm mt-0" list="datalist" id="nama_tabungan" name="nama_tabungan" placeholder="Pilih Nama Tabungan">
                    <datalist id="datalist">
                        @foreach ($savings as $saving)
                        <option value="{{ $saving->saving_name }}">
                        @endforeach
                    </datalist>
                </div>
                <div class="mb-3">
                    <label for="jenis_transaksi" class="form-label mb-0">Jenis Trsansaksi</label>
                    <select class="form-select form-select-sm mt-0" id="jenis_transaksi" name="jenis_transaksi">
                        <option value="">Pilih jenis transaksi</option>
                        <option value="masuk">pemasukan</option>
                        <option value="keluar">pengeluaran</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="nominal" class="form-label mb-0">Nominal</label>
                    <input type="text" class="form-control form-control-sm mt-0" id="nominal" name="nominal" placeholder="Input nominal transaksi">
                </div>
                <button type="submit" class="btn btn-primary mb-2 mt-6">Submit</button>
            </form>
        </div>
    </div>
</div>
