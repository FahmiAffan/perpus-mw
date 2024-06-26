 <?php

    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\BukuController;
    use App\Http\Controllers\DetailPeminjaman;
    use App\Http\Controllers\DetailPeminjamanController;
    use App\Http\Controllers\PeminjamanController;
    use App\Http\Controllers\PetugasController;
    use App\Http\Controllers\SiswaController;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;

    /*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });



    // Route::middleware('auth:sanctum')->group(function () {
    Route::resource('siswa', SiswaController::class);
    Route::resource('peminjaman', PeminjamanController::class);
    Route::resource('detailPeminjaman', DetailPeminjamanController::class);
    Route::resource('buku', BukuController::class);
    Route::put('/updateStatus/{id}', [PeminjamanController::class, 'updateStatus']);
    // });
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });


    Route::resource('users', PetugasController::class);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
