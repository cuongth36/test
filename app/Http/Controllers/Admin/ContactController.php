<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contact;
use Illuminate\Support\Facades\Auth;
class ContactController extends Controller
{
    function __construct()
    {
        $this->middleware(function($request,$next){
            session(['module_active' => 'customer']);
            return $next($request);
        });
    }

    function list(Request $request){
        $status = $request->input('status');
        $list_action =['delete' => 'Xóa tạm thời'];
        if($status == 'trash'){
            $list_action = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn',
            ];
            $contacts = Contact::onlyTrashed()->paginate(10);
        }else if ($status == 'pendding'){
            $list_action = [
                'approve' => 'Phê duyệt',
                'delete' => 'Xóa tạm thời',
            ];
            $contacts = Contact::where('status', '=', '0')->paginate(10);
        }else{
            $contacts = Contact::where('status', '=', '1')->paginate(10);
        }
        $count_contact_active = Contact::all()->where('status','=', '1')->count();
        $count_contact_trash = Contact::onlyTrashed()->count();
        $count_contact_pedding = Contact::all()->where('status', '=', '0')->count();
        $count = [$count_contact_active,$count_contact_pedding, $count_contact_trash];
        return view('admin.customer.feedback-list', compact('contacts', 'list_action', 'count'));
    }

    function edit($id){
        $contact = Contact::find($id);
        return view('admin.customer.feedback-edit', compact('contact'));
    }

    function update(Request $request, $id){
        Contact::where('id', $id)->update([
            'status' => $request->input('status'),
            'user_id' => Auth::id()
        ]);
      return redirect('admin/customer/feedback')->with('status', 'Bạn đã cập nhập thành công');
    }

    function search(Request $request){
        $keyword = '';
        if($request->input('keyword')){
            $keyword = $request->input('keyword');
            $contacts= Contact::where('name', 'LIKE', "%{$keyword}%")->paginate(10);
        }else{
            $contacts= Contact::where('name', 'LIKE', "%{$keyword}%")->paginate(10);
        }
        return view('admin.customer.feedback-pagination', compact('contacts'));
    }



    function pagination(Request $request){
        if($request->ajax()){
            if(!$request['status']){
                $contacts = Contact::where('status', '=', '1')->paginate(10);
            }else{
                if($request['status'] == 'pendding'){
                    $contacts = Contact::where('status', '=', '0')->paginate(10);
                  }else if($request['status'] == 'active'){
                   $contacts = Contact::where('status', '=', '1')->paginate(10);
                  }else{
                    $contacts = Contact::onlyTrashed()->paginate(10);
                 }
            }
            return view('admin.customer.feedback-pagination',compact('contacts'))->render();
        }
    }

    function delete($id){
        $contact = Contact::find($id);
        $contact->delete();
        return response()->json(
            [
                'status' => 1,
                'id' => $id,
                'message' => '<div class=" alert alert-success">'. 'Bạn đã xóa bản ghi vào thùng rác thành công'. '</div>',
                'contact' => $contact
            ]);
    }

    function action(Request $request){
        $action = $request['infoAction'];
        $list_check = $request['id'];
        if($action == 'approve'){
           $contact =  Contact::whereIn('id', $list_check)->update([
                 'status' => '1'
             ]);
         return response()->json([
            'status' => 1,
            'id' => $list_check,
            'message' => '<div class="alert alert-success">' .'Bạn đã phê duyệt bản ghi thành công' . '</div>', 
            'contact' => $contact
        ]);
        }else if($action == 'delete'){
            $contact =  Contact::destroy($list_check);
            return response()->json([
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã xóa bản ghi vào thùng rác thành công' . '</div>', 
                'contact' => $contact
            ]);
        }else if($action == 'restore'){
           
            $contact =  Contact::withTrashed()->whereIn('id', $list_check)->restore();
            return response()->json([
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã khôi phục bản ghi thành công' . '</div>', 
                'contact' => $contact
            ]);
        }else{
            $contact =  Contact::whereIn('id', $list_check);
            $contact ->forceDelete();
            return response()->json([
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã xóa bản ghi vĩnh viễn thành công' . '</div>', 
                'contact' => $contact
            ]);
        }
     }
}
