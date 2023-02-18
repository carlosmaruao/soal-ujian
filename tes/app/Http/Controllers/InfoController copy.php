<?php

namespace App\Http\Controllers;

use Response;
use App\Info;
use App\Major;
use App\Models\Admin\Category;
use App\Question;
use App\Skor;
use App\User;
use App\Models\Admin\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    

    public function reset(Request $request)
    { 
        // reset role 
        Role::truncate();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'member']);
        Role::create(['name' => 'manager']);

        // reset user         
        User::truncate();
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
            'email' => $passAcademic.'@gmail.com',
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

        //status code -> 
        //1:baru mengisi halaman info
        //2:sudah mengisi minat

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
        $major = Major::orderBy('id', 'Asc')->get();

        // return response()->json(['infos' => $infos]);
        return view('member.profile.index', ['major' => $major, 'infos' => $infos]);
    }

    public function postInfo(Request $request)
    { 

        $status = Auth::user()->status;
        $id = Auth::user()->id;
        if ($status == 2) {
            return redirect()->route('member.data.sukses');
        }
        if ($status == null || $status < 1) {
            request()->validate([
                'fr_jurusan'    => 'required',
                'fr_quest1'     => 'required',
                'fr_quest2'     => 'required',
                'fr_quest3'     => 'required',
            ]);

            $attr = $request->all();
            $form_data = [
                'user_id'   =>  $id,
                'major_id'  =>  $request->fr_jurusan,
                'info1'     =>  $request->fr_quest1,
                'info2'     =>  $request->fr_quest2,
                'info3'     =>  $request->fr_quest3,
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
        $kategori = Category::orderBy('id', 'Asc')->get();
        // looping kategori 
        foreach ($kategori as $kat) {
            // looping question per kategori 
            if (count($kat->question) > 0) {
                array_push($arr, [
                    'id' => $kat->id,
                    'pertanyaan' => $kat->name,
                    'pilihan' => $kat->question,
                ]);
            }
        }

        $info = Info::where('user_id', $id)->first();
        $infos = [
            'user_id'       =>  $id,
            'fr_jurusan'    =>  $request->fr_jurusan,
            'fr_quest1'     =>  $request->fr_quest1,
            'fr_quest2'     =>  $request->fr_quest2,
            'fr_quest3'     =>  $request->fr_quest3,
            'nm_jurusan'    =>  $info->major->title,
            'status'        =>  $status
        ];

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
            if (count($kat->question) > 0) {
                $totSoal += 1;
            }
        }
        for ($i = 0; $i < $totSoal; $i++) {
            $totMarketing += $_REQUEST['pertanyaan_' . $no . '__3'];
            $no++;
        }

        // PUSH NILAI PR --------------------------------------------------
        $no_pr = 1;
        $id_pr = 1;
        $tot_pr = 0;
        for ($i = 0; $i < $totSoal; $i++) {
            $tot_pr += $_REQUEST['pertanyaan_' . $no_pr . '__' . $id_pr];
            $pr['A' . $no_pr] = intval($_REQUEST['pertanyaan_' . $no_pr . '__' . $id_pr]);
            $no_pr++;
        }
        $pr['user_id'] = $id;
        $pr['major_id'] = $id_pr;
        $pr['Point'] = $tot_pr;
        $pr['tanggal'] =  Carbon::now()->toDateTimeString();
        //cek data di database 
        $dataPR = Skor::where('user_id', $id)->where('major_id', $id_pr)->first();
        if ($dataPR == null) {
            Skor::create($pr);
        }
        //   
        // PUSH NILAI MC --------------------------------------------------
        $no_mc = 1;
        $id_mc = 2; //id jurusan
        $tot_mc = 0;
        for ($i = 0; $i < $totSoal; $i++) {
            $tot_mc += $_REQUEST['pertanyaan_' . $no_mc . '__' . $id_mc];
            $mc_['A' . $no_mc] = intval($_REQUEST['pertanyaan_' . $no_mc . '__' . $id_mc]);
            $no_mc++;
        }
        $mc_['user_id'] = $id;
        $mc_['major_id'] = $id_mc;
        $mc_['Point'] = $tot_mc;
        $mc_['tanggal'] =  Carbon::now()->toDateTimeString();
        //cek data di database 
        $data_mc = Skor::where('user_id', $id)->where('major_id', $id_mc)->first();
        if ($data_mc == null) {
            Skor::create($mc_);
        }
        // 
        //------------------------------------------------------------------
        // PUSH NILAI MKT --------------------------------------------------
        $no_mkt = 1;
        $id_mkt = 3; //id jurusan
        $tot_mkt = 0;
        for ($i = 0; $i < $totSoal; $i++) {
            $tot_mkt += $_REQUEST['pertanyaan_' . $no_mkt . '__' . $id_mkt];
            $mkt_['A' . $no_mkt] = intval($_REQUEST['pertanyaan_' . $no_mkt . '__' . $id_mkt]);
            $no_mkt++;
        }
        $mkt_['user_id'] = $id;
        $mkt_['major_id'] = $id_mkt;
        $mkt_['Point'] = $tot_mkt;
        $mkt_['tanggal'] =  Carbon::now()->toDateTimeString();
        //cek data di database 
        $data_mkt = Skor::where('user_id', $id)->where('major_id', $id_mkt)->first();
        if ($data_mkt == null) {
            Skor::create($mkt_);
        }
        // 
        //------------------------------------------------------------------
        // PUSH NILAI DMCA --------------------------------------------------
        $no_dmca = 1;
        $id_dmca = 4; //id jurusan
        $tot_dmca = 0;
        for ($i = 0; $i < $totSoal; $i++) {
            $tot_dmca += $_REQUEST['pertanyaan_' . $no_dmca . '__' . $id_dmca];
            $dmca_['A' . $no_dmca] = intval($_REQUEST['pertanyaan_' . $no_dmca . '__' . $id_dmca]);
            $no_dmca++;
        }
        $dmca_['user_id'] = $id;
        $dmca_['major_id'] = $id_dmca;
        $dmca_['Point'] = $tot_dmca;
        $dmca_['tanggal'] =  Carbon::now()->toDateTimeString();
        //cek data di database 
        $data_dmca = Skor::where('user_id', $id)->where('major_id', $id_dmca)->first();
        if ($data_dmca == null) {
            Skor::create($dmca_);
        }
        // 
        //------------------------------------------------------------------
        // PUSH NILAI PAC --------------------------------------------------
        $no_pac = 1;
        $id_pac = 5; //id jurusan
        $tot_pac = 0;
        for ($i = 0; $i < $totSoal; $i++) {
            $tot_pac += $_REQUEST['pertanyaan_' . $no_pac . '__' . $id_pac];
            $pac_['A' . $no_pac] = intval($_REQUEST['pertanyaan_' . $no_pac . '__' . $id_pac]);
            $no_pac++;
        }
        $pac_['user_id'] = $id;
        $pac_['major_id'] = $id_pac;
        $pac_['Point'] = $tot_pac;
        $pac_['tanggal'] =  Carbon::now()->toDateTimeString();
        //cek data di database 
        $data_pac = Skor::where('user_id', $id)->where('major_id', $id_pac)->first();
        if ($data_pac == null) {
            Skor::create($pac_);
        }
        // 
        //------------------------------------------------------------------
        // PUSH NILAI IR --------------------------------------------------
        $no_ir = 1;
        $id_ir = 6; //id jurusan
        $tot_ir = 0;
        for ($i = 0; $i < $totSoal; $i++) {
            $tot_ir += $_REQUEST['pertanyaan_' . $no_ir . '__' . $id_ir];
            $ir_['A' . $no_ir] = intval($_REQUEST['pertanyaan_' . $no_ir . '__' . $id_ir]);
            $no_ir++;
        }
        $ir_['user_id'] = $id;
        $ir_['major_id'] = $id_ir;
        $ir_['Point'] = $tot_ir;
        $ir_['tanggal'] =  Carbon::now()->toDateTimeString();
        //cek data di database 
        $data_ir = Skor::where('user_id', $id)->where('major_id', $id_ir)->first();
        if ($data_ir == null) {
            Skor::create($ir_);
        }
        // 
        //------------------------------------------------------------------
        // PUSH NILAI EBC --------------------------------------------------
        $no_ebc = 1;
        $id_ebc = 7; //id jurusan
        $tot_ebc = 0;
        for ($i = 0; $i < $totSoal; $i++) {
            $tot_ebc += $_REQUEST['pertanyaan_' . $no_ebc . '__' . $id_ebc];
            $ebc_['A' . $no_ebc] = intval($_REQUEST['pertanyaan_' . $no_ebc . '__' . $id_ebc]);
            $no_ebc++;
        }
        $ebc_['user_id'] = $id;
        $ebc_['major_id'] = $id_ebc;
        $ebc_['Point'] = $tot_ebc;
        $ebc_['tanggal'] =  Carbon::now()->toDateTimeString();
        //cek data di database 
        $data_ebc = Skor::where('user_id', $id)->where('major_id', $id_ebc)->first();
        if ($data_ebc == null) {
            Skor::create($ebc_);
        }
        // 
        //------------------------------------------------------------------
        // PUSH NILAI HCM --------------------------------------------------
        $no_hcm = 1;
        $id_hcm = 8; //id jurusan
        $tot_hcm = 0;
        for ($i = 0; $i < $totSoal; $i++) {
            $tot_hcm += $_REQUEST['pertanyaan_' . $no_hcm . '__' . $id_hcm];
            $hcm_['A' . $no_hcm] = intval($_REQUEST['pertanyaan_' . $no_hcm . '__' . $id_hcm]);
            $no_hcm++;
        }
        $hcm_['user_id'] = $id;
        $hcm_['major_id'] = $id_hcm;
        $hcm_['Point'] = $tot_hcm;
        $hcm_['tanggal'] =  Carbon::now()->toDateTimeString();
        //cek data di database 
        $data_hcm = Skor::where('user_id', $id)->where('major_id', $id_hcm)->first();
        if ($data_hcm == null) {
            Skor::create($hcm_);
        }
        // 
        //------------------------------------------------------------------ 

        //update status user
        $setStatus = [
            'status' => 2
        ];
        User::whereId($id)->update($setStatus);

        return response()->json(['sukses' => 'data sukses']);
    }

    public function suksesData()
    {
        return view('member.profile.report');
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
            $getMajor =  Major::where('id', $idMajor)->first();

            return response()->json(['data' => $arrResult, 'user' => $totalSkor[0]->user, 'major' => $getMajor]);
        } else {
            return response()->json(['data' => null]);
        }
    }
}
