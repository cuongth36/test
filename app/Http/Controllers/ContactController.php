<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
class ContactController extends Controller
{
    function show(){
        return view('pages.contact.list');
    }

    function store(Request $request){
      $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required',
        'title' => 'required|string|max:255',
        'content' => 'required|string|max:255'
      ],
      [
          'required' => ':attribute không được bỏ trống',
          'name.max' => 'Họ tên tối đa 255 ký tự',
          'title.max' => 'Tiêu đề tối đa 255 ký tự',
          'content.max' => 'Nội dung tối đa 255 ký tự',
      ],
      [
          'name'    => 'Họ tên',
          'title'   =>  'Tiêu đề',
          'content' =>  'Nội dung'
      ]);

      Contact::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'title' => $request->input('title'),
        'content' => $request->input('content'),
      ]);

      return redirect('lien-he.html')->with('success', 'Bạn đã gửi thông tin thành công!');
    }
}
