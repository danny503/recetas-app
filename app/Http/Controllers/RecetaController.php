<?php

namespace App\Http\Controllers;

use App\Receta;
use App\Categoria;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class RecetaController extends Controller
{
    public function __construct()
    {        
        $this->middleware('auth', ['except' =>  ['show', 'search']]);
    }

    public function index()
    {
        //auth()->user()->recetas->dd();
        //$recetas = auth()->user()->recetas;
        $usuario = auth()->user();        

        $recetas = Receta::where('user_id', $usuario->id)->paginate(3);
        return view('recetas.index', compact('recetas', 'usuario'));
    }

    public function search(Request $request)
    {
        $busqueda = $request['buscar'];

        $recetas = Receta::where('titulo', 'like', '%' . $busqueda . '%')->paginate(1);
        $recetas->appends(['buscar' => $busqueda]);

        return view('busquedas.show', compact('recetas', 'busqueda'));
    }

    public function create()
    {
        //Obtener categorias (Sin modelo)
        //$categorias = DB::table('categorias')->get()->pluck('nombre', 'id');

        //Con modelo
        $categorias = Categoria::all(['id','nombre']);
        
        return view('recetas.create')->with('categorias', $categorias);
    }

    public function store(Request $request)
    {
        //validacion
        $data = $request->validate([
            'titulo' => 'required|min:3',
            'categoria' => 'required',
            'preparacion' => 'required',
            'ingredientes' => 'required',
            'imagen' => 'required|image'
        ]);
            //Ruta de la imagen
        $ruta_image = $request['imagen']->store('upload-recetas', 'public');

        //Relize de la imagen
        $img = Image::make( public_path("storage/{$ruta_image}"))->fit(1200, 550);
        $img->save();

        //Guardar en la DB sin modelo
        // DB::table('recetas')->insert([
        //     'titulo' => $data['titulo'],
        //     'ingredientes' => $data['ingredientes'],
        //     'preparacion' => $data['preparacion'],
        //     'imagen' => $ruta_image,
        //     'user_id' => Auth::user()->id,
        //     'categoria_id' => $data['categoria']
        // ]);

        //ALmacenar en DB con model
        Auth::user()->recetas()->create([
            'titulo' => $data['titulo'],
            'ingredientes' => $data['ingredientes'],
            'preparacion' => $data['preparacion'],
            'imagen' => $ruta_image,
            'categoria_id' => $data['categoria']
        ]);
        //$categorias = Categoria::DB

        return redirect()->action('RecetaController@index');
    }

    public function show(Receta $receta)
    {
        //Obtener si el usuario actual le gusta la receta y esta autenticado
        $like = ( auth()->user() ) ? auth()->user()->meGusta->contains($receta->id) : false;

        //Pasa la cantidad de likes a la vista
        $likes = $receta->likes->count();

        return view('recetas.show', compact('receta', 'like', 'likes'));
    }

    public function edit(Receta $receta)
    {
        $this->authorize('view', $receta);

        $categorias = Categoria::all('id', 'nombre');
        return view('recetas.edit', compact('categorias', 'receta'));
    }

    public function update(Request $request, Receta $receta)
    {
        //Revisar el policy
        $this->authorize('update', $receta);
        //validacion
        $data = $request->validate([
            'titulo' => 'required|min:3',
            'categoria' => 'required',
            'preparacion' => 'required',
            'ingredientes' => 'required'
        ]);

        //Asignar valores
        $receta->titulo = $data['titulo'];
        $receta->preparacion = $data['preparacion'];
        $receta->ingredientes = $data['ingredientes'];
        $receta->categoria_id = $data['categoria'];

        //Si el usuario actualiza la imagen
        if(request('imagen')){
            $ruta_image = $request['imagen']->store('upload-recetas', 'public');

            //Relize de la imagen
            $img = Image::make( public_path("storage/{$ruta_image}"))->fit(1200, 550);
            $img->save();

            //ASignamos al objeto
            $receta->imagen = $ruta_image;
        }
        $receta->save();

            //Redireccionar
        return redirect()->action('RecetaController@index');
    }

    public function destroy(Receta $receta)
    {
        //Ejecutar el policy
        $this->authorize('delete', $receta);
        //Eliminar receta
        $receta->delete();
        return redirect()->action('RecetaController@index');
    }
}
