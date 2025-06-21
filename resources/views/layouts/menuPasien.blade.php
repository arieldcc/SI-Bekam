<div class="navbar-content">
    <ul class="pc-navbar">

        <li class="pc-item pc-caption">
            <label>Dashboard</label>
            <i class="ti ti-dashboard"></i>
        </li>
        <li class="pc-item">
            <a href="{{ route('pasien.dashboard') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                <span class="pc-mtext">Dashboard Pasien</span>
            </a>
        </li>

        <li class="pc-item pc-caption">
            <label>Layanan Bekam</label>
            <i class="ti ti-heart-handshake"></i>
        </li>
        <li class="pc-item">
            <a href="{{ route('pasien.registrasi.index') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-calendar-plus"></i></span>
                <span class="pc-mtext">Daftar Terapi</span>
            </a>
        </li>
        <li class="pc-item">
            <a href="{{ route('pasien.antrian.index') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-git-pull-request"></i></span>
                <span class="pc-mtext">Status Antrian</span>
            </a>
        </li>
        <li class="pc-item">
            <a href="{{ route('pasien.rekam-medis.index') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-clipboard-list"></i></span>
                <span class="pc-mtext">Rekam Medis</span>
            </a>
        </li>

        <li class="pc-item pc-caption">
            <label>Akun</label>
            <i class="ti ti-user-settings"></i>
        </li>
        <li class="pc-item">
            <a href="{{ route('pasien.profil.edit') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-settings"></i></span>
                <span class="pc-mtext">Pengaturan Profil</span>
            </a>
        </li>

    </ul>

    <div class="pc-navbar-card bg-info rounded">
        <h4 class="text-white">Selamat datang!</h4>
        <p class="text-white opacity-75">Pantau antrian dan hasil terapi Anda dengan mudah.</p>
        <a href="" class="btn btn-light text-info">
            Ke Dashboard
        </a>
    </div>

    <div class="w-100 text-center">
        <div class="badge theme-version badge rounded-pill bg-light text-dark f-12">Pasien Panel</div>
    </div>
</div>
