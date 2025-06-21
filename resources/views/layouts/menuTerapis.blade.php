<div class="navbar-content">
    <ul class="pc-navbar">

        <li class="pc-item pc-caption">
            <label>Dashboard</label>
            <i class="ti ti-dashboard"></i>
        </li>
        <li class="pc-item">
            <a href="{{ route('terapis.dashboard') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                <span class="pc-mtext">Dashboard Terapis</span>
            </a>
        </li>

        <li class="pc-item pc-caption">
            <label>Pelayanan</label>
            <i class="ti ti-activity-heartbeat"></i>
        </li>
        <li class="pc-item">
            <a href="{{ route('terapis.jadwal.index') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-calendar-event"></i></span>
                <span class="pc-mtext">Jadwal Saya</span>
            </a>
        </li>
        <li class="pc-item">
            <a href="{{ route('terapis.rekam-medis.index') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-notes"></i></span>
                <span class="pc-mtext">Rekam Medis</span>
            </a>
        </li>
        <li class="pc-item">
            <a href="{{ route('terapis.pasien.index') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-users"></i></span>
                <span class="pc-mtext">Daftar Pasien</span>
            </a>
        </li>

        <li class="pc-item pc-caption">
            <label>Akun</label>
            <i class="ti ti-user-settings"></i>
        </li>
        <li class="pc-item">
            <a href="{{ route('terapis.profil.edit') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-settings"></i></span>
                <span class="pc-mtext">Pengaturan Profil</span>
            </a>
        </li>

    </ul>

    <div class="pc-navbar-card bg-success rounded">
        <h4 class="text-white">Halo Terapis!</h4>
        <p class="text-white opacity-75">Kelola jadwal dan rekam medis pasien dengan mudah.</p>
        <a href="{{ route('terapis.dashboard') }}" class="btn btn-light text-success">
            Ke Dashboard
        </a>
    </div>

    <div class="w-100 text-center">
        <div class="badge theme-version badge rounded-pill bg-light text-dark f-12">Terapis Panel</div>
    </div>
</div>
