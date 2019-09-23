<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    return view('usuarios.index');
  }

  public function ajax(Request $request)
  {
    $users = User::all();
    $data = [];
    foreach ($users as $user) {
      $actions = '<div class="text-nowrap">';
      $actions .= '<a class="btn btn-sm btn-icon btn-flat btn-primary" title="Editar" href="'.route('usuarios.edit', $user->id).'"><i class="icon wb-pencil"></i></a>';
      $actions .= '<button class="btn btn-sm btn-icon btn-flat btn-danger btn-delete" title="Excluir" data-id="'.$user->id.'"><i class="icon wb-trash"></i></button>';
      $actions .= '</div>';
      $data[] = [
        'name' => $user->name,
        'email' => $user->email,
        'actions' => $actions,
      ];
    }
    $data = [
      'data' => $data
    ];
    return response()->json($data);
  }

  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create()
  {
    return view('usuarios.create');
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:4|confirmed',
    ]);

    User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);

    return redirect()->route('usuarios.index');
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    $usuario = User::findOrFail($id);
    return view('usuarios.show')->with([
      'usuario' => $usuario,
    ]);
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function edit($id)
  {
    $usuario = User::findOrFail($id);
    return view('usuarios.edit')->with([
      'usuario' => $usuario,
    ]);
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
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users,email,'.$id,
      'password' => 'required|string|min:4|confirmed',
    ]);

    $usuario = User::findOrFail($id);
    $usuario->name = $request->name;
    $usuario->email = $request->email;
    $usuario->password = Hash::make($request->password);

    if ($usuario->save()) {
      return redirect()->route('usuarios.index');
    }
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    $usuario = User::findOrFail($id);
    if ($usuario->delete()) {
      return response(200);
    }
  }
}
