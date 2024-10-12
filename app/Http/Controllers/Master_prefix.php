<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\master_document;
use App\Models\log_company;
use Carbon\Carbon;
use Auth;
use App\Models\User;
class Master_prefix extends Controller
{
    public function index($menu)
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $prefix = master_document::query()->Where('Category','Mprename')->paginate($perPage);
        $exp = explode('.', $menu);
        if (count($exp) > 1) {
            $search = $exp[1];
            if ($search == "all") {
                $prefix = master_document::query()
                ->Where('Category','Mprename')
                ->paginate($perPage);
            }elseif ($search == 'ac') {
                $prefix = master_document::query()
                ->Where('Category','Mprename')
                ->where('status', 1)
                ->paginate($perPage);
            }else {
                $prefix = master_document::query()
                ->Where('Category','Mprename')
                ->where('status', 0)
                ->paginate($perPage);
            }
        }
        return view('master_prefix.index',compact('prefix','menu'));
    }
    public function changeStatus($id)
    {
        $M_prefix = master_document::find($id);
        if ($M_prefix->status == 1 ) {
            $status = 0;
            $M_prefix->status = $status;
        }elseif (($M_prefix->status == 0 )) {
            $status = 1;
            $M_prefix->status = $status;
        }
        $M_prefix->save();
    }

    public function save(Request $request)
    {
        try {
            $data = $request->all();
            $userid = Auth::user()->id;
            $Mprefix =  "Mprename" ;
            $save = new master_document();
            $save->Category= $Mprefix;
            $save->name_th = $request->name_th;
            $save->name_en = $request->name_en;
            $save->created_by = $userid;
            $save->save();
        } catch (\Throwable  $e) {
            return redirect()->route('Mprefix','index')->with('error', $e->getMessage());
        }
        try {
            //log
            $nameth = 'ชื่อภาษาไทย : '.$request->name_th;
            $nameen = 'ชื่อภาษาอังกฤษ : '.$request->name_en;
            $datacompany = '';
            $variables = [$nameth, $nameen];
            // รวม $formattedProductDataString เข้าไปใน $variables
            foreach ($variables as $variable) {
                if (!empty($variable)) {
                    if (!empty($datacompany)) {
                        $datacompany .= ' + ';
                    }
                    $datacompany .= $variable;
                }
            }
            $userid = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userid;
            $save->Company_ID = 'Master Prename';
            $save->type = 'Create';
            $save->Category = 'Create :: Master Prename';
            $save->content =$datacompany;
            $save->save();

        } catch (\Throwable  $e) {
            return redirect()->route('Mprefix','index')->with('error', $e->getMessage());
        }
        return redirect()->route('Mprefix','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function update($id,$datakey,$dataEN)
    {
        try {
            $userid = Auth::user()->id;
            $save = master_document::find($id);
            $save->name_th = $datakey;
            $save->name_en = $dataEN;
            $save->created_by = $userid;
            $save->save();
        } catch (\Throwable  $e) {
            return redirect()->route('Mprefix','index')->with('error', $e->getMessage());
        }
        try {
            $nameth = null;
            if ($datakey) {
                $nameth = 'ชื่อภาษาไทย : '.$datakey;
            }
            $nameen = null;
            if ($datakey) {
                $nameen = 'ชื่อภาษาอังกฤษ : '.$dataEN;
            }
            $datacompany = '';
            $variables = [$nameth, $nameen];
            // รวม $formattedProductDataString เข้าไปใน $variables
            foreach ($variables as $variable) {
                if (!empty($variable)) {
                    if (!empty($datacompany)) {
                        $datacompany .= ' + ';
                    }
                    $datacompany .= $variable;
                }
            }
            $userid = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userid;
            $save->Company_ID = 'Master Prename';
            $save->type = 'Edit';
            $save->Category = 'Edit :: Master Prename';
            $save->content =$datacompany;
            $save->save();

        } catch (\Throwable  $e) {
            return redirect()->route('Mprefix','index')->with('error', $e->getMessage());
        }
        return redirect()->route('Mprefix','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function edit($id)
    {
        $data = master_document::find($id);
        return response()->json(['data' => $data]);
    }
    public function  searchMprename($datakey)
    {
        $data = master_document::where('name_th',$datakey)->first();
        return response()->json($data);
    }
    public function  dupicateMprename($id,$datakey)
    {
        $data = master_document::where('id',$id)->where('name_th',$datakey)->Where('Category','Mprename')->first();
        return response()->json(['data' => $data]);
    }

    public function log(){
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $log = log_company::where('Company_ID', 'Master Prename')
        ->orderBy('updated_at', 'desc')
        ->paginate($perPage);
        return view('master_prefix.log',compact('log'));
    }

    public function prename_search_table(Request $request){
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;
        $permissionid = Auth::user()->permission;
        if ($search_value) {
            $data_query = master_document::Where('Category','Mprename')->Where('name_th', 'LIKE', '%'.$search_value.'%')
            ->orWhere('name_en', 'LIKE', '%'.$search_value.'%')
            ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = master_document::query()->where('Category','Mprename')->paginate($perPageS);
        }
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";
                $view ="";
                if ($value->status == 1) {
                    $btn_status = '<button type="button" class="btn btn-light-success btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ใช้งาน</button>';
                } else {
                    $btn_status = '<button type="button" class="btn btn-light-danger btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ปิดใช้งาน</button>';
                }

                $path = 'promotion/';
                $btn_action = '<div class="dropdown">';
                $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';
                $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="view_detail('.$value->id.')" data-bs-toggle="modal" data-bs-target="#QuantityCreate">View</a></li>';
                $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="edit('.$value->id.')" data-bs-toggle="modal" data-bs-target="#QuantityCreate">Edit</a></li>';
                $btn_action .= '</ul>';
                $btn_action .= '</div>';

                $data[] = [
                    'number' => ($key + 1) ,
                    'nameth' => $value->name_th,
                    'nameen' => $value->name_en,
                    'status' => $btn_status,
                    'btn_action' => $btn_action,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    public function prename_paginate_table(Request $request){
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;
        if ($perPage == 10) {
            $data_query = master_document::query()->where('Category','Mprename')->limit($request->page.'0')
            ->get();
        } else {
            $data_query = master_document::query()->where('Category','Mprename')->paginate($perPage);
        }


        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";

                // สร้าง dropdown สำหรับการทำรายการ
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                    if ($value->status == 1) {
                        $btn_status = '<button type="button" class="btn btn-light-success btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ใช้งาน</button>';
                    } else {
                        $btn_status = '<button type="button" class="btn btn-light-danger btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ปิดใช้งาน</button>';
                    }

                    $path = 'promotion/';
                    $btn_action = '<div class="dropdown">';
                    $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                    $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="view_detail('.$value->id.')" data-bs-toggle="modal" data-bs-target="#QuantityCreate">View</a></li>';
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="edit('.$value->id.')" data-bs-toggle="modal" data-bs-target="#QuantityCreate">Edit</a></li>';
                    $btn_action .= '</ul>';
                    $btn_action .= '</div>';

                    $data[] = [
                        'number' => ($key + 1) ,
                        'nameth' => $value->name_th,
                        'nameen' => $value->name_en,
                        'status' => $btn_status,
                        'btn_action' => $btn_action,
                    ];
                }
            }
        }
        // dd($data);
        return response()->json([
            'data' => $data,
        ]);
    }

    public function prename_search_table_paginate_log(Request $request){
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;

        if ($search_value) {
            $data_query = log_company::where('created_at', 'LIKE', '%'.$search_value.'%')
                ->where('Company_ID','Master Prename')
                ->orderBy('updated_at', 'desc')
                ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = log_company::where('Company_ID', 'Master Prename')->orderBy('updated_at', 'desc')->paginate($perPageS);
        }
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $contentArray = explode('+', $value->content);
                $content = implode('</br>', $contentArray);
                $Category = '<b style="color:#0000FF ">' . $value->Category . '</b>';
                $name = $Category.'</br>'.$content;
                $data[] = [
                    'number' => $key + 1,
                    'Category'=>$value->Category,
                    'type'=>$value->type,
                    'Created_by'=>@$value->userOperated->name,
                    'created_at' => \Carbon\Carbon::parse($value->created_at)->format('d/m/Y'),
                    'Content' => $name,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    public function prename_paginate_log_table(Request $request){
        $perPage = (int)$request->perPage;
        $guest_profile = $request->guest_profile;


        if ($perPage == 10) {
            $data_query = log_company::where('Company_ID', 'Master Prename')->orderBy('updated_at', 'desc')->limit($request->page.'0')->get();
        } else {
            $data_query = log_company::where('Company_ID', 'Master Prename')->orderBy('updated_at', 'desc')->paginate($perPage);
        }
        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $contentArray = explode('+', $value->content);
                $content = implode('</br>', $contentArray);
                $Category = '<b style="color:#0000FF ">' . $value->Category . '</b>';
                $name = $Category.'</br>'.$content;
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                    $data[] = [
                        'number' => $key + 1,
                        'Category'=>$value->Category,
                        'type'=>$value->type,
                        'Created_by'=>@$value->userOperated->name,
                        'created_at' => \Carbon\Carbon::parse($value->created_at)->format('d/m/Y'),
                        'Content' => $name,
                    ];
                }
            }
        }
        return response()->json([
            'data' => $data,
        ]);

    }
}
