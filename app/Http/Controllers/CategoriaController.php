<?php

namespace App\Http\Controllers;

use App\Receta;
use App\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function show(Categoria $categoria)
    {
        $recetas = Receta::where('categoria_id', $categoria->id)->paginate(3);


        return view('categorias.show', compact('recetas', 'categoria'));
    }
}
