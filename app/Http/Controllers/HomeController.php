<?php

namespace App\Http\Controllers;

use App\PassagemPeca;
use App\ProcessoTanque;
use App\TanqueCiclo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class HomeController extends Controller
{
  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
  * Show the application dashboard.
  *
  * @return \Illuminate\Contracts\Support\Renderable
  */
  public function index()
  {
    return view('dashboard.main');
  }

  /**
  * Generate image thumbnail in cache.
  *
  * @return \Illuminate\Http\Response
  */
  public function thumbnail(Request $request)
  {
    $src = $request->query('src');
    $width = $request->query('width');
    $height = $request->query('height');

    $cacheimage = Image::cache(function($image) use ($src, $width, $height) {
      return $image->make($src)->resize($width, $height, function ($constraint) {
        $constraint->aspectRatio();
      });
    }, 10, false); // one minute cache expiry

    return response()->make($cacheimage, 200, array('Content-Type' => 'image/jpeg'));
  }

  public function webcam()
  {
    return view('dashboard.webcam');
  }

  public function upload(Request $request)
  {
    //Alternativa 1:
    $filename = Storage::disk('public')->put('snapshots', $request->file('snapshot'));
    $path = Storage::url($filename);
    return response()->json([
      'path' => $path,
      'filename' => $filename
    ]);

    //Alternativa 2:
    /*$filecontent = $request->get('data_uri');
    $fileext = $request->get('image_fmt');
    $name = "snapshot-".time().".$fileext";
    $image = Image::make(file_get_contents($filecontent));
    $newimage = $image->encode($fileext);
    $filename = Storage::disk('public')->put('snapshots/'.$name, $newimage->__toString());
    return response()->json([
      'filename' => $filename
    ]);*/

  }

  /**
   * 
   * APAGAR APÓS ATUALIZAR
   * 
   */
  public function restaurar()
  {
    $count = 0;
    $passagens = PassagemPeca::where('data_servico', '>=', '2020-06-29')->get();

    foreach ($passagens as $passagem) {
      $processos = ProcessoTanque::orderBy('id');
      if ($passagem->tiposervico_id) {
        $processos->where('tiposervico_id', $passagem->tiposervico_id);
      }
      if ($passagem->material_id) {
        $processos->where('material_id', $passagem->material_id);
      }
      if ($passagem->cor_id) {
        $processos->where('cor_id', $passagem->cor_id);
      }
      if ($passagem->milesimos) {
        $processos->where('mil_ini', '<=', $passagem->milesimos)
                  ->where('mil_fim', '>=', $passagem->milesimos);
      }
      $processos = $processos->get();
      foreach ($processos as $processo) {
        $ciclo_existe = TanqueCiclo::where('tanque_id', $processo->tanque_id)
                                   ->where('cliente_id', $passagem->cliente_id)
                                   ->where('tiposervico_id', $passagem->tiposervico_id)
                                   ->where('material_id', $passagem->material_id)
                                   ->where('milesimos', $passagem->milesimos)
                                   ->where('data_servico', $passagem->data_servico)
                                   ->get();

        if ($ciclo_existe->count() == 0) {
          $count++;

          $ciclo = new TanqueCiclo;

          if ($processo->tanque->tipo_consumo == 'M') {
            $NPeso = $passagem->peso;
            $NMilesimos = $passagem->milesimos;
            $peso_consumido = ($NPeso * $NMilesimos) / 1000;
            $ciclo->peso = $peso_consumido;
          } else {
            $fat = $processo->fator ?? 1;
            $ciclo->peso = $passagem->peso * $fat;
          }

          $ciclo->tanque_id = $processo->tanque_id;
          $ciclo->cliente_id = $passagem->cliente_id;
          $ciclo->tiposervico_id = $passagem->tiposervico_id;
          $ciclo->material_id = $passagem->material_id;
          $ciclo->cor_id = $passagem->cor_id;
          $ciclo->milesimos = $passagem->milesimos;
          $ciclo->data_servico = $passagem->created_at;
          $ciclo->status = 'P';
          $ciclo->save();

        }


      } // end processos

    } // end passagens

    return response()->json($count);
    
  } // end restaurar

}
