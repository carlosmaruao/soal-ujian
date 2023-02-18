@extends('layouts.app', ['title'=>'LSPR - MEMBER'])

@section('style')
<link href="{{ asset('css/profile.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container box-container roboto">
    <div class="row">
        <div class="col-md-4 mb-5">

            <div class="row mb-3">
                
                @if ($infos['foto'])
                    <div class="col-auto col-12 no-border text-center p-3 d-none custom-image  d-md-block">
                        <img class="img-fluid rounded-circle" src="{{asset($infos['foto'])}}" alt="">
                    </div>
                @endif 
            </div>
            <div class="row mb-3">
                <div class="col-md-12 font-weight-light">
                    Nama Lengkap
                </div>
                <div class="col-md-12 font-weight-bold">
                    {{ Auth::user()->name }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12 font-weight-light">
                    Pilih Jurusan
                </div>
                <div class="col-md-12 font-weight-bold">
                    {{ $infos['nm_jurusan'] }}
                </div>
            </div>


            {{-- <div id="sidenav">
                @if ($infos['foto'])
                    <div class="col-auto col-12 no-border text-center p-3 d-none custom-image  d-md-block">
                        <img class="img-fluid rounded-circle" src="{{asset($infos['foto'])}}" alt="">
                    </div>
                @endif 

                <div class="col-12 no-border p-3">
                    <table class="table table-borderless table-sm">
                        <tbody> 
              <tr>
                <th scope="row">Nama Lengkap</th>
                <td>{{ Auth::user()->name }}</td>
              </tr>
              <tr>
                <th scope="row">Kelas</th>
                <td>{{ Auth::user()->kelas }}</td>
              </tr>
              <tr>
                <th scope="row">Telepon</th> 
                <td>{{ Auth::user()->telepon }}</td>
              </tr>
                            <tr>
                                <th scope="row">Jurusan</th>
                                <td colspan="2">
                                    {{ $infos['nm_jurusan'] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> --}}
        </div>
        <div class="col-md-8 no-border rounded">
            <form name="contact" action="{{route('member.data.post')}}" method="POST" id="form_import" class="mb-5">
                <ul class="list-group">
                    <?php
                    $no = 1;
                    $noJwb = 0;
                    $pilihanArr = [];
                    foreach ($pertanyaan as $value) { 
                        echo "<li class='list-group-item'>";
                        echo "<div class='alert alert-info' role='alert'>Urutkan dari no 1 s/d " . count($value['pilihan']) . "</div>";
                        echo "<h5>" . $value['pertanyaan'] . "</h5>";
                        $ss = 1;
                        for ($i = 0; $i < count($value['pilihan']); $i++) {
                            $setPilihan = $value['pilihan'][$i];

                            echo '<div class="row"><div class="col-3 col-md-3 col-lg-2 pull-left"><select class="form-control m-1 form-control-sm tengah" name="pertanyaan_' . $setPilihan['category_id'] . '__' . $setPilihan['major_id'] . '"  id="pertanyaan_' . $no . '__' . $ss . '"  alt="pertanyaan_' . $no . '' . $noJwb . '">';
                            echo "<option value='' hidden selected></option>";
                            $xx = 1;
                            foreach ($value['pilihan'] as $item) {
                                array_push($pilihanArr, $item->title);
                                echo "<option value='" . $xx . "' id='jawaban" . $ss . "" . $xx . "'>" . $xx . "</option> ";
                                $xx++;
                            }
                            echo "</select>
                            </div>
                            <div class='col-8 col-md-9 pull-left align-middle'>
                            <p class='text-left'>" . $setPilihan['title'] . "</p>
                            </div>
                            </div>";
                            $ss++;
                        }
                        echo "</li>";
                        $noJwb++;
                        $no++;
                    }
                    ?>
                </ul>
                <input type="submit" name="submit" class="button btn-lg btn-outline-secondary mt-3" id="submit_btn" value="Send" />
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="errModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="judulError"></h5>
                    <h5 class="modal-title" id="judulError"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="bodyError"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scriptBottom')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var slider = [];
    var no = 1;
    var noJwb = 0;

    var totalPertanyaan = document.getElementsByClassName('list-group-item').length; //total pertanyaan 
    for (let i = 0; i < totalPertanyaan; i++) {
        slider[i] = $('[alt ="pertanyaan_' + no + '' + noJwb + '"]');
        slider[i].change(function() {
            // console.log(slider[i].children)
            slider[i].find(":hidden").prop("hidden", false)
            slider[i].each(function() {
                var v = $(this).val()
                var disable = [v]
                if ($(this).val() != "") {
                    $(this).removeClass('is-invalid');
                }
                // var disable = disables[v] || [v]
                disable.forEach(function(val) {
                    // console.log(val)
                    if (val !== "") {
                        slider[i].find("[value='" + val + "']").prop("hidden", true);
                    }
                })

            })
        });
        no++;
        noJwb++;
    }

    $('#form_import').on('submit', function(e) {
        e.preventDefault();
        var jumPilihan = 1;
        var salah = 0;
        for ($i = 0; $i < totalPertanyaan; $i++) {
            var ccc = $("[id*='pertanyaan_" + jumPilihan + "']").length; //total pilihan

            var setJumPilihan = 1;
            var ss = [];
            for (let i = 0; i < ccc; i++) {
                ss[i] = $("[id*='pertanyaan_" + jumPilihan + "__" + setJumPilihan + "']");
                ss[i].each(function() {
                    var vv = $(this).val()
                    if ($(this).val() == "") {
                        salah += $(this).length;
                        $(this).addClass(' is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                    // console.log($(this).attr('id') + ', ' + vv);
                }); //cek satuan pilihan 
                setJumPilihan++;
            }

            jumPilihan++;
        }
        if (salah > 0) {
            document.getElementById('judulError').innerHTML = "Error";
            document.getElementById('bodyError').innerHTML = "Masih terdapat " + salah + " data kosong";
            $('#errModal').modal('show')
            return false;
        }
        $.ajax({
            url: "{{route('member.data.post')}}",
            method: 'POST',
            data: new FormData(this),
            /**   
            data:$(this).serialize(),*/
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",
            beforeSend: function() {
                $('#submit_btn').text('Post Data...');
            },
            success: function(data) {
                window.location.href = "{{route('member.data.sukses')}}"
            }
        });
    });
</script>
@endsection