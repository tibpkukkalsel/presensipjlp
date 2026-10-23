<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\HariliburController;
use App\Http\Controllers\IzinabsenController;
use App\Http\Controllers\IzincutiController;
use App\Http\Controllers\IzinsakitController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KonfigurasiController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\UserController;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Psy\VarDumper\Presenter;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::middleware(['guest:karyawan'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/proseslogin', [AuthController::class, 'proseslogin']);
});


Route::middleware(['guest:user'])->group(function () {
    Route::get('/panel-adm14', function () {
        return view('auth.loginadmin');
    })->name('loginadmin');

    Route::post('/panel-adm14/prosesloginadmin', [AuthController::class, 'prosesloginadmin']);
    Route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin']);

    Route::get('/panel', function () {
        return redirect('/');
    });
});

Route::middleware(['auth:karyawan'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/proseslogout', [AuthController::class, 'proseslogout']);

    //Presensi
    Route::get('/presensi/{kode_jam_kerja}/create', [PresensiController::class, 'create']);
    Route::get('/presensi/pilihjamkerja', [PresensiController::class, 'pilihjamkerja']);
    Route::post('/presensi/store', [PresensiController::class, 'store']);
    Route::get('/presensi/scanqr', [PresensiController::class, 'scanqr']);
    Route::post('/presensi/storeqr', [PresensiController::class, 'storeqr']);
    //Edit Profile
    Route::get('/editprofile', [PresensiController::class, 'editprofile']);
    Route::post('/presensi/{nik}/updateprofile', [PresensiController::class, 'updateprofile']);
    //Edit lokasi
    Route::get('/editlokasi', [PresensiController::class, 'editlokasi']);
    Route::post('/presensi/{nik}/updatelokasi', [PresensiController::class, 'updatelokasi']);

    //Histori
    Route::get('/presensi/histori', [PresensiController::class, 'histori']);
    Route::post('/gethistori', [PresensiController::class, 'gethistori']);

    //Izin
    Route::get('/presensi/izin', [PresensiController::class, 'izin']);
    Route::get('/presensi/buatizin', [PresensiController::class, 'buatizin']);
    Route::post('/presensi/storeizin', [PresensiController::class, 'storeizin']);
    Route::post('/presensi/cekpengajuanizin', [PresensiController::class, 'cekpengajuanizin']);




    //Izin Absen

    Route::get('/izinabsen', [IzinabsenController::class, 'create']);
    Route::post('/izinabsen/store', [IzinabsenController::class, 'store']);
    Route::get('/izinabsen/{kode_izin}/edit', [IzinabsenController::class, 'edit']);
    Route::post('/izinabsen/{kode_izin}/update', [IzinabsenController::class, 'update']);
    //Izin Sakit
    Route::get('/izinsakit', [IzinsakitController::class, 'create']);
    Route::post('/izinsakit/store', [IzinsakitController::class, 'store']);
    Route::get('/izinsakit/{kode_izin}/edit', [IzinsakitController::class, 'edit']);
    Route::post('/izinsakit/{kode_izin}/update', [IzinsakitController::class, 'update']);
    //Izin Cuti
    Route::get('/izincuti', [IzincutiController::class, 'create']);
    Route::post('/izincuti/store', [IzincutiController::class, 'store']);
    Route::get('/izincuti/{kode_izin}/edit', [IzincutiController::class, 'edit']);
    Route::post('/izincuti/{kode_izin}/update', [IzincutiController::class, 'update']);
    Route::post('/izincuti/getmaxcuti', [IzincutiController::class, 'getmaxcuti']);

    Route::get('/izin/{kode_izin}/showact', [PresensiController::class, 'showact']);
    Route::get('/izin/{kode_izin}/delete', [PresensiController::class, 'deleteizin']);
});


