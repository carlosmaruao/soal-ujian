@extends('layouts.app', ['title'=>'LSPR - Minat Bakat'])

@section('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
@endsection

@section('content')
<div class="container mb-5">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="mb-5">Management User
                <a class="float-right mr-2">
                    <input type="submit" class="btn btn-lg btn-success float-right" id="addUser" value="Add Data"></input>
                </a>
            </h3>
            <div class="table-responsive-sm">
                <table class="table table-bordered table-striped" id="table-view">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>NIM</th>
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

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0">
            <div class="modal-header btn-primary rounded-top">
                <h5 class="modal-title" id="staticBackdropLabel">Add Data Staff</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="POST" id="data_form">
                @csrf
                <input type="hidden" class="form-control" name="userid" id="userId">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" class="form-control" name="name" id="name">
                        <div class="invalid-feedback" id="errname"></div>
                    </div>
                    <div class="form-group">
                        <label for="username">User Login</label>
                        <input type="text" class="form-control" name="username" id="username">
                        <div class="invalid-feedback" id="errusername"></div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password">
                        <div class="invalid-feedback" id="errpassword"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="aksi">
                    <button type="button" id="addData" class="btn btn-primary float-right mr-2">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Hapus Data -->
<div class="modal fade" id="hapusData" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0">
            <div class="modal-header bg-danger text-white rounded-top">
                <h5 class="modal-title" id="hapusDataLabel">Hapus Data Staff</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger mt-3" role="alert">
                    <strong>Warning ! </strong> data yang dihapus tidak dapat di kembalikan lagi.
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="idStaff">
                <input type="submit" id="btnhapusData" class="btn btn-danger float-right mr-2" value="Hapus Data">
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

        //DATATABLES
        var table = $('#table-view').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.table.user.management') }}",
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
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            "columnDefs": [{
                    "targets": [0, 1, 2, 3, 4],
                    "orderable": true,
                },
                {
                    "className": "text-center",
                    "targets": [0, 2, 3, 4]
                },
                {
                    "className": "text-right",
                    "targets": []
                }
            ],
        });

        function resetData() {
            $("#data_form")[0].reset();
            $('#name').removeClass('is-invalid');
            $('#username').removeClass('is-invalid');
            $('#password').removeClass('is-invalid');
            $('#staticBackdrop').modal('hide');
        }

        //MODAL CHART
        $('body').on('click', '#views', function() {
            resetData();
            var setId = $(this).data('id');
            $.get(baseUrl + '/admin/get-info-users/' + setId, function(ss) {
                $('#staticBackdrop').modal('show');
                $('#userId').val(ss.id);
                $('#name').val(ss.name);
                $('#username').val(ss.username);
                $('#password').val('');
                $('#password').addClass(' is-invalid');
                $('#errpassword').html('Jika Password tidak di rubah kosongkan saja');
                $('#aksi').val('edit');
            })
        });
        $('body').on('click', '#deleteStaff', function() {
            resetData();
            var setId = $(this).data('id');

            $('#hapusData').modal('show');
            $('#idStaff').val(setId);
        });

        //DELETE USER 
        $('#btnhapusData').on('click', function(e) {
            var setId = document.getElementById('idStaff').value;
            $('#hapusData').modal('hide');
            $.get(baseUrl + '/admin/delete-info-users/' + setId, function(ss) {
                $('#hapusData').modal('hide');
                $('#table-view').DataTable().ajax.reload();
                resetData();
            })
        });

        //ADD USER 
        $('#addUser').on('click', function(e) {
            resetData();
            e.preventDefault();
            $('#staticBackdrop').modal('show');
            $('#addData').html('Add User');
            $('#aksi').val('add');
        });

        //RESET DATA SKOR
        $('#addData').click(function(e) {
            e.preventDefault();

            var status = document.getElementById('aksi').value;
            if (status == 'edit') {
                var action_url = "{{ route('admin.user.execution') }}";
            } else {
                var action_url = "{{ route('admin.add.user.execution') }}";
            }

            $.ajax({
                method: 'POST',
                url: action_url,
                data: $('#data_form').serialize(),
                dataType: "json",
                success: (data) => {
                    // // cek data yg valid  
                    $.each(data.dataValid, function(index, value) {
                        if (value != null) {
                            $('#' + index).removeClass('is-invalid');
                        }
                    });
                    // // cek data yg errors
                    if (data.errors) {
                        $.each(data.errors, function(index, value) {
                            if (data.errors[index] != null) {
                                $('#err' + index).html(data.errors[index][0]);
                                $('#' + index).addClass(' is-invalid');
                            } else {
                                $('#' + index).removeClass('is-invalid');
                            }
                        });
                    } else {
                        $('#table-view').DataTable().ajax.reload();
                        resetData();
                    }
                },
                error: function(data) {
                    console.log(data);
                    $('#action_button').html('Save Changes');
                }
            });
        });

    });
</script>
@endsection