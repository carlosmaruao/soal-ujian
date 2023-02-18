@extends('layouts.app', ['title'=>'LSPR - Minat Bakat'])

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <style>
        .hidden{ display: none !important;}
    </style>
@endsection

@section('scriptTop')
@endsection
@section('content')
    <div class="container mb-5">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="mb-5">Soal Minat Jurusan
                    <input type="submit" class="btn btn-lg btn-success float-right" id="TambahData" value="Add Data"></input>
                </h3>

                <div class="table-responsive-sm">
                    <table class="table table-bordered table-striped" id="table-view">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>qty</th>
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

    <!--Modal Data -->
    @include('admin.soal.modal.modalEdit') 

    <!--Modal Hapus -->
    @include('admin.soal.modal.modalHapus') 

 
@endsection


@section('scriptBottom')
<script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
    var baseUrl = "{{URL::to('/')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var idMjr = document.getElementById("Kpertanyaan_id");
        var table = $('#table-view').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('table.data.soal') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'total',
                    name: 'total'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            "columnDefs": [{
                    "targets": [0, 1, 2, 3],
                    "orderable": true,
                },
                {
                    "className": "text-center",
                    "targets": [0, 2, 3]
                },
                {
                    "className": "text-right",
                    "targets": []
                }
            ],
        });

        /* edit Data */
        $('body').on('click', '#data-modal', function() {
            var setId = $(this).data('id');
            $.get(baseUrl + '/admin/categories/' + setId, function(data) { 
                var quest = data.dataQuest;  
                var major_aktif = data.major_aktif; 
                $('#data-modal-show').modal('show'); 
                $('#userApproveModal').html('Soal Minat Jurusan');
                
                for (var i = 0; i < data.totalMajor; i++) {
                    $('#boxPilihan').append("<div class='input-group mb-1'>"+
                            "<div class='input-group-prepend'>"+
                            "<div class='input-group-text'>"+major_aktif[i]+"</div>"+
                            "</div>"+
                            "<input name='forPilihan"+major_aktif[i]+"' value=''  class='form-control' id='forPilihan"+major_aktif[i]+"'>"+
                            "<div class='invalid-feedback' id='errforPilihan"+major_aktif[i]+"'></div>"+
                            "</div>"); 
                } 
                $('#action_button').val('Update');
                $('#Kpertanyaan_id').val(setId);
                $('#forPertanyaan').val(data.xData.name);
                
                $.each(quest, function(k, v) { 
                    $('#forPilihan' + v.major_id).val(v.title); 
                });
                $('#deleteData').removeClass('hidden');
            })
        });

        $('#TambahData').click(function(e) {
            $.get(baseUrl + '/total-major', function(data) { 
                var major_aktif = data.major_aktif; 
                for (var i = 0; i < data.totalMajor; i++) {
                    $('#boxPilihan').append("<div class='input-group mb-1'>"+
                        "<div class='input-group-prepend'>"+
                        "<div class='input-group-text'>"+major_aktif[i]+"</div>"+
                        "</div>"+
                        "<input name='forPilihan"+major_aktif[i]+"' value=''  class='form-control' id='forPilihan"+major_aktif[i]+"'>"+
                        "<div class='invalid-feedback' id='errforPilihan"+major_aktif[i]+"'></div>"+
                        "</div>"); 
                } 
            });
            $('#userApproveModal').html('Soal Minat Jurusan'); 
            $('#action_button').val('Tambah Data');
            $('#data-modal-show').modal('show');
            $('#action_button').val('Save');
            $('#deleteData').addClass('hidden');
        });

        $('#close_modal').click(function(e) {
            resetData();
        });

        function resetData() {
            $('#table-view').DataTable().ajax.reload();
            $("#formData")[0].reset();
            $('#data-modal-show').modal('hide');
            var no = 1;
            for (let i = 0; i < 8; i++) {
                $('#forPilihan' + no).removeClass('is-invalid');
                no++;
            }
            
            $('#boxPilihan').html("");
            $('#forPertanyaan').removeClass('is-invalid');
        }

        $('#buttonCancel').click(function(e) {
            $('#table-view').DataTable().ajax.reload();
            $("#formData")[0].reset();
            $('#modalHapus').modal('hide');
        });

        $('#deleteData').click(function(e) {   
            e.preventDefault();
            $('#Kid_soal_delete').val(idMjr.value); 
            $('#titleHapus').html("Hapus Data"); 
            $('#modalHapus').modal('show'); 
            $('#data-modal-show').modal('hide');  
        });
        
        $('#buttonHapus').click(function(e) {   
            e.preventDefault();
            var setId =$('#Kid_soal_delete').val();  
            $.get(baseUrl + '/admin/delete-data-soal/' + setId, function(data) {   
                resetData()
                $('#modalHapus').modal('hide');
            });  
        });

        $('#action_button').click(function(e) {
            e.preventDefault();
            var status = $(this).attr('value');
            if (status == 'Save') {
                var action_url = "{{ route('admin.soal.tambah') }}";
            } else {
                var action_url = "{{ route('admin.soal.update') }}";
            }
            $.ajax({
                method: 'POST',
                url: action_url,
                data: $('#formData').serialize(),
                dataType: "json",
                success: (data) => {   
                    // cek data yg valid  
                    $.each(data.dataValid, function(index, value) {
                        if(value != null){
                            $('#'+index).removeClass('is-invalid');
                        }   
                    });
                    // cek data yg errors
                    if (data.errors) { 
                        $.each(data.errors, function(index, value) {
                            
                            if (data.errors[index] != null) {
                                $('#err'+index).html(data.errors[index][0]);
                                $('#'+index).addClass(' is-invalid');
                            } else {
                                $('#'+index).removeClass('is-invalid');
                            } 
                        });   
                    } else {
                        resetData();
                    }
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        });

    });
</script>
@endsection