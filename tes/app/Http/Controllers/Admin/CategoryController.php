<?php

namespace App\Http\Controllers\Admin;
use App\Category;
use App\Major;
use App\Question;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Builder;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.soal.index');
    }

    public function soalDataTables(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::with('questions')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('total', function ($data) {
                    return count($data->activeQuestion);
                })
                ->addColumn('action', function ($data) {
                    $detail = '<a class="btn btn-sm btn-outline-info" id="data-modal" data-toggle="modal" data-id=' . $data->id . '>Detail</a>';
                    $action = $detail;

                    return $action;
                })
                ->rawColumns(['name', 'total', 'action'])
                ->make(true);
        }
        return view('admin.users.index');
    }

    public function create()
    {
    }
    public function store(Request $request)
    {
    }
    public function show($id)
    {
        $where = array('id' => $id);
        $data = Category::where($where)->first();
        $totalMajor = Major::where('active', 1)->get();

        $quest  = [];
        $major_aktif  = [];
        // hitung total major yg aktif 
        for ($i=0; $i < count($totalMajor); $i++) { 
            $idMjr = $totalMajor[$i]['id'];
            array_push($major_aktif, $idMjr);

            // looping             
            $fileQuest  = Question::where('major_id', $idMjr)->where('category_id', $id)->get();
            if(count($fileQuest)){
                for ($x=0; $x < count($fileQuest); $x++) {  
                    array_push($quest, $fileQuest[$x]);
                }
            }
        }
        return response()->json([
            'xData' => $data, 
            'totalMajor' => count($totalMajor), 
            'dataQuest' => $quest,
            'major_aktif' => $major_aktif 
            ]);
    }
    public function edit(Category $category)
    {
    }

    public function update(Request $request, Category $category)
    {
    }
    public function destroy(Category $category)
    {
    }
    public function totalMajor()
    {
        $totalMajor = Major::where('active', 1)->get(); 
        
        $major_aktif  = [];
        // hitung total major yg aktif 
        for ($i=0; $i < count($totalMajor); $i++) { 
            $idMjr = $totalMajor[$i]['id'];
            array_push($major_aktif, $idMjr); 
        }
        
        return response()->json([ 
            'totalMajor' => count($totalMajor),  
            'major_aktif' => $major_aktif 
            ]);

    }
}
