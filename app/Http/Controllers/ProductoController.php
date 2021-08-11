<?php

namespace App\Http\Controllers;

use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Mime\Encoder\Base64Encoder;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()     
    {
        //
    }
    // composer create-project laravel/laravel web-api
    //php artisan make:controller ProductoController -r
    public function obtenerPosts()
    {

        $datos= DB::table('post')
            ->select('post.*')
            ->get();


        return json_encode(["status"=>"success",
            "datos"=>$datos]);
    }


     function subirImagen(Request $request){

        if(!$request->hasFile('image')) {
            return response()->json(['upload_file_not_found'], 400);
        }
        $file = $request->file('image');
        if(!$file->isValid()) {
            return response()->json(['invalid_file_upload'], 400);
        }
        $path = public_path() . '/uploads/images/';
        $filename = $file->getClientOriginalName();
        $filename = pathinfo($filename,PATHINFO_BASENAME);
        $name_file  = str_replace(" " , "_" , $filename);
        $extension = $file->getClientOriginalExtension();

        $imagen = date("His") . '-' . $name_file;

        $file->move($path, $imagen);
        return response()->json(compact('path'));


// /////
// if(!$request->hasFile('image')) {
//     return response()->json(['upload_file_not_found'], 400);
// }
// $file = $request->file('image');
// if(!$file->isValid()) {
//     return response()->json(['invalid_file_upload'], 400);
// }
// $path = public_path() . '/uploads/images/';

// // $path = $request->image->store('public/images');

// $filename = $file->getClientOriginalName();
// $filename = pathinfo($filename,PATHINFO_BASENAME);
// $name_file  = str_replace(" " , "_" , $filename);
// $extension = $file->getClientOriginalExtension();

// $imagen = date("His") . '-' . $name_file;

// $file->move($path, $imagen);

// // return response()->json(compact('path'));
// return json_encode([
//     'status'=>"success $imagen ----- $path",
//     'error'=>false,
//     'datos'=>null
// ]);


        // guardar imagen 
        
        // if(!$request->hasFile('image')) {
        //     return response()->json(['upload_file_not_found'], 400);
        // }
        // $file = $request->file('image');
        // if(!$file->isValid()) {
        //     return response()->json(['invalid_file_upload'], 400);
        // }
        // $path = public_path() . '/uploads/images/';
        // $file->move($path, $file->getClientOriginalName());
        // return response()->json(compact('path'));


        // if($request->hash_file('file') )
        // {
        //     $file = $request->file('file');
        //     $filename = $file->getClientOriginalName();

        //     $filename = pathinfo($filename,PATHINFO_DIRNAME);

        //     $name_file  = str_replace(" " , "_" , $filename);
        //     $extension = $file->getClientOriginalExtension();

        //     $imagen = date("His") . '-' . $name_file  . '.' . $extension;
        //     $file->move(public_path('Files/', $imagen));
    
        //     return response()->json(
        //         ["message"=> "Image upload succesful!"]);
        // }else{
        //   return response()->json(
        //       ["message"=> "error upload image!"]);
        // }

  }

 
    public function guadarPost(Request $request)
    {
  
    
    $titulo=$request->titulo;
    $imagen=$request->imagen;
    $descripcion=$request->descripcion;

    if(strlen($imagen) > 0)
    $img_decoder = base64_decode($imagen);

    $imagen_name = date("His") . '-.jpg';
    $path = public_path() . '/uploads/images/';
    $img_upload_dir = "$path"."$imagen_name";
    
        $flag = file_put_contents($img_upload_dir,$img_decoder);
    if($flag)
    {
           
        DB::table('post')
        ->insert([
            'titulo'=>$titulo,
            'imagen'=> $imagen_name,
            'descripcion'=>$descripcion,
        ]);

    return json_encode([
        'status'=>"success $imagen}",
        'error'=>false,
        'datos'=>null
    ]);
    }else{
        return json_encode([
            'status'=>"error $imagen}",
            'error'=>true,
            'datos'=>null
        ]);
    }

   
    }



    public function actualizarPost(Request $request)
    {
        $id=$request->id;
        $titulo=$request->titulo;
        $imagen=$request->imagen;
        $descripcion=$request->descripcion;

        $id=$request->get('id');
        $post = DB::table('post')
          ->where('id',$id)
          ->first();
          $imgBorrar = $post->imagen;


    
        if(strlen($imagen) > 0)
        $img_decoder = base64_decode($imagen);
        else return  json_encode([
            'status'=>"error en al decodificar base64 }",
            'error'=>true,
            'datos'=>null
        ]);

        $imagen_name = date("His") . '-.jpg';
        $path = public_path() . '/uploads/images/';
        $img_upload_dir = "$path"."$imagen_name";
        
        $flag = file_put_contents($img_upload_dir,$img_decoder);
        if($flag)
        {
            //borrar 
            if($imagen_name > 0)
            {
                $path =  public_path()."/uploads/images/"."$imagen_name";
                if(file_exists($path)){
                    
                DB::table('post')
                    ->where('id',$id)
                    ->update([
                        'titulo'=>$titulo,
                        'imagen'=>$imagen_name,
                        'descripcion'=>$descripcion,
                    ]);
                    
                    //para borrar la imagen vieja
                    $path =  public_path()."/uploads/images/"."$imgBorrar";
                    if(file_exists($path))
                     unlink($path);

                     return json_encode([
                        'status'=>"success actualizado correcto--$path",
                        'error'=>null,
                        'datos'=>null
                ]);
                }else{
                    return json_encode([
                        'status'=>"error no existe fichero  path ${path}",
                        'error'=>true,
                        'datos'=>null
                    ]);
                }
               
            }else{
                
                return json_encode([
                    'status'=>"error al querer borrar imagen",
                    'error'=>true,
                    'datos'=>null
                ]);
            }

        }else{
            return json_encode([
                'status'=>"error en flag }",
                'error'=>true,
                'datos'=>null
            ]);
        }
    

        /////////////////////////////////////////
        // $id=$request->id;

        // $titulo=$request->titulo;
        // // $imagen=$request->imagen;
        // $descripcion=$request->descripcion;
    
        //               //subir imagen
        //               if(!$request->hasFile('imagen')) {
        //                 return response()->json(['upload_file_not_found'], 400);
        //             }
        //             $file = $request->file('imagen');
        //             if(!$file->isValid()) {
        //                 return response()->json(['invalid_file_upload'], 400);
        //             }
        //             $path = public_path() . '/uploads/images/';
        //             $filename = $file->getClientOriginalName();
        //             $filename = pathinfo($filename,PATHINFO_BASENAME);
        //             $name_file  = str_replace(" " , "_" , $filename);
        //             $extension = $file->getClientOriginalExtension();
            
        //             $imagen = date("His") . '-' . $name_file;
            
        //             $file->move($path, $imagen);


        // DB::table('post')
        //     ->where('id',$id)
        //     ->update([
        //         'titulo'=>$titulo,
        //         'imagen'=>$imagen,
        //         'descripcion'=>$descripcion,
        //     ]);
        // return json_encode([
        //     'status'=>"success datos subidos correctamente $imagen",
        //     'error'=>false,
        //     'datos'=>null
        // ]);

    }


    public function eliminarPost(Request $request)
    {
        $id=$request->get('id');
        $post = DB::table('post')
          ->where('id',$id)
          ->first();
          $img = $post->imagen;



        if($img > 0 && $id > 0)
        {
            $path =  public_path()."/uploads/images/"."$img";
            if(file_exists($path)){
                
             
                
                 DB::table('post')->delete($id);
                 unlink($path);
                 return json_encode([
                    'status'=>"success --$path",
                    'error'=>null,
                    'datos'=>null
            ]);
            }else{
                return json_encode([
                    'status'=>"error no existe fichero  path ${path}",
                    'error'=>true,
                    'datos'=>null
                ]);
            }
           
        }else{
            
            return json_encode([
                'status'=>"error al querer borrar imagen",
                'error'=>true,
                'datos'=>null
            ]);
        }
      
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
