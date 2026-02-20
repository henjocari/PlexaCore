<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConductorController;
use App\Http\Controllers\CloudFleet_Conductores;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\VerificarModulo;
use App\Http\Middleware\RefreshPermissions;
use App\Http\Controllers\HistorialHabitacionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PrecioGlpController;
use App\Http\Controllers\CarruselController;
use App\Http\Controllers\TicketController;

// ----------------------
// RUTAS PÚBLICAS (sin login)
// ----------------------
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// ----------------------
// RUTAS PROTEGIDAS (requieren sesión)
// ----------------------
Route::middleware(['auth', RefreshPermissions::class])->group(function () {

    Route::get('/', function () { return view('index'); })->name('index');
    Route::get('/index', function () { return view('index'); });
    
    // 🚫 Módulo Dashboard
    Route::get('/dashboard', function () { return view('dashboard'); })
    ->middleware(\App\Http\Middleware\VerificarModulo::class . ':Dashboard')
    ->name('dashboard');

    // 🚫 Módulo Tabla Conductores
    Route::get('/tablas', [ConductorController::class, 'tablas'])
        ->middleware(VerificarModulo::class . ':Tabla Conductores')
        ->name('tablas');

    // 🚫 Módulo Hotel
    Route::get('/hotel', [HabitacionController::class, 'hotel'])
        ->middleware(VerificarModulo::class . ':Hotel')
        ->name('hotel');

    //--------------Usuarios------------\\
    Route::get('/usuarios', [UsuarioController::class, 'index'])
        ->middleware(VerificarModulo::class . ':Usuarios')
        ->name('usuarios');
    
    Route::post('/usuarios', [UsuarioController::class, 'store'])
        ->middleware(VerificarModulo::class . ':Usuarios')
        ->name('usuarios.store');

    Route::put('/usuarios/{cedula}', [UsuarioController::class, 'update'])
        ->middleware(VerificarModulo::class . ':Usuarios')
        ->name('usuarios.update');

    Route::post('/usuarios/{cedula}/toggle', [UsuarioController::class, 'toggle'])
        ->middleware(VerificarModulo::class . ':Usuarios')
        ->name('usuarios.toggle');

    //--------------Precio GLP------------\\
    Route::get('/precio-glp', [PrecioGlpController::class, 'precioglp'])
        ->middleware(VerificarModulo::class . ':Precio GLP')
        ->name('precio.glp');
    
    Route::post('/precio-glp/store', [PrecioGlpController::class, 'store'])
        ->middleware(VerificarModulo::class . ':Precio GLP')
        ->name('precio.store');
    
    Route::patch('/precio-glp/{id}/inactivar', [PrecioGlpController::class, 'inactivar'])
        ->middleware(\App\Http\Middleware\VerificarModulo::class . ':Precio GLP')
        ->name('precio.inactivar');
    
    Route::get('/precio-glp/ver/{archivo}', [PrecioGlpController::class, 'verPDF'])
        ->name('precio.ver');

    // ==========================================
    //           MÓDULO DE VIAJES (CON CANDADOS)
    // ==========================================
    Route::get('/solicitar-viaje', [TicketController::class, 'verSolicitar'])
        ->middleware(VerificarModulo::class . ':Solicitar Viaje')
        ->name('tickets.solicitar');
    
    Route::get('/gestion-viajes', [TicketController::class, 'verGestion'])
        ->middleware(VerificarModulo::class . ':Gestion Viajes')
        ->name('tickets.gestion');

    Route::post('/tickets/crear', [TicketController::class, 'store'])->name('tickets.store');
    Route::post('/tickets/{id}/gestionar', [TicketController::class, 'gestionar'])->name('tickets.gestionar');


    Route::get('/probar-email', function() {
        try {
            $miCorreo = 'roisroisomg@gmail.com'; 
            $datos = ['empleado' => 'Usuario de Prueba', 'destino' => 'Destino Test', 'fecha' => '2026-12-31'];
            Illuminate\Support\Facades\Mail::to($miCorreo)->send(new App\Mail\SolicitudViajeMail($datos));
            return "✅ ¡ÉXITO! Laravel dice que envió el correo.";
        } catch (\Exception $e) {
            return "❌ ERROR DETECTADO: " . $e->getMessage();
        }
    });

    //--------------CARRUSEL------------\\
    Route::get('/carrusel', [CarruselController::class, 'carrusel'])
        ->middleware(\App\Http\Middleware\VerificarModulo::class . ':Carrusel')
        ->name('carrusel.index');

    Route::post('/carrusel/guardar', [CarruselController::class, 'store'])
        ->middleware(\App\Http\Middleware\VerificarModulo::class . ':Carrusel')
        ->name('carrusel.store');

    Route::post('/carrusel/{id}/update-imagen', [CarruselController::class, 'updateImagen'])
        ->middleware(\App\Http\Middleware\VerificarModulo::class . ':Carrusel')
        ->name('carrusel.update_imagen');

    Route::patch('/carrusel/{id}/inactivar', [CarruselController::class, 'inactivar'])
        ->middleware(\App\Http\Middleware\VerificarModulo::class . ':Carrusel')
        ->name('carrusel.inactivar');

    Route::patch('/carrusel/{id}/activar', [CarruselController::class, 'activar'])
        ->middleware(VerificarModulo::class . ':Carrusel')
        ->name('carrusel.activar');

   // ==========================================
    //       RUTAS DE TICKETS (PAPELERA)    
    // ==========================================
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/papelera', [TicketController::class, 'papelera'])->name('tickets.papelera');
    Route::patch('/tickets/{id}/inactivar', [TicketController::class, 'inactivar'])->name('tickets.inactivar');
    Route::patch('/tickets/{id}/activar', [TicketController::class, 'activar'])->name('tickets.activar');

    Route::get('/gestiondehotel', function () { return view('gestiondehotel'); });
    Route::get('/utilidades', function () { return view('buttons'); });

    // Conductores CRUD
    Route::get('/conductores/{id}', [ConductorController::class, 'show']);
    Route::post('/conductores', [ConductorController::class, 'store']);
    Route::put('/conductores/{id}', [ConductorController::class, 'update']);
    Route::delete('/conductores/{id}', [ConductorController::class, 'destroy']);
    Route::get('/conductores/buscar', [ConductorController::class, 'buscarDisponibles'])->name('conductores.buscar');

    // Habitaciones
    Route::put('/habitaciones/{numero}', [HabitacionController::class, 'update'])->name('habitaciones.update');
    Route::post('/habitaciones/{id}/asignar', [HabitacionController::class, 'asignarConductor'])->name('habitaciones.asignar');
    Route::post('/habitaciones/{id}/desasignar', [HabitacionController::class, 'desasignarConductor'])->name('habitaciones.desasignar');

    // HISTORIAL DE HABITACIONES (CON CANDADOS)
    Route::get('/historial-habitaciones/export-excel', [HistorialHabitacionController::class, 'exportExcel'])
        ->middleware(VerificarModulo::class . ':Historial Habitacion')
        ->name('historial.export.excel');
        
    Route::get('/historial-habitaciones', [HistorialHabitacionController::class, 'index'])
        ->middleware(VerificarModulo::class . ':Historial Habitacion')
        ->name('historial.habitaciones');

    // CloudFleet
    Route::get('/cloud_conductor/', [CloudFleet_Conductores::class, 'obtenerTodos'])->name('actualizarconductores');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});