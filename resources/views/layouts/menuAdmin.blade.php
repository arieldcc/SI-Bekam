<div class="navbar-content">
    <ul class="pc-navbar">

        <li class="pc-item pc-caption">
            <label>Dashboard</label>
            <i class="ti ti-dashboard"></i>
        </li>
        <li class="pc-item">
            <a href="{{ route('admin.dashboard') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                <span class="pc-mtext">Dashboard Admin</span>
            </a>
        </li>
        {{-- <li class="pc-item">
            <a href="{{ route('terapis.dashboard') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-report-medical"></i></span>
                <span class="pc-mtext">Dashboard Terapis</span>
            </a>
        </li>
        <li class="pc-item">
            <a href="{{ route('pasien.dashboard') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-user"></i></span>
                <span class="pc-mtext">Dashboard Pasien</span>
            </a>
        </li> --}}

        <li class="pc-item pc-caption">
            <label>Master Data</label>
            <i class="ti ti-database"></i>
        </li>
        <li class="pc-item">
            <a href="{{ route('admin.therapists.index') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-accessible"></i></span>
                <span class="pc-mtext">Data Terapis</span>
            </a>
        </li>
        <li class="pc-item">
            <a href="{{ route('admin.patients.index') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-user"></i></span>
                <span class="pc-mtext">Data Pasien</span>
            </a>
        </li>

        <li class="pc-item">
            <a href="{{ route('admin.services.index') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-book"></i></span>
                <span class="pc-mtext">Data Service</span>
            </a>
        </li>

        <li class="pc-item">
            <a href="{{ route('admin.contacts.index') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-mailbox"></i></span>
                <span class="pc-mtext">Data Contanct</span>
            </a>
        </li>

        <li class="pc-item pc-caption">
            <label>Layanan Klinik</label>
            <i class="ti ti-activity-heartbeat"></i>
        </li>
        <li class="pc-item">
            <a href="{{ route('admin.schedules.index') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-calendar-event"></i></span>
                <span class="pc-mtext">Jadwal Terapis</span>
            </a>
        </li>
        <li class="pc-item">
            <a href="{{ route('admin.registrations.index') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-notebook"></i></span>
                <span class="pc-mtext">Pendaftaran Pasien</span>
            </a>
        </li>
        {{-- <li class="pc-item">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-stethoscope"></i></span>
                <span class="pc-mtext">Janji Temu</span>
            </a>
        </li> --}}
        <li class="pc-item">
            <a href="{{ route('admin.rekam-medis.index') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-notes"></i></span>
                <span class="pc-mtext">Rekam Medis</span>
            </a>
        </li>

        <li class="pc-item pc-caption">
            <label>Administrasi</label>
            <i class="ti ti-shield-lock"></i>
        </li>
        <li class="pc-item">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-list-check"></i></span>
                <span class="pc-mtext">Log Aktivitas</span>
            </a>
        </li>
        <li class="pc-item">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-users"></i></span>
                <span class="pc-mtext">Manajemen Pengguna</span>
            </a>
        </li>

    </ul>

    <div class="pc-navbar-card bg-primary rounded">
        <h4 class="text-white">Selamat Datang</h4>
        <p class="text-white opacity-75">Panel admin sistem informasi klinik bekam</p>
    </div>

    <div class="w-100 text-center">
        <div class="badge theme-version badge rounded-pill bg-light text-dark f-12"></div>
    </div>
</div>
