<?php

namespace App\Http\Controllers;

use App\Models\Manifiesto;
use Illuminate\Http\Request;

class ManifiestosController extends Controller
{
    /**
     * Carga la vista de manifiestos con los datos de la base de datos.
     */
    public function index()
    {
        // Consulta todos los registros ordenados por id descendente
        $manifiestos = Manifiesto::orderBy('id', 'desc')->get();

        // Retorna la vista enviando la variable $manifiestos
        return view('manifiestos', compact('manifiestos'));
    }
}