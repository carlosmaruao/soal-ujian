<?php

namespace App\Http\Controllers;

use App\User;
use App\Major;
use App\Skor;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TableController extends Controller
{

    public function userDataTables(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('infos')->where('username', '!=', 'admin')->where('username', '!=', 'academic')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('kelas', function ($data) {
                    return $data->kelas;
                })
                ->addColumn('username', function ($data) {
                    return $data->username;
                })
                ->addColumn('jurusan', function ($data) {
                    $info = $data->infos;
                    if (count($info) > 0) {
                        $major = Major::where('id', $data->infos[0]['major_id'])->first();
                        return $major->title;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == 3) {
                        return '<span class="badge badge-success">Valid</span>';
                    } else if ($data->status == 2) {
                        return '<span class="badge badge-warning">Non Valid</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('action', function ($data) {
                    if ($data->status > 1) {
                        $infoResult = ' <a class="btn btn-sm btn-outline-info" id="views"  data-id=' . $data->id . '>View</a>';
                    } else {
                        $infoResult = '';
                    }
                    $detail = '<a class="btn btn-sm btn-outline-info" id="data-modal" data-toggle="modal" data-id=' . $data->id . '>Detail</a>';
                    $action = $infoResult;

                    return $action;
                })
                ->addColumn('cleardata', function ($data) {
                    $cleardata = '<a class="btn btn-sm btn-secondary text-white" id="clearDataBtn" data-toggle="modal" data-id=' . $data->id . '>Reset</a>';
                    $btnClearData = $cleardata;

                    return $btnClearData;
                })
                ->rawColumns(['name', 'kelas', 'username', 'jurusan', 'status', 'action', 'cleardata'])
                ->make(true);
        }
        return view('admin.users.index');
    }
}
