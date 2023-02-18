<?php

namespace App\Http\Controllers;

use PDF;
use App\Skor;
use App\User;
use Response;
use App\Info;
use App\Major;
use App\Category;
use App\Question;
use Carbon\Carbon;
use App\Models\Admin\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Validator;


class InfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function resetUser($id)
    {
        // $info = Info::where('user_id', $id)->first();
        // $info = Skor::where('user_id', $id)->get();
        Info::where('user_id', $id)->delete();
        Skor::where('user_id', $id)->delete();
        $setStatus = [
            'status' => null
        ];
        User::whereId($id)->update($setStatus);
        return response()->json(['success' => 'Data berhasil di bersihkan']);
    }

    public function getUserDetail($id)
    {
        $user = User::where('id', $id)->first();
        return response()->json([$user]);
    }

    public function reset(Request $request)
    {
        Info::query()->truncate();
        Skor::query()->truncate();
        User::query()->update(['status' => null]);
        return response()->json(['success' => 'Data berhasil di bersihkan']);
    }

    public function hapusData(Request $request)
    {

        // reset user         
        User::truncate();
        // reset role 
        Role::truncate();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'member']);
        Role::create(['name' => 'manager']);

        DB::table('role_user')->truncate();

        $adminRole = Role::where('name', 'admin')->first();
        $academicRole = Role::where('name', 'manager')->first();

        $passAdmin = 'Admin!#';
        $passAcademic = 'Academic!#';

        $admin = User::create([
            'name' => 'Admin LSPR',
            'username' => 'admin',
            'password' => bcrypt($passAdmin),
            'email_verified_at' => Carbon::now(),
            'email' => $passAdmin . '@gmail.com',
            'thumbnail' => 'images/foto/profile/default.jpg'
        ]);
        $academic = User::create([
            'name' => 'Academic',
            'username' => 'academic',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt($passAcademic),
            'email' => $passAcademic . '@gmail.com',
            'thumbnail' => 'images/foto/profile/default.jpg'
        ]);

        $admin->roles()->attach($adminRole);
        $academic->roles()->attach($academicRole);

        Info::query()->truncate();
        Skor::query()->truncate();

        auth()->logout();
        Session()->flush();

        $request->session()->invalidate();
        return redirect('/login');
    }

    public function showInfo()
    {
        $status = Auth::user()->status;
        $idUser = Auth::user()->id;

        if ($status > 0) {
            $info = Info::where('user_id', $idUser)->first();
            $infos['fr_jurusan'] = $info->major_id;
            $infos['fr_quest1'] = $info->info1;
            $infos['fr_quest2'] = $info->info2;
            $infos['fr_quest3'] = $info->info3;
            $infos['nm_jurusan'] = $info->major->title;
        } else {
            $infos['fr_jurusan'] = '';
            $infos['fr_quest1'] = '';
            $infos['fr_quest2'] = '';
            $infos['fr_quest3'] = '';
            $infos['nm_jurusan'] = '';
        }
        $infos['status'] = $status;

        // cek foto 
        $file = Auth::user()->thumbnail;
        if (file_exists($file)) {
            $infos['foto'] = $file;
        } else {
            $infos['foto'] = null;
        }

        $major = Major::where('active', 1)->orderBy('id', 'Asc')->get();
        return view('member.profile.index', ['major' => $major, 'infos' => $infos]);
    }

    public function postInfo(Request $request)
    { 
        $status = Auth::user()->status;
        $id = Auth::user()->id;
        if ($status > 1) {
            return redirect()->route('member.data.sukses');
        }
        if ($status == null || $status < 1) {
            request()->validate([
                'fr_jurusan'    => 'required',
            //     'fr_quest1'     => 'required',
            //     'fr_quest2'     => 'required',
            //     'fr_quest3'     => 'required',
            ]);

            // $attr = $request->all();
            $form_data = [
                'user_id'   =>  $id,
                'major_id'  =>  $request->fr_jurusan,
                'info1'     => 1, //  $request->fr_quest1,
                'info2'     => 1, //  $request->fr_quest2,
                'info3'     => 1, //  $request->fr_quest3,
            ]; 

            //insert data info
            Info::create($form_data);

            //update status user
            $setStatus = [
                'status' => 1
            ];
            User::whereId($id)->update($setStatus);
        }

        $arr = [];
        $kategori = Category::whereHas('questions', function ($q) {
            $q->where('active', '=', 1);
        })->orderBy('id', 'Asc')->get();

        // looping kategori 
        foreach ($kategori as $kat) {
            // looping question per kategori 
            if (count($kat->activeQuestion) > 0) {
                array_push($arr, [
                    'id' => $kat->id,
                    'pertanyaan' => $kat->name,
                    'pilihan' => $kat->activeQuestion,

                ]);
            }
        }
        // return response()->json(['pertanyaan' => $arr]);
        $info = Info::where('user_id', $id)->first();
        $infos = [
            'user_id'       =>  $id,
            'fr_jurusan'    =>   $request->fr_jurusan,
            'fr_quest1'     =>  '', //$request->fr_quest1,
            'fr_quest2'     =>  '', //$request->fr_quest2,
            'fr_quest3'     =>  '', //$request->fr_quest3,
            'nm_jurusan'    =>  $info->major->title,
            'status'        =>  $status
        ];
        // cek foto 
        $file = Auth::user()->thumbnail;
        if (file_exists($file)) {
            $infos['foto'] = $file;
        } else {
            $infos['foto'] = null;
        }


        // return response()->json(['pertanyaan' => $arr]);
        return view('member.profile.pertanyaan', ['pertanyaan' => $arr, 'infos' => $infos]);
    }

    public function postData(Request $request)
    {
        $id = Auth::user()->id;
        $no = 1;
        $totSoal =  0;
        $totMarketing = 0;

        //mencari jumlah soal
        $kategori = Category::orderBy('id', 'Asc')->get();
        foreach ($kategori as $kat) {
            if (count($kat->activeQuestion) > 0) {
                $totSoal += 1;
            }
        }

        $mjr = Major::where('active', 1)->get();
        $infos = Info::where('user_id', $id)->first();

        $valid = 0;
        $arrPoiint = [];
        $total_point_jurusan_minat = 0; 

        for ($aa = 0; $aa < count($mjr); $aa++) {
            $noMjr = 1;
            $idMjr = $mjr[$aa]['id'];
            $totMjr = 0;
            $set_total_point_jurusan_minat = 0;
            for ($i = 0; $i < $totSoal; $i++) {
                // jumlahkan point sesuai jurusan 
                $set_total_point_jurusan_minat += $_REQUEST['pertanyaan_' . $noMjr . '__' . $infos->major_id];

                $totMjr += $_REQUEST['pertanyaan_' . $noMjr . '__' . $idMjr];
                $setMajor['A' . $noMjr] = intval($_REQUEST['pertanyaan_' . $noMjr . '__' . $idMjr]);
                $noMjr++;
            }
            $setMajor['major_minat'] = $set_total_point_jurusan_minat;
            $setMajor['user_id'] = $id;
            $setMajor['major_id'] = $idMjr;
            $setMajor['Point'] = $totMjr;
            $setMajor['tanggal'] =  Carbon::now()->toDateTimeString();

            $total_point_jurusan_minat = $set_total_point_jurusan_minat;
            array_push($arrPoiint,  $totMjr);

            //cek data di database 
            $sendData = Skor::where('user_id', $id)->where('major_id', $idMjr)->first();
            if ($sendData == null) {
                Skor::create($setMajor);
            }
        }

        $idMinat = $infos->major_id;
        $min = min($arrPoiint);  // nilai terendah
        // cek jika total nilai jurusan minat lebih kecil = dari nilai terendah 
        if ($total_point_jurusan_minat <=  $min) {
            $valid = 3; //valid
        } else {
            $valid = 2; //non valid
        }
        //update status user
        $setStatus = [
            'status' => $valid
        ];
        User::whereId($id)->update($setStatus);

        return response()->json(['sukses' => 'data sukses']);
    }

    public function suksesData()
    {
        $where = array('id' => Auth::user()->id);
        $qryUser = User::with('infos')->where($where)->first();
        $qryMajor = Major::where('id', $qryUser->infos[0]['major_id'])->first();

        $totMajot = Major::where('active', 1)->orderBy('id', 'Asc')->get();


        return view('member.profile.report', ['user' => $qryUser, 'major' => $qryMajor, 'totSoal' => count($totMajot)]);
    }

    public function dataAjax()
    {
        $totalSkor = Skor::with(['user', 'major'])->where('user_id', Auth::user()->id)->get();

        $no_mc = 1;
        $arrResult = [];
        for ($i = 0; $i < count($totalSkor); $i++) {
            array_push($arrResult, [
                'jurusan' => $totalSkor[$i]->major->initial,
                'point' => $totalSkor[$i]->Point,
            ]);
            $no_mc++;
        }

        return $arrResult;
    }

    public function showQuestion()
    {
        return view('member.profile.question');
    }

    public function ajaxResult($id)
    {
        $idMajor = [];
        $totalSkor = Skor::with(['user', 'major'])->where('user_id', $id)->get();
        //jika ada data
        if (count($totalSkor) > 0) {
            for ($i = 0; $i < count($totalSkor); $i++) {
                array_push($idMajor, $totalSkor[$i]->major->title);
            }
            $no_mc = 1;
            $arrResult = [];
            for ($i = 0; $i < count($totalSkor); $i++) {
                array_push($arrResult, [
                    'jurusan' => $totalSkor[$i]->major->initial,
                    'point' => $totalSkor[$i]->Point,
                ]);
                $no_mc++;
            }

            $idMajor = $totalSkor[0]->user->infos[0]->major_id;
            $getMajor =  Major::where('id', $idMajor)->where('active', 1)->first();

            return response()->json(['data' => $arrResult, 'user' => $totalSkor[0]->user, 'major' => $getMajor]);
        } else {
            return response()->json(['data' => null]);
        }
    }

    // user management 
    public function getUser($id)
    {
        $user = User::where('id', $id)->first();
        return response()->json($user);
    }
    public function viewUserManagement()
    {
        return view('admin.users.tabel_user_management');
    }
    public function userManagementDataTables(Request $request)
    {
        if ($request->ajax()) {
            $data = User::whereHas('roles', function ($e) {
                $e->where('name', 'manager');
            })->get();
            // $data = User::where('username', 'academic')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('kelas', function ($data) {
                    return $data->email;
                })
                ->addColumn('username', function ($data) {
                    return $data->username;
                })
                ->addColumn('action', function ($data) {
                    $detail = '<a class="btn btn-sm btn-primary text-white" id="views" data-toggle="modal" data-id=' . $data->id . '>Detail</a>';
                    $delete = '<a class="btn btn-sm btn-danger text-white" id="deleteStaff" data-toggle="modal" data-id=' . $data->id . '><span class="fa fa-delete"></span> Delete</a>';
                    return $delete . ' ' . $detail;
                })
                ->rawColumns(['name', 'kelas', 'username', 'action'])
                ->make(true);
        }
        return view('admin.users.tabel_user_management');
    }
    public function userExecution(Request $request)
    {
        if ($request->password != null) {
            $rules = array(
                'password' => 'required|min:8|max:30',
                'name' => 'required|min:3|max:30',
                'username' => 'required|min:3|max:30',
            );
        } else {
            $rules = array(
                'name' => 'required|min:3|max:30',
                'username' => 'required|min:3|max:30',
            );
        }

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors(), 'dataValid' => $request->all()]);
        }
        $id = $request->userid;
        $user = User::where('id', $id)->first();
        $attr['name'] = ucwords($request->name);
        $attr['username'] = $request->username;
        $attr['email'] = $request->username . '@gmail.com';
        if ($request->password != null) {
            $attr['password'] = Hash::make($request->password);
        }
        $user->update($attr);

        return response()->json(['success' => $request->all()]);
    }
    public function userExecutionAdd(Request $request)
    {
        $rules = array(
            'password' => 'required|min:8|max:30',
            'name' => 'required|min:3|max:30',
            'username' => 'required|min:3|max:30',
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors(), 'dataValid' => $request->all()]);
        }

        $user = User::create([
            'name' => ucwords($request->name),
            'username' => $request->username,
            'email' => $request->username . '@gmail.com',
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'password' => Hash::make($request->password),
            'thumbnail' => 'images/foto/profile/default.jpg'
        ]);

        $role = Role::select('id')->where('name', 'manager')->first();

        $user->roles()->attach($role);
        return response()->json(['success' => $request->all()]);
    }

    public function userExecutionDelete($id)
    {
        User::where('id', $id)->delete();
        return response()->json(['success' => 'Data berhasil di hapus']);
    }
    // end user management bcrypt(request('password')),
}
