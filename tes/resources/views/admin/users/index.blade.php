@extends('layouts.app', ['title'=>'LSPR - Minat Bakat'])

@section('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
@endsection

@section('content')
<div class="container-fluid mb-5">
  <div class="row">
    <div class="col-lg-12">
      <h3 class="mb-5">Tabel User
        <a href="{{route('admin.view.upload')}}" class="float-right">
          <button class="btn btn-sm btn-info">Upload Data</button>
        </a>
        <a class="float-right mr-2">
          <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#staticBackdrop">Reset Data</button>
        </a>
        <a class="float-right mr-2">
          <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusData">Hapus Data</button>
        </a>
        <a class="float-right mr-2" href="{{ route('admin.export.excel') }}">
          <button class="btn btn-sm btn-success">Export Excel</button>
        </a>
      </h3>
      <div class="table-responsive-sm">
        <table class="table table-bordered table-striped" id="table-view">
          <thead>
            <tr>
              <th>#</th>
              <th>NIM</th>
              <th>Nama</th>
              <th>Kelas</th>
              <th>Jurusan Minat</th>
              <th>Status</th>
              <th>Detail</th>
              <th>Reset</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<!-- Modal Hapus Data -->
<div class="modal fade" id="hapusData" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content border-0">
      <div class="modal-header bg-danger text-white rounded-top">
        <h5 class="modal-title" id="hapusDataLabel">Hapus Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Menu ini akan menghapus semua data mahasiswa.
        <div class="alert alert-danger mt-3" role="alert">
          <strong>Warning ! </strong> data yang dihapus tidak dapat di kembalikan lagi.
        </div>
      </div>
      <div class="modal-footer">
        <a href="{{ route('admin.hapus.data.master') }}" class="btn btn-danger float-right mr-2">Hapus Data</a>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content border-0">
      <div class="modal-header btn-primary rounded-top">
        <h5 class="modal-title" id="staticBackdropLabel">Reset Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Menu ini akan merefresh data skor mahasiswa dan menjadikan data yang siap untuk digunakan.
        <div class="alert alert-danger mt-3" role="alert">
          <strong>Warning ! </strong> data yang direfresh tidak dapat di kembalikan lagi.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="resetData" class="btn btn-primary float-right mr-2">Reset Data</button>
      </div>
    </div>
  </div>
</div>

<!--APPROVE customer modal -->
<div class="modal fade" id="clearDataModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0">
      <div class="modal-header btn-secondary text-white rounded-top">
        <h4 class="modal-title" id="clearDataModalTitle"></h4>
      </div>
      <div class="modal-body">
        Menu ini akan mereset data skor mahasiswa atas nama <strong><span id="userName"></span></strong>
        <div class="alert alert-danger mt-3" role="alert">
          <strong>Warning ! </strong> data yang direfresh tidak dapat di kembalikan lagi.
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" id="Kuser_id">
        <button type="button" id="action_button" class="btn btn-secondary float-right mr-2">Reset Skor</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" id="data-result" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="pl-5 pt-3">
        <h4 class="modal-title" id="dataHeader"></h4>
        <h6 class="modal-title" id="dataNIM"></h6>
        <h6 class="modal-title" id="dataJurusan"></h6>
        <h6 class="modal-title" id="dataJurusan"></h6>
      </div>
      <div class="modal-body">
        <canvas id="chartResult" width="600" height="250"></canvas>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger float-right" id="close_modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection


@section('scriptBottom')

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>


<script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    var baseUrl = "{{URL::to('/')}}";
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var chart;

    //DATATABLES
    var table = $('#table-view').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('table.data.user') }}",
      columns: [{
          data: 'DT_RowIndex',
          name: 'DT_RowIndex'
        },
        {
          data: 'username',
          name: 'username'
        },
        {
          data: 'name',
          name: 'name'
        },
        {
          data: 'kelas',
          name: 'kelas'
        },
        {
          data: 'jurusan',
          name: 'jurusan'
        },
        {
          data: 'status',
          name: 'status'
        },
        {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        },
        {
          data: 'cleardata',
          name: 'cleardata',
          orderable: false,
          searchable: false
        },
      ],
      "columnDefs": [{
          "targets": [0, 1, 2, 3, 4, 5, 6, 7],
          "orderable": true,
        },
        {
          "className": "text-center",
          "targets": [0, 1, 3, 5, 6, 7]
        },
        {
          "className": "text-right",
          "targets": []
        }
      ],
    });

    //MODAL CHART
    $('body').on('click', '#views', function() {
      var setId = $(this).data('id');
      $.get(baseUrl + '/user-result/' + setId, function(ss) {
        //-----------------------
        //jika ada datanya 
        if (ss.data != null) {
          $('#dataHeader').html(ss.user.name);
          $('#dataNIM').html(ss.user.username);
          $('#dataJurusan').html(ss.major.title);
          $('#data-result').modal('show');

          var labelnya = [];
          var datanya = [];
          for (var i in ss.data) {
            labelnya.push(ss.data[i].jurusan);
            datanya.push(ss.data[i].point);
          }
          startChart(labelnya, datanya);
        }
        //-----------------------
      })
    });

    function startChart(labelnya, datanya) {
      var ctx = document.getElementById('chartResult').getContext('2d');
      // Code for chart initialization
      chart = new Chart(ctx, {
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
              'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
              'rgba(255, 99, 132, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)'
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
      }); // Replace ... with your chart parameters
    }

    function destroyChart() {
      chart.destroy();
    }

    //RESET SKOR PER USER
    $('body').on('click', '#clearDataBtn', function() {
      var setId = $(this).data('id');
      $.get(baseUrl + '/admin/users-detail/' + setId, function(data) {
        $('#clearDataModal').modal('show');
        $('#form_result').html('');
        $('#clearDataModalTitle').html('Reset Skor');
        $('#action_button').val('Reset');
        $('#Kuser_id').val(setId);
        $('#userName').html(data[0]['name']);
      })
    });

    $('#action_button').click(function(e) {
      var setId = document.getElementById('Kuser_id').value;
      $.get(baseUrl + '/admin/reset-data-user/' + setId, function(data) {
        if (data.success) {
          $('#table-view').DataTable().ajax.reload();
          $('#clearDataModal').modal('hide');
        }
      });
    });

    $('#close_modal').click(function(e) {
      $('#chartResult').html("");
      $('#data-result').modal('hide');
      destroyChart();
    });

    //RESET DATA SKOR
    $('#resetData').click(function(e) {
      $.get(baseUrl + '/admin/reset-data-master', function(data) {
        if (data.success) {
          $('#table-view').DataTable().ajax.reload();
          $('#staticBackdrop').modal('hide');
        }
      });
    });

  });
</script>
@endsection