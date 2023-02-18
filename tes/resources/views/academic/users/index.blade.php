@extends('layouts.app', ['title'=>'LSPR - Minat Bakat'])
@section('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
@endsection 
@section('content')
<div class="container mb-5">
  <div class="row">
    <div class="col-lg-12">
      <h3 class="mb-5">Tabel User</h3>
      <div class="table-responsive-sm">
        <table class="table table-bordered table-striped" id="table-view">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama</th>
              <th>Kelas</th>
              <th>NIM</th> 
              <th>Jurusan Minat</th>
              <th>Status</th>
              <th>Detail</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@include('academic.users.modalChart')
 
 
@endsection

@section('scriptBottom')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>

<script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var chart;

    var table = $('#table-view').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('table.data.user') }}",
      columns: [{
          data: 'DT_RowIndex',
          name: 'DT_RowIndex'
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
          data: 'username',
          name: 'username'
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
      ],
      "columnDefs": [{
          "targets": [0, 1, 2, 3, 4, 5, 6],
          "orderable": true,
        },
        {
          "className": "text-center",
          "targets": [0, 2, 3, 5, 6]
        },
        {
          "className": "text-right",
          "targets": []
        }
      ],
    }); 

    $('body').on('click', '#views', function() {
      var setId = $(this).data('id');
      $.get('/user-result/' + setId, function(ss) {
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
    
    $('#close_modal').click(function(e) { 
        $('#chartResult').html(""); 
        $('#data-result').modal('hide');
        destroyChart();
    });

  });
</script>
@endsection