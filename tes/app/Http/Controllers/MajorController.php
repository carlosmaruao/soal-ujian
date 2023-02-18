<?php

namespace App\Http\Controllers;

use App\Major;
use Illuminate\Http\Request; 
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Validator;
use App\Question;
use Illuminate\Support\Facades\DB;

class MajorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.major.index');
    }

    public function majorDataTables(Request $request)
    {
        if ($request->ajax()) {
            $data = Major::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->title;
                })
                ->addColumn('initial', function ($data) {
                    return $data->initial;
                })
                ->addColumn('aktif', function ($data) {

                    if($data->active == 1){
                        $xx = \URL::to('').'/images/ok.png';
                    $actived = '<a id="btnAktif" data-toggle="modal" data-id=' . $data->id . '>
                    <img src="'.$xx.'" alt=""></a>';
                    }else{
                        $xx = \URL::to('').'/images/cancel.png';
                    $actived = '<a id="btnAktif" data-toggle="modal" data-id=' . $data->id . '>
                    <img src="'.$xx.'" alt=""></a>';
                    } 
                    $action = $actived;

                    return $action;
                })
                ->addColumn('action', function ($data) {
                    $detail = '<a class="btn btn-sm btn-outline-info" id="data-modal" data-toggle="modal" data-id=' . $data->id . '>Edit</a>';
 
                    $action =  $detail;

                    return $action;
                })
                ->rawColumns(['name', 'initial','aktif', 'action'])
                ->make(true);
        }
        return view('admin.users.index');
    }

    public function show($id)
    {
        $where = array('id' => $id);
        $data = Major::where($where)->first(); 
        return response()->json(['xData' => $data]);
    }

    public function aktifData($id)
    {
        $where = array('id' => $id);
        $setMajor = Major::where($where)->first(); 
        if($setMajor->active == 1){
            $aktif = 0;
        }else{
            $aktif = 1;
        }
        
        $setMajor->update([
            'active' => $aktif, 
        ]);       
 
        DB::table('questions')->where('major_id', $id)->update(['active' => $aktif]); 
        
        return response()->json(['xData' => 'sukses']);
    }    

    public function tambahMajor(Request $request)
    {
        $rules = array(
            'forMajor'  =>  'required|min:3|max:200', 
            'forInitial'  =>  'required|min:2|max:10', 
        );  

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()]);
        }
        $setMajor = [
            'title' => $request->forMajor,
            'initial' => $request->forInitial,
            'slug' => Str::slug($request->forMajor),
        ];
        Major::create($setMajor);
        return response()->json(['xData' => 'Data Berhasil diUpdate']);
    }

    public function updateMajor(Request $request)
    {
        $rules = array(
            'forMajor'  =>  'required|min:3|max:200', 
            'forInitial'  =>  'required|min:2|max:10', 
        ); 

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()]);
        }

        $setMajor = Major::where('id', $request->major_id)->first();
        $setMajor->update([
            'title' => $request->forMajor,
            'initial' => $request->forInitial,
            'slug' => Str::slug($request->forMajor),
        ]); 
        return response()->json(['xData' => 'Data Berhasil diUpdate']);
    }

    public function deleteMajor($id)
    {
        $where = array('id' => $id);
        // delete major 
        $delMajor = Major::where($where)->first();  
        $delMajor->delete();

        // delete Question 
        Question::where('major_id', $id)->delete();  
 
        return response()->json(['xData' => 'Data berhasil di hapus']);
    }
}