//Route Yang Bisa di AKses Oleh Administrator dan Admin Departemen
Route::group(['prefix' => 'panel-adm14', 'middleware' => ['auth:user', 'role:administrator|admin departemen,user']], function () {
    Route::get('/proseslogoutadmin', [AuthController::class, 'proseslogoutadmin']);
    Route::get('/dashboardadmin', [DashboardController::class, 'dashboardadmin']);

    //Pegawai / Karyawan
    Route::get('/karyawan', [KaryawanController::class, 'index']);
    Route::get('/pegawai', [KaryawanController::class, 'index']);
    Route::get('/karyawan/{nik}/resetpassword', [KaryawanController::class, 'resetpassword']);
    Route::get('/pegawai/{nik}/resetpassword', [KaryawanController::class, 'resetpassword']);

    //Konfigruasi Jam Kerja
    Route::get('/konfigurasi/{nik}/setjamkerja', [KonfigurasiController::class, 'setjamkerja']);
    Route::post('/konfigurasi/storesetjamkerja', [KonfigurasiController::class, 'storesetjamkerja']);
    Route::post('/konfigurasi/updatesetjamkerja', [KonfigurasiController::class, 'updatesetjamkerja']);
    Route::post('/konfigurasi/storesetjamkerjabydate', [KonfigurasiController::class, 'storesetjamkerjabydate']);
    Route::get('/konfigurasi/{nik}/{bulan}/{tahun}/getjamkerjabydate', [KonfigurasiController::class, 'getjamkerjabydate']);
    Route::post('/konfigurasi/deletejamkerjabydate', [KonfigurasiController::class, 'deletejamkerjabydate']);
    //Presensi
    Route::get('/presensi/monitoring', [PresensiController::class, 'monitoring']);
    Route::post('/getpresensi', [PresensiController::class, 'getpresensi']);
    Route::post('/tampilkanpeta', [PresensiController::class, 'tampilkanpeta']);
    Route::get('/presensi/laporan', [PresensiController::class, 'laporan']);
    Route::post('/presensi/cetaklaporan', [PresensiController::class, 'cetaklaporan']);
    Route::get('/presensi/rekap', [PresensiController::class, 'rekap']);
    Route::post('/presensi/cetakrekap', [PresensiController::class, 'cetakrekap']);


    Route::get('/presensi/izinsakit', [PresensiController::class, 'izinsakit']);

    // Status Kehadiran Pegawai (Kelola Status Kehadiran oleh Admin)
    Route::get('/presensi/statuskehadiran', [PresensiController::class, 'statuskehadiran']);
    Route::post('/presensi/storestatuskehadiran', [PresensiController::class, 'storestatuskehadiran']);
    Route::post('/presensi/editstatuskehadiran', [PresensiController::class, 'editstatuskehadiran']);
    Route::post('/presensi/updatestatuskehadiran', [PresensiController::class, 'updatestatuskehadiran']);
    Route::post('/presensi/{id}/deletestatuskehadiran', [PresensiController::class, 'deletestatuskehadiran']);

    Route::post('/koreksipresensi', [PresensiController::class, 'koreksipresensi']);
    Route::post('/storekoreksipresensi', [PresensiController::class, 'storekoreksipresensi']);
});


