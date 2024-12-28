<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RoleController extends Controller
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
    return view('roles.index');
  }

  public function ajax(Request $request)
  {
    $roles = Role::all();
    $data = [];
    foreach ($roles as $role) {
      $actions = '<div class="text-nowrap">';
      $actions .= '<a class="btn btn-sm btn-icon btn-flat btn-primary" title="Editar" href="'.route('roles.edit', $role->id).'"><i class="icon wb-pencil"></i></a>';
      $actions .= '<button class="btn btn-sm btn-icon btn-flat btn-danger btn-delete" title="Excluir" data-id="'.$role->id.'"><i class="icon wb-trash"></i></button>';
      $actions .= '</div>';
      $data[] = [
        'name' => $role->name,
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
    $permissions = Permission::where('group', '<>', 'Menu')->orderBy('position')->get();
    $permissions = $permissions->groupBy(['group', 'feature']);
    return view('roles.create')->with([
      'permissions' => $permissions,
    ]);
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
    ]);

    $role = new Role;
    $role->name = $request->name;
    $role->save();

    $role->syncPermissions($request->permissions);
    //Definindo permissões de menu
    $permissions = Permission::where('group', 'Menu')->get();
    foreach ($permissions as $permission) {
      if ($role->permissions()->where('group', $permission->name)->count() > 0) {
        $role->givePermissionTo($permission);
      }
    }

    return redirect()->route('roles.index');
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    $role = Role::findOrFail($id);
    return view('roles.show')->with([
      'role' => $role,
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
    $permissions = Permission::where('group', '<>', 'Menu')->orderBy('position')->get();
    $permissions = $permissions->groupBy(['group', 'feature']);
    $role = Role::findOrFail($id);
    return view('roles.edit')->with([
      'role' => $role,
      'permissions' => $permissions,
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
    ]);

    $role = Role::findOrFail($id);
    $role->name = $request->name;
    $role->save();

    $role->syncPermissions($request->permissions);
    //Definindo permissões de menu
    $permissions = Permission::where('group', 'Menu')->get();
    foreach ($permissions as $permission) {
      if ($role->permissions()->where('group', $permission->name)->count() > 0) {
        $role->givePermissionTo($permission);
      }
    }

    return redirect()->route('roles.index');
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    $role = Role::findOrFail($id);
    if ($role->delete()) {
      return response(200);
    }
  }

  
}
