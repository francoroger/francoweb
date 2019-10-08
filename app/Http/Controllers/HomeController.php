<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;

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


}
