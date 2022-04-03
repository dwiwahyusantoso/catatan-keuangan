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
            @include('transaction.create')
        </div>

        <div class="tab-pane fade" id="formTabungan">
            @include('saving.create')
        </div>
    </div>
</div>
