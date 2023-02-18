<?php

namespace App\Http\Controllers\Admin;

use App\Info;
use App\User;
use App\Major;
use Validator;
use App\Category;
use App\Question;
use App\Exports\UserExport;
use Illuminate\Support\Str;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Skor;
use Maatwebsite\Excel\Facades\Excel;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::orderBy('name', 'Asc')->get();
        return view('admin.users.index', ['users' => $users]);
    }

    public function updateData(Request $request)
    {
        $r = $request->validate([
            'name' => 'required',
            'username' => 'required',
            'kelas' => 'required',
        ]);
        $id = $request->user_id;
        $setStatus = [
            'name' => $request->name,
            'kelas' => $request->kelas,
            'username' => $request->username
        ];
        User::whereId($id)->update($setStatus);
        // $uId = $request->user_id;
        // User::updateOrCreate(['id' => $uId], ['name' => $request->name, 'email' => $request->email]);
        // if (empty($request->user_id))
        //     $msg = 'User created successfully.';
        // else
        //     $msg = 'User data is updated successfully';
        return response()->json(['success' => 'Data updated successfully!']);
    }

    public function tambahSoal(Request $request)
    {
        $rules = array(
            'forPertanyaan'  =>  'required|min:3|max:250',
        );
        $totalMajor = Major::where('active', 1)->get();
        $jumlahJurusan = count($totalMajor);

        // mencari id Major yg aktif untuk di jadikan rules
        $major_aktif  = [];
        for ($i = 0; $i < count($totalMajor); $i++) {
            $idMjr = $totalMajor[$i]['id'];
            array_push($major_aktif, $idMjr);
        }

        // rules 
        for ($i = 0; $i < $jumlahJurusan; $i++) {
            $rules['forPilihan' . $major_aktif[$i]] =  'required|min:3|max:250';
        }

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors(), 'dataValid' => $request->all()]);
        }
        $addCategory = [
            'name' => $request->forPertanyaan,
            'slug' => Str::slug($request->forPertanyaan),
        ];
        $kategori = Category::create($addCategory);

        for ($i = 0; $i < $jumlahJurusan; $i++) {
            $subParam = $_REQUEST['forPilihan' . $major_aktif[$i]];

            // $setQuestion = Question::where('category_id', $kategori->id)
            //     ->where('major_id', $major_aktif[$i])->first();
            Question::create([
                'major_id' => $major_aktif[$i],
                'category_id' => $kategori->id,
                'title' => $subParam,
                'slug' => Str::slug($subParam),
            ]);
        }
        return response()->json(['xData' => 'Data Berhasil diUpdate']);
    }

    public function updateSoal(Request $request)
    {
        $rules = array(
            'forPertanyaan'  =>  'required|min:3|max:250',
        );
        $totalMajor = Major::where('active', 1)->get();
        $jumlahJurusan = count($totalMajor);

        // mencari id Major yg aktif untuk di jadikan rules
        $major_aktif  = [];
        for ($i = 0; $i < count($totalMajor); $i++) {
            $idMjr = $totalMajor[$i]['id'];
            array_push($major_aktif, $idMjr);
        }

        for ($i = 0; $i < $jumlahJurusan; $i++) {
            $rules['forPilihan' . $major_aktif[$i]] =  'required|min:3|max:250';
        }


        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()]);
        }

        $setCategory = Category::where('id', $request->pertanyaan_id)->first();
        $setCategory->update([
            'name' => $request->forPertanyaan,
            'slug' => Str::slug($request->forPertanyaan),
        ]);

        for ($i = 0; $i < $jumlahJurusan; $i++) {
            $subParam = $_REQUEST['forPilihan' . $major_aktif[$i]];

            Question::updateOrCreate(
                ['category_id' => $request->pertanyaan_id,  'major_id' => $major_aktif[$i]],
                [
                    'title' => $subParam,
                    'slug' => Str::slug($subParam),
                    'active' => 1
                ]
            );
        }
        return response()->json(['xData' => 'Data Berhasil diUpdate']);
    }

    public function show($id)
    {
        // $where = array('id' => $id);
        // $user = User::where($where)->first();
        // return Response::json(['user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        return response()->json(['data' => $request]);
        //$user->roles()->sync($request->roles);

        // return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // if (Gate::denies('admin-area')) {
        //     return redirect()->route('admin.users.index');
        // }
        $user->roles()->detach();
        $user->delete();
        return redirect()->route('admin.users.index');
    }


    public function viewUpload()
    {
        return view('member.profile.upload');
    }

    public function postUpload(Request $request)
    {

        $this->validate($request, [
            'file' => 'required|mimes:xlsx'
            // 'file' => 'required|mimes:csv,xls,xlsx' 
        ]);
        if ($request->hasFile('file')) {
            ini_set('max_execution_time', 3600);
            ini_set('memory_limit', '2048M');
            // validasi 
            // $file = $request->file('file')->store('filesImport');
            $file = $request->file('file');
            $import = new UsersImport;
            $import->import($file);

            // if ($import->failures()->isNotEmpty()) {
            //     return back()->withFailures($import->failures());
            // } 
            return redirect('admin/users')->with(['success' => 'data berhasil diupdate']);
        } else {
            return redirect('admin/upload');
        }
    }

    public function export_excel()
    {
        return Excel::download(new UserExport, 'user.xlsx');
    }

    public function deleteSoal($id)
    {
        $where = array('id' => $id);
        // delete soal 
        $delSoal = Category::where($where)->first();
        $delSoal->delete();

        // delete Question 
        Question::where('category_id', $id)->delete();

        return response()->json(['xData' => 'Data berhasil di hapus']);
    }
}