//ROute yang Hanya bisa di akses oleh Administrator
Route::group(['prefix' => 'panel-adm14', 'middleware' => ['auth:user', 'role:administrator,user']], function () {



    //Pegawai / Karyawan
    Route::post('/karyawan/store', [KaryawanController::class, 'store']);
    Route::post('/karyawan/edit', [KaryawanController::class, 'edit']);
    Route::post('/karyawan/{nik}/update', [KaryawanController::class, 'update']);
    Route::post('/karyawan/{nik}/delete', [KaryawanController::class, 'delete']);
    Route::get('/karyawan/{nik}/lockandunlocklocation', [KaryawanController::class, 'lockandunlocklocation']);
    Route::get('/karyawan/{nik}/lockandunlockjamkerja', [KaryawanController::class, 'lockandunlockjamkerja']);

    Route::post('/pegawai/store', [KaryawanController::class, 'store']);
    Route::post('/pegawai/edit', [KaryawanController::class, 'edit']);
    Route::post('/pegawai/{nik}/update', [KaryawanController::class, 'update']);
    Route::post('/pegawai/{nik}/delete', [KaryawanController::class, 'delete']);
    Route::get('/pegawai/{nik}/lockandunlocklocation', [KaryawanController::class, 'lockandunlocklocation']);
    Route::get('/pegawai/{nik}/lockandunlockjamkerja', [KaryawanController::class, 'lockandunlockjamkerja']);
    //Seksi
    Route::get('/seksi', [DepartemenController::class, 'index'])->middleware('permission:view-departemen,user');
    Route::post('/seksi/store', [DepartemenController::class, 'store']);
    Route::post('/seksi/edit', [DepartemenController::class, 'edit']);
    Route::post('/seksi/{kode_dept}/update', [DepartemenController::class, 'update']);
    Route::post('/seksi/{kode_dept}/delete', [DepartemenController::class, 'delete']);

    // Fallback alias /departemen -> /seksi
    Route::get('/departemen', function () {
        return redirect('/seksi');
    });
    Route::post('/departemen/store', [DepartemenController::class, 'store']);
    Route::post('/departemen/edit', [DepartemenController::class, 'edit']);
    Route::post('/departemen/{kode_dept}/update', [DepartemenController::class, 'update']);
    Route::post('/departemen/{kode_dept}/delete', [DepartemenController::class, 'delete']);

    //Presensi

    Route::post('/presensi/approveizinsakit', [PresensiController::class, 'approveizinsakit']);
    Route::get('/presensi/{kode_izin}/batalkanizinsakit', [PresensiController::class, 'batalkanizinsakit']);




    //Cabang
    Route::get('/cabang', [CabangController::class, 'index']);
    Route::post('/cabang/store', [CabangController::class, 'store']);
    Route::post('/cabang/edit', [CabangController::class, 'edit']);
    Route::post('/cabang/update', [CabangController::class, 'update']);
    Route::post('/cabang/{kode_cabang}/delete', [CabangController::class, 'delete']);

    //Konfigurasi

    Route::get('/konfigurasi/lokasikantor', [KonfigurasiController::class, 'lokasikantor']);
    Route::post('/konfigurasi/updatelokasikantor', [KonfigurasiController::class, 'updatelokasikantor']);

    Route::get('/konfigurasi/jamkerja', [KonfigurasiController::class, 'jamkerja']);
    Route::post('/konfigurasi/storejamkerja', [KonfigurasiController::class, 'storejamkerja']);
    Route::post('/konfigurasi/editjamkerja', [KonfigurasiController::class, 'editjamkerja']);
    Route::post('/konfigurasi/updatejamkerja', [KonfigurasiController::class, 'updatejamkerja']);

    Route::post('/konfigurasi/jamkerja/{kode_jam_kerja}/delete', [KonfigurasiController::class, 'deletejamkerja']);


    Route::get('/konfigurasi/jamkerjadept', [KonfigurasiController::class, 'jamkerjadept']);
    Route::get('/konfigurasi/jamkerjadept/create', [KonfigurasiController::class, 'createjamkerjadept']);
    Route::post('/konfigurasi/jamkerjadept/store', [KonfigurasiController::class, 'storejamkerjadept']);
    Route::get('/konfigurasi/jamkerjadept/{kode_jk_dept}/edit', [KonfigurasiController::class, 'editjamkerjadept']);
    Route::post('/konfigurasi/jamkerjadept/{kode_jk_dept}/update', [KonfigurasiController::class, 'updatejamkerjadept']);
    Route::get('/konfigurasi/jamkerjadept/{kode_jk_dept}/show', [KonfigurasiController::class, 'showjamkerjadept']);
    Route::get('/konfigurasi/jamkerjadept/{kode_jk_dept}/delete', [KonfigurasiController::class, 'deletejamkerjadept']);

    //User
    Route::get('/konfigurasi/users', [UserController::class, 'index']);
    Route::post('/konfigurasi/users/store', [UserController::class, 'store']);
    Route::post('/konfigurasi/users/edit', [UserController::class, 'edit']);
    Route::post('/konfigurasi/users/{id_user}/update', [UserController::class, 'update']);
    Route::post('/konfigurasi/users/{id_user}/delete', [UserController::class, 'delete']);


    //Harilibur

    Route::get('/konfigurasi/harilibur', [HariliburController::class, 'index']);
    Route::get('/konfigurasi/harilibur/create', [HariliburController::class, 'create']);
    Route::post('/konfigurasi/harilibur/store', [HariliburController::class, 'store']);
    Route::post('/konfigurasi/harilibur/edit', [HariliburController::class, 'edit']);
    Route::post('/konfigurasi/harilibur/{kode_libur}/update', [HariliburController::class, 'update']);
    Route::post('/konfigurasi/harilibur/{kode_libur}/delete', [HariliburController::class, 'delete']);
    Route::get('/konfigurasi/harilibur/{kode_libur}/setkaryawanlibur', [HariliburController::class, 'setkaryawanlibur']);
    Route::get('/konfigurasi/harilibur/{kode_libur}/setlistkaryawanlibur', [HariliburController::class, 'setlistkaryawanlibur']);
    Route::get('/konfigurasi/harilibur/{kode_libur}/getsetlistkaryawanlibur', [HariliburController::class, 'getsetlistkaryawanlibur']);
    Route::post('/konfigurasi/harilibur/storekaryawanlibur', [HariliburController::class, 'storekaryawanlibur']);
    Route::post('/konfigurasi/harilibur/removekaryawanlibur', [HariliburController::class, 'removekaryawanlibur']);
    Route::get('/konfigurasi/harilibur/{kode_libur}/getkaryawanlibur', [HariliburController::class, 'getkaryawanlibur']);
    //Cuti

    Route::get('/cuti', [CutiController::class, 'index']);
    Route::post('/cuti/store', [CutiController::class, 'store']);
    Route::post('/cuti/edit', [CutiController::class, 'edit']);
    Route::post('/cuti/{kode_cuti}/update', [CutiController::class, 'update']);
    Route::post('/cuti/{kode_cuti}/delete', [CutiController::class, 'delete']);

    // Konfigurasi Penandatangan Laporan
    Route::get('/konfigurasi/penandatangan', [KonfigurasiController::class, 'penandatangan']);
    Route::post('/konfigurasi/updatepenandatangan', [KonfigurasiController::class, 'updatepenandatangan']);
});

