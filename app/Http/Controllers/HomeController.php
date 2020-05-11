<?php

namespace App\Http\Controllers;

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
    return redirect()->route('catalogacao_checklist.index');
    //return view('dashboard.main');
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
