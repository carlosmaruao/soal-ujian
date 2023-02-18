@extends('layouts.app', ['title'=>'LSPR - MEMBER'])

@section('style')
<link href="{{ asset('css/card.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container box-container">
    <form method="POST" action="{{route('member.info')}}">
        @csrf
        <div class="row">
            <div class="col-md-3 no-border p-3">
                <!-- <div class="text-center mb-2">
                    <img class="img-fluid rounded-circle" src="{{asset('/images/foto-default.jpg')}}" alt="">
                </div> -->
                {{-- <table class="table table-borderless table-sm">
                    <tbody>
                        <tr>
                            <th scope="row">NIM</th>
                            <td>{{Auth::user()->username}} </td>
                        </tr>
                        <tr>
                            <th scope="row">Nama</th>
                            <td>{{ Auth::user()->name }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Kelas</th>
                            <td colspan="2">{{$user->kelas}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Kelas</th>
                            <td colspan="2">{{$major->title}}</td>
                        </tr>
                    </tbody>
                </table> --}} 
            <div class="row mb-3">
                <div class="col-md-12 font-weight-light">
                    Nama Lengkap
                </div>
                <div class="col-md-12 font-weight-bold">
                    {{ Auth::user()->name }}
                </div>
            </div>
            {{-- <div class="row mb-3">
                <div class="col-md-12 font-weight-light">
                    Pilih Jurusan
                </div>
                <div class="col-md-12 font-weight-bold">
                    {{ $infos['nm_jurusan'] }}
                </div>
            </div> --}}

            </div>
            <div class="col-md-9 no-border p-3">
                <canvas id="chartResult" width="600" height="250"></canvas>
            </div>
            <div class="alert alert-primary col-md-12 m-2" role="alert">
                @if ($user->status == 3) 


                   Dear <strong>{{$user->name}}</strong>, berdasarkan hasil scoring diatas, maka pemilihan jurusan Anda <strong>SUDAH TEPAT</strong>   dan sesuai dengan minat yang telah Anda urutkan pada soal, dimulai dari no 1 s/d {{$totSoal}} sesuai dengan yang PALING Anda suka s/d yang PALING Anda TIDAK suka..
                @else
                   Dear <strong>{{$user->name}}</strong>, berdasarkan hasil scoring diatas, maka pemilihan jurusan Anda <strong>BELUM TEPAT</strong> dan belum sesuai dengan minat yang telah Anda urutkan pada soal, dimulai dari no 1 s/d 6 sesuai dengan yang PALING Anda suka s/d yang PALING Anda TIDAK suka..
                @endif 
            </div>
        </div>
    </form>
</div>
@endsection

@section('scriptBottom')

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>

<script>
    $(document).ready(function() {
        $.ajax({
            url: "{{route('member.data.ajax')}}",
            method: "GET",
            success: function(data) {
                var labelnya = [];
                var datanya = [];
                for (var i in data) {
                    labelnya.push(data[i].jurusan);
                    datanya.push(data[i].point);
                }
                var ctx = document.getElementById('chartResult').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'horizontalBar',
                    data: {
                        labels: labelnya,
                        datasets: [{
                            label: 'Hasil Vote',
                            data: datanya,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)',
                                'rgba(128, 185, 24, 0.2)',
                                'rgba(255, 107, 107, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(128, 185, 24, 1)',
                                'rgba(255, 107, 107, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        plugins: {
                            datalabels: {
                                // anchor: 'start',
                                align: 'right',
                                offset: 5,
                                color: 'black',
                                display: function(context) {
                                    return context.dataset.data[context.dataIndex] > 0;
                                },
                                font: {
                                    weight: 'bold',
                                    size: 17
                                },
                                formatter: Math.round
                            }
                        },
                        scales: {
                            xAxes: [{
                                stacked: true,
                                gridLines: {
                                    display: true
                                },
                                ticks: {
                                    display: true //menampilkan point data major
                                },
                            }],
                            yAxes: [{
                                stacked: true,
                                gridLines: {
                                    display: false
                                },
                                ticks: {
                                    fontSize: 16,
                                    fontColor: 'black',
                                    display: true //menampilkan title major
                                },
                            }]
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    return 'Point ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                },
                                title: function(tooltipItem, data) {
                                    return null;
                                }
                            }
                        },
                        legend: {
                            display: false
                        }
                    }
                });
            }
        });
    });
</script>
@endsection