// Redirect URL admin lama & alias ke prefix panel-adm14
Route::group(['middleware' => ['auth:user', 'role:administrator|admin departemen,user']], function () {
    Route::get('/panel/dashboardadmin', fn() => redirect('/panel-adm14/dashboardadmin'));
    Route::get('/proseslogoutadmin', fn() => redirect('/panel-adm14/proseslogoutadmin'));
    Route::get('/karyawan', fn() => redirect('/panel-adm14/pegawai'));
    Route::get('/pegawai', fn() => redirect('/panel-adm14/pegawai'));
    Route::get('/seksi', fn() => redirect('/panel-adm14/seksi'));
    Route::get('/departemen', fn() => redirect('/panel-adm14/seksi'));
    Route::get('/cabang', fn() => redirect('/panel-adm14/cabang'));
    Route::get('/presensi/monitoring', fn() => redirect('/panel-adm14/presensi/monitoring'));
    Route::get('/presensi/laporan', fn() => redirect('/panel-adm14/presensi/laporan'));
    Route::get('/presensi/rekap', fn() => redirect('/panel-adm14/presensi/rekap'));
    Route::get('/presensi/izinsakit', fn() => redirect('/panel-adm14/presensi/izinsakit'));
    Route::get('/presensi/statuskehadiran', fn() => redirect('/panel-adm14/presensi/statuskehadiran'));
    Route::get('/cuti', fn() => redirect('/panel-adm14/cuti'));
    Route::get('/konfigurasi/harilibur', fn() => redirect('/panel-adm14/konfigurasi/harilibur'));
    Route::get('/konfigurasi/jamkerja', fn() => redirect('/panel-adm14/konfigurasi/jamkerja'));
    Route::get('/konfigurasi/jamkerjadept', fn() => redirect('/panel-adm14/konfigurasi/jamkerjadept'));
    Route::get('/konfigurasi/users', fn() => redirect('/panel-adm14/konfigurasi/users'));
    Route::get('/konfigurasi/lokasikantor', fn() => redirect('/panel-adm14/konfigurasi/lokasikantor'));
    Route::get('/konfigurasi/penandatangan', fn() => redirect('/panel-adm14/konfigurasi/penandatangan'));
    Route::post('/konfigurasi/updatepenandatangan', [KonfigurasiController::class, 'updatepenandatangan']);

    // Alias AJAX POST lama agar request tetap berjalan normal jika dipanggil tanpa prefix
    Route::post('/getpresensi', [PresensiController::class, 'getpresensi']);
    Route::post('/tampilkanpeta', [PresensiController::class, 'tampilkanpeta']);
    Route::post('/koreksipresensi', [PresensiController::class, 'koreksipresensi']);
    Route::post('/storekoreksipresensi', [PresensiController::class, 'storekoreksipresensi']);
    Route::post('/presensi/approveizinsakit', [PresensiController::class, 'approveizinsakit']);
    Route::get('/presensi/{kode_izin}/batalkanizinsakit', [PresensiController::class, 'batalkanizinsakit']);
    Route::post('/presensi/cetaklaporan', [PresensiController::class, 'cetaklaporan']);
    Route::post('/presensi/cetakrekap', [PresensiController::class, 'cetakrekap']);

    // Alias Jam Kerja tanpa prefix
    Route::post('/konfigurasi/storejamkerja', [KonfigurasiController::class, 'storejamkerja']);
    Route::post('/konfigurasi/editjamkerja', [KonfigurasiController::class, 'editjamkerja']);
    Route::post('/konfigurasi/updatejamkerja', [KonfigurasiController::class, 'updatejamkerja']);
    Route::post('/konfigurasi/jamkerja/{kode_jam_kerja}/delete', [KonfigurasiController::class, 'deletejamkerja']);

    // Alias Set Jam Kerja Karyawan tanpa prefix
    Route::get('/konfigurasi/{nik}/setjamkerja', [KonfigurasiController::class, 'setjamkerja']);
    Route::post('/konfigurasi/storesetjamkerja', [KonfigurasiController::class, 'storesetjamkerja']);
    Route::post('/konfigurasi/updatesetjamkerja', [KonfigurasiController::class, 'updatesetjamkerja']);
    Route::post('/konfigurasi/storesetjamkerjabydate', [KonfigurasiController::class, 'storesetjamkerjabydate']);
    Route::get('/konfigurasi/{nik}/{bulan}/{tahun}/getjamkerjabydate', [KonfigurasiController::class, 'getjamkerjabydate']);
    Route::post('/konfigurasi/deletejamkerjabydate', [KonfigurasiController::class, 'deletejamkerjabydate']);

    // Alias Users tanpa prefix
    Route::post('/konfigurasi/users/store', [UserController::class, 'store']);
    Route::post('/konfigurasi/users/edit', [UserController::class, 'edit']);
    Route::post('/konfigurasi/users/{id_user}/update', [UserController::class, 'update']);
    Route::post('/konfigurasi/users/{id_user}/delete', [UserController::class, 'delete']);

    // Alias Cuti tanpa prefix
    Route::post('/cuti/store', [CutiController::class, 'store']);
    Route::post('/cuti/edit', [CutiController::class, 'edit']);
    Route::post('/cuti/{kode_cuti}/update', [CutiController::class, 'update']);
    Route::post('/cuti/{kode_cuti}/delete', [CutiController::class, 'delete']);

    // Alias AJAX harilibur tanpa prefix
    Route::get('/konfigurasi/harilibur/{kode_libur}/getsetlistkaryawanlibur', [HariliburController::class, 'getsetlistkaryawanlibur']);
    Route::post('/konfigurasi/harilibur/storekaryawanlibur', [HariliburController::class, 'storekaryawanlibur']);
    Route::post('/konfigurasi/harilibur/removekaryawanlibur', [HariliburController::class, 'removekaryawanlibur']);
    Route::get('/konfigurasi/harilibur/{kode_libur}/getkaryawanlibur', [HariliburController::class, 'getkaryawanlibur']);
});


Route::get('/createrolepermission', function () {

    try {
        Role::create(['name' => 'admin departemen']);
        // Permission::create(['name' => 'view-karyawan']);
        // Permission::create(['name' => 'view-departemen']);
        echo "Sukses";
    } catch (\Exception $e) {
        echo "Error";
    }
});


Route::get('/give-user-role', function () {
    try {
        $user = User::findorfail(1);
        $user->assignRole('administrator');
        echo "Sukses";
    } catch (\Exception $e) {
        //throw $th;
        echo "Error";
    }
});


Route::get('/give-role-permission', function () {
    try {
        $role = Role::findorfail(1);
        $role->givePermissionTo('view-departemen');
        echo "Sukses";
    } catch (\Exception $e) {
        //throw $th;
        echo "Error";
    }
});
