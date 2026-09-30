<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Roles;
use App\Models\PermisosModulo;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    /**
     * Panel "Usuarios y roles".
     */
    public function index()
    {
        $usuarios = Usuario::with('rolInfo')->orderBy('cedula')->get();
        $roles    = Roles::orderBy('id')->get();

        // Catálogo de pantallas/módulos
        $paginas = PermisosModulo::distinct()->orderBy('paginas')->pluck('paginas');

        // Qué pantallas tiene cada rol
        $rolPaginas = [];
        foreach ($roles as $rol) {
            $rolPaginas[$rol->id] = PermisosModulo::where('roles', $rol->id)->pluck('paginas')->toArray();
        }

        // Matriz pantalla × rol
        $matriz = [];
        foreach ($rolPaginas as $rolId => $pags) {
            foreach ($pags as $pag) {
                $matriz[$pag][$rolId] = true;
            }
        }

        // Info de roles para JS
        $rolesInfo = [];
        foreach ($roles as $r) {
            $rolesInfo[$r->id] = ['id' => $r->id, 'nombre' => $r->nombre];
        }

        // Datos limpios de usuarios para JS (sin contraseña)
        $usuariosJson = [];
        foreach ($usuarios as $u) {
            $usuariosJson[(string) $u->cedula] = [
                'cedula'         => $u->cedula,
                'Nombre'         => $u->Nombre,
                'Apellido'       => $u->Apellido,
                'email'          => $u->email,
                'cel'            => $u->cel,
                'rol'            => $u->rol,
                'estado'         => (int) $u->estado,
                'tipo_operacion' => $u->tipo_operacion,   // ⚠️ CLAVE: sin esto el modal abre en "Todas" y guarda NULL
            ];
        }

        // Base64 para evitar parse errors de Blade
        $usuariosJsonB64 = base64_encode(json_encode($usuariosJson, JSON_UNESCAPED_UNICODE));
        $rolesDataB64    = base64_encode(json_encode($rolPaginas, JSON_UNESCAPED_UNICODE));
        $rolesInfoB64    = base64_encode(json_encode($rolesInfo, JSON_UNESCAPED_UNICODE));

        // Extraer y unificar las operaciones dinámicas para el formulario
        $operacionesRaw = DB::table('silog_manifiestos')
            ->whereRaw("UPPER(estado) != 'ANULADO'")
            ->distinct()
            ->pluck('tipoOperacion')
            ->filter();

        $operacionesUnificadas = $operacionesRaw->map(function ($op) {
            $opTrim = trim($op);
            return str_contains(strtoupper($opTrim), 'GLP') ? 'GLP' : $opTrim;
        })->unique()->sort()->values();

        $stats = [
            'roles'    => $roles->count(),
            'permisos' => $paginas->count(),
            'usuarios' => $usuarios->count(),
        ];

        $tuRol = auth()->user()->rolInfo ?? null;

        return view('usuarios', compact(
            'usuarios', 'roles', 'paginas', 'rolPaginas', 'matriz', 'stats', 'tuRol',
            'usuariosJsonB64', 'rolesDataB64', 'rolesInfoB64', 'operacionesUnificadas'
        ));
    }

    // ================= USUARIOS =================

    public function store(Request $request)
    {
        $request->validate([
            'cedula'         => 'required|numeric|unique:usuarios,cedula',
            'Nombre'         => 'required|string|max:50',
            'Apellido'       => 'required|string|max:50',
            'email'          => 'nullable|email|max:250',
            'cel'            => 'nullable|string|max:30',
            'contraseña'     => 'required|string|min:6',
            'rol'            => 'required|integer',
            'estado'         => 'required|boolean',
            'tipo_operacion' => 'nullable|array',
        ]);

        $usuario = Usuario::create([
            'cedula'     => $request->cedula,
            'Nombre'     => $request->Nombre,
            'Apellido'   => $request->Apellido,
            'email'      => $request->email,
            'cel'        => $request->cel,
            'contraseña' => $request->contraseña,
            'rol'        => $request->rol,
            'estado'     => $request->estado,
        ]);

        // ✅ Asignación directa: garantiza que se guarde aunque fillable falle
        $usuario->tipo_operacion = $request->filled('tipo_operacion') ? implode(',', $request->tipo_operacion) : null;
        $usuario->save();

        return redirect()->back()->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, $cedula)
    {
        $usuario = Usuario::findOrFail($cedula);

        $request->validate([
            'Nombre'         => 'required|string|max:50',
            'Apellido'       => 'required|string|max:50',
            'email'          => 'nullable|email|max:250',
            'cel'            => 'nullable|string|max:30',
            'rol'            => 'required|integer',
            'estado'         => 'required|boolean',
            'contraseña'     => 'nullable|string|min:6',
            'tipo_operacion' => 'nullable|array', // ¡EL ERROR ESTABA AQUÍ! Ahora acepta múltiples opciones
        ]);

        $usuario->Nombre   = $request->Nombre;
        $usuario->Apellido = $request->Apellido;
        $usuario->email    = $request->email;
        $usuario->cel      = $request->cel;
        $usuario->rol      = $request->rol;
        $usuario->estado   = $request->estado;

        if ($request->filled('contraseña')) {
            $usuario->contraseña = $request->contraseña;
        }

        // ✅ Asignación segura: Convierte el array ['GLP', 'PGR'] en texto "GLP,PGR"
        $usuario->tipo_operacion = is_array($request->tipo_operacion) && count($request->tipo_operacion) > 0 
                                    ? implode(',', $request->tipo_operacion) 
                                    : null;

        $usuario->save();

        return redirect()->back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function toggle($cedula)
    {
        $usuario = Usuario::where('cedula', $cedula)->firstOrFail();

        if (auth()->user()->cedula == $usuario->cedula && $usuario->estado) {
            return back()->with('error', 'No puedes inactivar tu propio usuario.');
        }

        $usuario->estado = !$usuario->estado;
        $usuario->save();
        return back()->with('success', 'El estado del usuario ha sido actualizado.');
    }

    // ================= ROLES Y PERMISOS =================

    public function storeRol(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:100|unique:permisos_roles,nombre']);

        $rol = Roles::create(['nombre' => $request->nombre]);
        $this->guardarPermisosRol($rol->id, $request);

        return back()->with('success', 'Rol creado con sus permisos.');
    }

    public function updateRol(Request $request, $id)
    {
        $rol = Roles::findOrFail($id);

        $request->validate(['nombre' => 'required|string|max:100|unique:permisos_roles,nombre,' . $id]);

        $rol->nombre = $request->nombre;
        $rol->save();

        $this->guardarPermisosRol($rol->id, $request);
        return back()->with('success', 'Rol y permisos actualizados.');
    }

    public function destroyRol($id)
    {
        $rol = Roles::findOrFail($id);

        if ($rol->id == 1) {
            return back()->with('error', 'El rol Super Administrador no se puede eliminar.');
        }
        if ($rol->usuarios()->count() > 0) {
            return back()->with('error', 'No se puede eliminar: hay usuarios con este rol.');
        }

        PermisosModulo::where('roles', $rol->id)->delete();
        $rol->delete();

        return back()->with('success', 'Rol eliminado correctamente.');
    }

    /**
     * Sincroniza las pantallas (módulos) de un rol en permisos_modulos.
     */
    private function guardarPermisosRol($rolId, Request $request)
    {
        $paginasSel = (array) $request->input('paginas', []);

        PermisosModulo::where('roles', $rolId)
            ->whereNotIn('paginas', $paginasSel)
            ->delete();

        $existentes = PermisosModulo::where('roles', $rolId)->pluck('paginas')->toArray();
        foreach ($paginasSel as $pagina) {
            if (!in_array($pagina, $existentes)) {
                PermisosModulo::create(['roles' => $rolId, 'paginas' => $pagina]);
            }
        }
    }
}