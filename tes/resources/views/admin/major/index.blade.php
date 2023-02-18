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
            <h3 class="mb-5">Majoring
                <input type="submit" class="btn btn-lg btn-success float-right" id="TambahData" value="Add Data"></input>
            </h3>

            <div class="table-responsive-sm">
                <table class="table table-bordered table-striped" id="table-view">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Initial</th> 
                            <th>Title</th>  
                            <th>Active</th>  
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
    @include('admin.major.modal.modalEdit')

    <!--Modal Hapus --> 
    @include('admin.major.modal.modalHapus')

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
        var idMjr = document.getElementById("Kmajor_id");
        var table = $('#table-view').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('table.data.major') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'initial',
                    name: 'initial'
                },
                {
                    data: 'name',
                    name: 'name'
                }, 
                {
                    data: 'aktif',
                    name: 'aktif'
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
                    "orderable": false,
                },
                {
                    "className": "text-center",
                    "targets": [0, 3, 4]
                },
                {
                    "className": "text-right",
                    "targets": []
                }
            ],
        });
        $('body').on('click', '#btnAktif', function() {
            var setId = $(this).data('id');
            $.get(baseUrl + '/admin/major/active/' + setId, function(data) { 
                console.log(data)
                $('#table-view').DataTable().ajax.reload();
            }); 
        });

        /* edit Data */
        $('body').on('click', '#data-modal', function() {
            var setId = $(this).data('id');
            $.get(baseUrl + '/admin/major/' + setId, function(data) { 
                $('#data-modal-show').modal('show'); 
                $('#userApproveModal').html('Edit Major');  
                $('#boxPilihan').append("<div class='form-group row'>"+
                                           "<label for='staticEmail' class='col-sm-2 col-form-label'>Title</label>"+
                                           "<div class='col-sm-10'>"+
                                             "<input type='text' name='forMajor' value='{{ old('forMajor') ?? '' }}'  class='form-control' id='forMajor'>"+
                                             "<div class='invalid-feedback' id='errforMajor'></div>"+
                                         "</div></div>"+
                                         "<div class='form-group row'>"+
                                           "<label for='staticEmail' class='col-sm-2 col-form-label'>Initial</label>"+
                                           "<div class='col-sm-10'>"+
                                             "<input type='text' name='forInitial' value='{{ old('forInitial') ?? '' }}'  class='form-control' id='forInitial'>"+
                                             "<div class='invalid-feedback' id='errforInitial'></div>"+
                                         "</div></div>"); 
                $('#action_button').val('Update');
                
                $('#Kmajor_id').val(setId);
                $('#forMajor').val(data.xData.title); 
                $('#forInitial').val(data.xData.initial); 
                $('#deleteData').removeClass('hidden');
            })
        });

        $('#TambahData').click(function(e) { 
            $('#userApproveModal').html('Add Major');             
            $('#boxPilihan').append("<div class='form-group row'>"+
                                        "<label for='staticEmail' class='col-sm-2 col-form-label'>Title</label>"+
                                        "<div class='col-sm-10'>"+
                                            "<input type='text' name='forMajor' value='{{ old('forMajor') ?? '' }}'  class='form-control' id='forMajor'>"+
                                            "<div class='invalid-feedback' id='errforMajor'></div>"+
                                        "</div></div>"+
                                        "<div class='form-group row'>"+
                                        "<label for='staticEmail' class='col-sm-2 col-form-label'>Initial</label>"+
                                        "<div class='col-sm-10'>"+
                                            "<input type='text' name='forInitial' value='{{ old('forInitial') ?? '' }}'  class='form-control' id='forInitial'>"+
                                            "<div class='invalid-feedback' id='errforInitial'></div>"+
                                        "</div></div>"); 
            $('#action_button').val('Add Data');
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
            
            $('#boxPilihan').html("");
            $('#forMajor').removeClass('is-invalid');
            $('#forInitial').removeClass('is-invalid');
        }

        $('#buttonCancel').click(function(e) {
            $('#table-view').DataTable().ajax.reload();
            $("#formData")[0].reset();
            $('#modalHapus').modal('hide');
        });

        $('#deleteData').click(function(e) {   
            e.preventDefault();
            $('#Kid_major_delete').val(idMjr.value); 
            $('#titleHapus').html("Hapus Data"); 
            $('#modalHapus').modal('show'); 
            $('#data-modal-show').modal('hide');  
        });
        
        $('#buttonHapus').click(function(e) { 
            e.preventDefault();
            var setId =$('#Kid_major_delete').val();  
            $.get(baseUrl + '/admin/delete-data-major/' + setId, function(data) {   
                resetData()
                $('#modalHapus').modal('hide');
            });
        });

        $('#action_button').click(function(e) {
            e.preventDefault();
            var status = $(this).attr('value');
            if (status == 'Save') {
                var action_url = "{{ route('admin.major.tambah') }}";
            } else {
                var action_url = "{{ route('admin.major.update') }}";
            }
            $.ajax({
                method: 'POST',
                url: action_url,
                data: $('#formData').serialize(),
                dataType: "json",
                success: (data) => {
                    console.log(data.errors)
                    if (data.errors) {
                        // if error forMajor 
                        if (data.errors.forMajor != null) {
                            $('#errforMajor').html(data.errors.forMajor[0]);
                            $('#forMajor').addClass(' is-invalid');
                        } else {
                            $('#forMajor').removeClass('is-invalid');
                        }
                        // if error forInitial
                        if (data.errors.forInitial != null) {
                            $('#errforInitial').html(data.errors.forInitial[0]);
                            $('#forInitial').addClass(' is-invalid');
                        } else {
                            $('#forInitial').removeClass('is-invalid');
                        } 
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