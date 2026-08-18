<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManifiestosController extends Controller
{
    /**
     * Muestra la vista principal de manifiestos.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Carga el archivo ubicado en resources/views/manifiestos.blade.php
        return view('manifiestos');
    }
}