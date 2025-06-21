<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminMedicalRecordController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\RegistrationController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\serviceController;
use App\Http\Controllers\Admin\TherapistController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Landingpage\mainLandingPageController;
use App\Http\Controllers\Landingpage\PatientRegisterController;
use App\Http\Controllers\Pasien\PasienDashboardController;
use App\Http\Controllers\Pasien\PasienMedicalRecordController;
use App\Http\Controllers\Pasien\PasienRegistrationController;
use App\Http\Controllers\Pasien\ProfilController;
use App\Http\Controllers\Pasien\QueueStatusController;
use App\Http\Controllers\Terapis\MedicalRecordController;
use App\Http\Controllers\Terapis\PatientTherapisController;
use App\Http\Controllers\Terapis\ProfileController;
use App\Http\Controllers\Terapis\ScheduleController as TerapisScheduleController;
use App\Http\Controllers\Terapis\TerapisDashboardController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::controller(mainLandingPageController::class)->group(function(){
    Route::get('/', 'index');
    Route::get('/jadwal', 'jadwal')->name('jadwal');
});

Route::prefix('pendaftaran')->group(function () {
    Route::get('/', [PatientRegisterController::class, 'create'])->name('landing.registration.create');
    Route::post('/', [PatientRegisterController::class, 'store'])->name('landing.registration.store');
});


Route::controller(AuthController::class)->group(function(){
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'auth_login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware(['auth', 'role:admin'])->group(function(){
    Route::controller(AdminDashboardController::class)->group(function(){
        Route::get('/admin-dashboard', 'adminDashboard')->name('admin.dashboard');
    });

    // Route::controller(TherapistController::class)->group(function(){
    //     Route::get('/therapists', 'index')->name('admin.therapists.index');
    // });
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('therapists')->name('therapists.')->controller(TherapistController::class)->group(function () {
        Route::get('/', 'index')->name('index');            // admin.therapists.index
        Route::get('/create', 'create')->name('create');    // admin.therapists.create
        Route::post('/', 'store')->name('store');           // admin.therapists.store
        Route::get('/{id}/edit', 'edit')->name('edit');     // admin.therapists.edit
        Route::put('/{id}', 'update')->name('update');      // admin.therapists.update
        Route::delete('/{id}', 'destroy')->name('destroy'); // admin.therapists.destroy
    });
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('patients')->name('patients.')->controller(PatientController::class)->group(function () {
        Route::put('/{id}/toggle-status', 'toggleStatus')->name('toggle-status'); // HARUS DULUAN
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('schedules')->name('schedules.')->controller(ScheduleController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');

        Route::put('/{id}/update-status', 'updateStatus')->name('update-status');
    });
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Routing untuk menu pendaftaran pasien
    Route::prefix('registrations')->name('registrations.')->controller(RegistrationController::class)->group(function () {
        Route::get('/get-therapist-schedules/{id}', 'getTherapistSchedules')->name('getTherapistSchedules');
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::put('/{id}/update-status', 'updateStatus')->name('update-status');
    });
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Routing untuk manajemen layanan
    Route::prefix('services')->name('services.')->controller(serviceController::class)->group(function () {
        Route::put('services/{id}/toggle', 'toggle')->name('toggle');
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Routing untuk menu kontak
    Route::prefix('contacts')->name('contacts.')->controller(ContactController::class)->group(function () {
        Route::put('/{id}/toggle', 'toggle')->name('toggle');
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('rekam-medis')->name('rekam-medis.')->controller(AdminMedicalRecordController::class)->group(function () {
        Route::get('/rekam-medis', 'index')->name('index');
        Route::get('/rekam-medis/{rekam_medis}', 'show')->name('show');
    });
});

Route::middleware(['auth', 'role:terapis,admin'])->group(function(){
    Route::controller(TerapisDashboardController::class)->group(function(){
        Route::get('/terapis-dashboard', 'terapisDashboard')->name('terapis.dashboard');
    });
});

Route::middleware(['auth', 'role:terapis'])->prefix('terapis')->name('terapis.')->group(function () {
    Route::prefix('jadwal')->name('jadwal.')->controller(TerapisScheduleController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::patch('/{schedule}/status', 'updateStatus')->name('updateStatus');
    });
});

Route::middleware(['auth', 'role:terapis'])->prefix('terapis')->name('terapis.')->group(function () {
    Route::prefix('pasien')->name('pasien.')->controller(PatientTherapisController::class)->group(function () {
        Route::get('/pasien', 'index')->name('index');
        Route::patch('/pasien/{id}', 'update')->name('update');
    });
});

Route::middleware(['auth', 'role:terapis'])->prefix('terapis')->name('terapis.')->group(function () {
    Route::prefix('rekam-medis')->name('rekam-medis.')->controller(MedicalRecordController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{rekam_medi}/edit', 'edit')->name('edit');
        Route::put('/{rekam_medi}', 'update')->name('update');
        Route::get('rekam-medis/cetak/pdf', 'exportPdf')->name('pdf');
    });
});

Route::middleware(['auth', 'role:terapis'])->prefix('terapis')->name('terapis.')->group(function () {
    Route::prefix('profil')->name('profil.')->controller(ProfileController::class)->group(function () {
        Route::get('/profil', 'edit')->name('edit');
        Route::put('/profil', 'update')->name('update');
    });
});

Route::middleware(['auth', 'role:pasien,admin'])->group(function(){
    Route::controller(PasienDashboardController::class)->group(function(){
        Route::get('/pasien-dashboard', 'pasienDashboard')->name('pasien.dashboard');
    });
});

Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->name('pasien.')->group(function () {
    Route::prefix('registrasi')->name('registrasi.')->controller(PasienRegistrationController::class)->group(function () {
        Route::get('/registrasi', 'create')->name('create');
        Route::post('/registrasi', 'store')->name('store');
        Route::get('/riwayat', 'index')->name('index');
    });
});

Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->name('pasien.')->group(function () {
    Route::prefix('antrian')->name('antrian.')->controller(QueueStatusController::class)->group(function () {
        Route::get('/antrian', 'index')->name('index');
    });
});

Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->name('pasien.')->group(function () {
    Route::prefix('profil')->name('profil.')->controller(ProfilController::class)->group(function () {
        Route::get('profil', 'edit')->name('edit');
        Route::put('profil', 'update')->name('update');
    });
});

Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->name('pasien.')->group(function () {
    Route::prefix('rekam-medis')->name('rekam-medis.')->controller(PasienMedicalRecordController::class)->group(function () {
        Route::get('rekam-medis', 'index')->name('index');
        Route::get('rekam-medis/{id}', 'show')->name('show');
    });
});
