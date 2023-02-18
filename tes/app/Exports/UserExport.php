<?php

namespace App\Exports;

use App\Info;
use App\Skor;
use App\User;
use App\Major;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserExport implements FromCollection, WithMapping, WithHeadings, WithColumnWidths, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $num = 1;
    public function collection()
    {
        return User::with('infos', 'skors')->where('username', '!=', 'admin')->where('username', '!=', 'academic')->get();
    }

    public function map($data): array
    {
        if (count($data->infos) > 0) {
            $mjr = Major::where('id', $data->infos[0]->major_id)->get();
            $info = Info::where('user_id', $data->id)->get();

            $jurusan = $mjr[0]['title'];
            $kerja = $info[0]['info1'];
            $rencana = $info[0]['info2'];
            $pindah = $info[0]['info2'];
        } else {
            $jurusan = '-';
            $kerja = '-';
            $rencana = '-';
            $pindah = '-';
        }
        if (count($data->skors) > 0) {
            $mjr = Major::where('active', 1)->get();
            $skor = [];
            $tanggal = '-';
            for ($i = 0; $i < count($mjr); $i++) {
                $skors = Skor::where('user_id', $data->id)->get();
                // $xx = [
                //     $mjr[$i]['initial'] => $skors[$i]['Point']
                // ];
                array_push($skor, $skors[$i]['Point']);
                $tanggal = $skors[$i]['created_at'];
                # code...
            }
        } else {
            $mjr = Major::where('active', 1)->get();
            $skor = [];
            $tanggal = '-';
            for ($i = 0; $i < count($mjr); $i++) {
                $xx = '-';
                array_push($skor, $xx);
            }
        }

        if ($data->status == 3) {
            $status = 'Valid';
        } else if ($data->status == 2) {
            $status = 'Non Valid';
        } else {
            $status = '-';
        }

        return [
            $this->num++,
            $data->username,
            $data->name,
            $data->kelas,
            $jurusan,
            $status,
            $skor[0],
            $skor[1],
            $skor[2],
            $skor[3],
            $skor[4],
            $skor[5],
            $kerja,
            $rencana,
            $pindah,
            $tanggal
            // Carbon::parse($data->event_date)->toFormattedDateString(),
            // Carbon::parse($data->created_at)->toFormattedDateString()
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'NIM',
            'Name',
            'Kelas',
            'Jurusan Peminatan',
            'Hasil',
            'PRDC Skor',
            'MMC Skor',
            'DMCA Skor',
            'MKT Skor',
            'PAC Skor',
            'IR Skor',
            'Status Kerja',
            'Rencana Kerja',
            'Kampus TransPark',
            'Tanggal Test'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 17,
            'C' => 45,
            'D' => 19,
            'E' => 39,
            'F' => 12,
            'G' => 7,
            'H' => 7,
            'I' => 7,
            'J' => 7,
            'K' => 7,
            'L' => 7,
            'M' => 8,
            'N' => 8,
            'O' => 8,
            'P' => 20,
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }
}
