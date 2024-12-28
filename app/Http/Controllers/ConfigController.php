<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ConfigController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('config.index');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request)
  {
    $this->envUpdate('DIA_UTIL_SEGUNDA', filter_var($request->segunda, FILTER_VALIDATE_BOOLEAN));
    $this->envUpdate('DIA_UTIL_SEG_HORA1', $request->seg_hora1);
    $this->envUpdate('DIA_UTIL_SEG_HORA2', $request->seg_hora2);
    $this->envUpdate('DIA_UTIL_SEG_HORA3', $request->seg_hora3);
    $this->envUpdate('DIA_UTIL_SEG_HORA4', $request->seg_hora4);
    $this->envUpdate('DIA_UTIL_TERCA', filter_var($request->terca, FILTER_VALIDATE_BOOLEAN));
    $this->envUpdate('DIA_UTIL_TER_HORA1', $request->ter_hora1);
    $this->envUpdate('DIA_UTIL_TER_HORA2', $request->ter_hora2);
    $this->envUpdate('DIA_UTIL_TER_HORA3', $request->ter_hora3);
    $this->envUpdate('DIA_UTIL_TER_HORA4', $request->ter_hora4);
    $this->envUpdate('DIA_UTIL_QUARTA', filter_var($request->quarta, FILTER_VALIDATE_BOOLEAN));
    $this->envUpdate('DIA_UTIL_QUA_HORA1', $request->qua_hora1);
    $this->envUpdate('DIA_UTIL_QUA_HORA2', $request->qua_hora2);
    $this->envUpdate('DIA_UTIL_QUA_HORA3', $request->qua_hora3);
    $this->envUpdate('DIA_UTIL_QUA_HORA4', $request->qua_hora4);
    $this->envUpdate('DIA_UTIL_QUINTA', filter_var($request->quinta, FILTER_VALIDATE_BOOLEAN));
    $this->envUpdate('DIA_UTIL_QUI_HORA1', $request->qui_hora1);
    $this->envUpdate('DIA_UTIL_QUI_HORA2', $request->qui_hora2);
    $this->envUpdate('DIA_UTIL_QUI_HORA3', $request->qui_hora3);
    $this->envUpdate('DIA_UTIL_QUI_HORA4', $request->qui_hora4);
    $this->envUpdate('DIA_UTIL_SEXTA', filter_var($request->sexta, FILTER_VALIDATE_BOOLEAN));
    $this->envUpdate('DIA_UTIL_SEX_HORA1', $request->sex_hora1);
    $this->envUpdate('DIA_UTIL_SEX_HORA2', $request->sex_hora2);
    $this->envUpdate('DIA_UTIL_SEX_HORA3', $request->sex_hora3);
    $this->envUpdate('DIA_UTIL_SEX_HORA4', $request->sex_hora4);
    $this->envUpdate('DIA_UTIL_SABADO', filter_var($request->sabado, FILTER_VALIDATE_BOOLEAN));
    $this->envUpdate('DIA_UTIL_SAB_HORA1', $request->sab_hora1);
    $this->envUpdate('DIA_UTIL_SAB_HORA2', $request->sab_hora2);
    $this->envUpdate('DIA_UTIL_SAB_HORA3', $request->sab_hora3);
    $this->envUpdate('DIA_UTIL_SAB_HORA4', $request->sab_hora4);
    $this->envUpdate('DIA_UTIL_DOMINGO', filter_var($request->domingo, FILTER_VALIDATE_BOOLEAN));
    $this->envUpdate('DIA_UTIL_DOM_HORA1', $request->dom_hora1);
    $this->envUpdate('DIA_UTIL_DOM_HORA2', $request->dom_hora2);
    $this->envUpdate('DIA_UTIL_DOM_HORA3', $request->dom_hora3);
    $this->envUpdate('DIA_UTIL_DOM_HORA4', $request->dom_hora4);

    Artisan::call('config:clear');

    return redirect()->route('home');
  }

  public function envUpdate($key, $value)
  {
    $path = base_path('.env');

    if (strpos($value, ' ') || strpos($value, '/')) {
      $new_value = '\'' . $value . '\'';
    } else {
      $new_value = $value;
    }

    if (strpos(env($key), ' ') || strpos(env($key), '/')) {
      $old_value = '\'' . env($key) . '\'';
    } else {
      $old_value = env($key);
    }

    if (file_exists($path)) {
      file_put_contents($path, str_replace(
        $key . '=' . $old_value, 
        $key . '=' . $new_value, 
        file_get_contents($path)
      ));
    }
  }
}
