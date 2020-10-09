<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Menu;
class MenuController extends Controller
{
    // function show(){
    //     $menus = Menu::where('parent_id', '=', '0')->orderBy('menu_sort', 'asc')->get();
    //     return view ('header.header', compact('menus'));
    // }
}
