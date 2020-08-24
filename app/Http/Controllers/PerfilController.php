<?php

namespace App\Http\Controllers;

use App\Perfil;
use App\Receta;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except', 'show']);
    }
    
    public function show(Perfil $perfil)
    {
        //Obtener las recetas con paginacion
        $recetas = Receta::where('user_id', $perfil->user_id)->paginate(10);
        return view('perfiles.show', compact('perfil', 'recetas'));
    }

    public function edit(Perfil $perfil)
    {
        //Ejecutar el policy
        $this->authorize('view', $perfil);
        return view('perfiles.edit', compact('perfil'));
    }

    public function update(Request $request, Perfil $perfil)
    {
        //Ejecutar el policy
        $this->authorize('update', $perfil);
        //validar
        $data = request()->validate([
            'nombre' => 'required',
            'url' => 'required',
            'biografia' => 'required'
        ]);

        //Si el usuario sube una imagen
        if($request['imagen']){
             //Ruta de la imagen
            $ruta_image = $request['imagen']->store('upload-perfiles', 'public');

            //Relize de la imagen
            $img = Image::make( public_path("storage/{$ruta_image}"))->fit(1200, 550);
            $img->save();

            //crea un arreglo de imagen
            $array_imagen = ['imagen' => $ruta_image];
         } 
        //Asignar nombre y url
         auth()->user()->url = $data['url'];
         auth()->user()->name = $data['nombre'];
         auth()->user()->save();

         //Eliminar url y name de $data
         unset($data['url']);
         unset($data['nombre']);
        //Asignar biografia y imagen
            auth()->user()->perfil()->update( array_merge(
                $data,
                $array_imagen ?? []
            ));
        //Guardar informacion

        //Redireccionar
        return redirect()->action('RecetaController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
