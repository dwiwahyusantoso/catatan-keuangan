<div class="row">
    <div class="col-md-9">
        <div class="mt-2 mb-3"><mark class="rounded py-2 px-2">Saldo Utama: Rp {{ $saldo->saldo ?? "" }},-</mark></div>
    </div>
    <div class="col-md-1">
        <div class="nav-item dropdown">
            <div class="nav-link dropdown-toggle" style="color: black" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              {{ Auth()->user()->username }}
            </div>
            <ul class="dropdown-menu my-0 pt-0 pb-1" aria-labelledby="navbarDropdown">
                <li>
                    <a class="nav-link dropdown-item my-0 py-0" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('form-logout').submit();" style="color: black">
                        <span class="menu-title">Logout</span>
                    </a>
                    <form id="form-logout" action="{{ route('logout') }}" method="post" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
@if (isset($savings))
    @foreach ($savings as $saving)
        <div class="row">
            <div class="col-md-4">Uang Tabungan {{$saving->saving_name ?? ""}}: Rp {{$saving->saldo ?? ""}},-</div>
            <div class="col-md-4"><a style="text-decoration: none" href="{{ route('show', ['id' => $saving->id, 'saving_name' => $saving->saving_name])}}"><button class="rounded btn btn-sm btn-info px-1 py-0">Riwayat tabungan {{ $saving->saving_name }}</button></a></div>
        </div>
    @endforeach
@endif
