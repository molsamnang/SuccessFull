<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LangController extends Controller
{
     public function setlocale($lang)
    {
        if (in_array($lang, ['en', 'kh'])) {
            Session::put('locale', $lang);
        }
        return redirect()->back();
    }
}
