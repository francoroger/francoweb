<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use Storage;

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
    return redirect()->route('catalogacao_checklist.index');
    //return view('dashboard.main');
  }

  public function painel()
  {
    $catalogacoes = \App\Catalogacao::where('status', 'A')
                                    ->whereNotNull('idcliente')
                                    ->orderBy('datacad', 'desc')
                                    ->get();

    $ordens = \App\Catalogacao::where('status', 'F')
                              ->whereNotNull('idcliente')
                              ->orderBy('datacad', 'desc')
                              ->get();

    $revisoes = \App\Catalogacao::whereIn('status', ['P', 'G'])
                                ->whereNotNull('idcliente')
                                ->orderBy('datacad', 'desc')
                                ->get();

    $expedicoes = \App\Catalogacao::where('status', 'C')
                                 ->whereNotNull('idcliente')
                                 ->orderBy('datacad', 'desc')
                                 ->take(30)
                                 ->get();

    $concluidos = \App\Catalogacao::where('status', 'L')
                                ->whereNotNull('idcliente')
                                ->orderBy('datacad', 'desc')
                                ->take(30)
                                ->get();

    return view('dashboard.painel')->with([
      'catalogacoes' => $catalogacoes,
      'ordens' => $ordens,
      'revisoes' => $revisoes,
      'expedicoes' => $expedicoes,
      'concluidos' => $concluidos,
    ]);
  }

  public function reforco()
  {
    $tanques = \App\Tanque::whereNotNull('ciclo_reforco')->orderBy('pos')->get();

    $clientes = \App\Cliente::select(['id', 'nome', 'rzsc', 'ativo'])->orderBy('rzsc')->get();

    $tiposServico = \App\TipoServico::whereHas('processos_tanque')->orderBy('descricao')->get();
    $materiais = \App\Material::whereHas('processos_tanque')->orderBy('pos')->get();
    return view('dashboard.reforco')->with([
      'tanques' => $tanques,
      'tiposServico' => $tiposServico,
      'materiais' => $materiais,
      'clientes' => $clientes,
    ]);
  }

  public function consulta_reforco(Request $request)
  {
    $passagens = \App\PassagemPeca::latest('created_at');
    $passagens = $passagens->paginate(10);

    if ($request->ajax()) {
      return response()->json([
        'view' => view('consulta_reforco.data', compact('passagens'))->render()
      ]);
    } else {
      return view('consulta_reforco.index')->with([
        'passagens' => $passagens
      ]);
    }
  }

  public function destroy_reforco($id)
  {
    $passagem = \App\PassagemPeca::findOrFail($id);

    $processos = \App\ProcessoTanque::orderBy('id');

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

    foreach ($processos as $proc) {
      $fat = $proc->fator ?? 1;
      $peso = $passagem->peso * $fat;

      $ciclo = \App\TanqueCiclo::where('tanque_id', $proc->tanque_id)
                          ->where('cliente_id', $passagem->cliente_id)
                          ->where('tiposervico_id', $passagem->tiposervico_id)
                          ->where('material_id', $passagem->material_id)
                          ->where('peso', $peso)
                          ->get();

      if ($ciclo->count() > 0) {
        $toDel = \App\TanqueCiclo::findOrFail($ciclo->first()->id);
        $toDel->delete();
      }

    }

    if ($passagem->delete()) {
      return response(200);
    }
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


}
