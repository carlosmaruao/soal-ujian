@extends('layouts.app', ['title' => 'LSPR - Minat Bakat'])

@section('style')
    <link href="{{ asset('css/global.css') }}" rel="stylesheet">
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container box-container roboto">
        <form method="POST" action="{{ route('member.info') }}">
            @csrf
            <div class="row">

                <div class="col-md-4 mb-5">
                    @if ($infos['foto'])
                        <div class="col-auto col-12 no-border text-center p-3 d-none custom-image  d-md-block">
                            <img class="img-fluid rounded-circle" src="{{ asset($infos['foto']) }}" alt="">
                        </div>
                    @endif

                    <div class="col-12 no-border p-3">

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
                            <div class="col-md-12">

                                @if ($infos['fr_jurusan'] != null)
                                    {{ $infos['nm_jurusan'] }}
                                @else
                                    <select
                                        class="custom-select select2 form-control @error('fr_jurusan') is-invalid @enderror"
                                        id="fr_jurusan" name="fr_jurusan">
                                        @if (old('fr_jurusan') == null)
                                            <option selected disabled value="">...</option>
                                        @endif

                                        @foreach ($major as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('fr_jurusan') == $item->id ? 'selected' : '' }}>{{ $item->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        {{-- <table class="table table-borderless table-sm">
            <tbody>
               <tr>
                <th scope="row">NIM</th>
                <td>{{ Auth::user()->username }}</td>
              </tr>  
             
              <tr>
                <th scope="row">Nama Lengkap</th> 
              </tr>
              <tr class=" col-span-2"> 
                <td class="bg-danger">{{ Auth::user()->name }}</td>
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
                <th scope="row">Pilih Jurusan</th>
                <td colspan="2">
                  @if ($infos['fr_jurusan'] != null)
                  {{ $infos['nm_jurusan'] }}
                  @else
                  <select class="custom-select select2 form-control @error('fr_jurusan') is-invalid @enderror" id="fr_jurusan" name="fr_jurusan">
                    @if (old('fr_jurusan') == null) <option selected disabled value="">...</option> @endif

                    @foreach ($major as $item)
                    <option value="{{$item->id}}" {{ (old("fr_jurusan") == $item->id ? "selected":"") }}>{{$item->title}}</option>
                    @endforeach
                  </select>
                  @endif
                </td>
              </tr>
            </tbody>
          </table> --}}
                    </div>
                </div>
                <div class="col-md-8 no-border rounded">
                    <div class="row">
                        <!--/* img -->
                        <div class="col-lg-12 no-border mt-3  p-sm-5 p-md-2">
                            <!--/* question basic 1 */ -->
                            {{-- <div class="col-12 mb-1 m-0 p-0">
              <div class="row">
                <div class="col-sm-3">
                  @if ($infos['fr_quest1'] != null)
                  <input type="text" value="{{ ucfirst($infos['fr_quest1']) }}" class="form-control" disabled>
                  @else
                  <select class="custom-select select2 @error('fr_quest1') is-invalid @enderror" id="fr_quest1" name="fr_quest1">
                    <option selected disabled value="">...</option>
                    <option value="ya" @if (old('fr_quest1') == 'ya') {{ 'selected' }} @endif>Ya</option>
                    <option value="belum" @if (old('fr_quest1') == 'belum') {{ 'selected' }} @endif>Belum</option>
                  </select>
                  @endif
                </div>
                <div class="col-sm-9">
                  <label for="fr_quest1">Apakah anda sudah bekerja ? </label>
                </div>
              </div>
            </div>

            <!--/* question basic 2 */ -->
            <div class="col-12 mb-1 m-0 p-0">
              <div class="row">
                <div class="col-sm-3">
                  @if ($infos['fr_quest2'] != null)
                  <input type="text" value="{{ ucfirst($infos['fr_quest2']) }}" class="form-control" disabled>
                  @else
                  <select class="custom-select select2 @error('fr_quest2') is-invalid @enderror" id="fr_quest2" name="fr_quest2">
                    <option selected disabled value="">...</option>
                    <option value="ya" @if (old('fr_quest2') == 'ya') {{ 'selected' }} @endif>Ya</option>
                    <option value="tidak" @if (old('fr_quest2') == 'tidak') {{ 'selected' }} @endif>Tidak</option>
                  </select>
                  @endif
                </div>
                <div class="col-sm-9">
                  <label for="fr_quest2">Apakah dalam jangka waktu 1 tahun ke depan ada rencana bekerja ? </label>
                </div>
              </div>
            </div> --}}

                            {{-- <!--/* question basic 3 */ -->
                            <div class="col-12 mb-1 m-0 p-0">
                                <div class="row">
                                    <div class="col-sm-3">
                                        @if ($infos['fr_quest3'] != null)
                                            <input type="text" value="{{ ucfirst($infos['fr_quest3']) }}"
                                                class="form-control" disabled>
                                        @else
                                            <select class="custom-select select2 @error('fr_quest3') is-invalid @enderror"
                                                id="fr_quest3" name="fr_quest3">
                                                <option selected disabled value="">...</option>
                                                <option value="ya"
                                                    @if (old('fr_quest3') == 'ya') {{ 'selected' }} @endif>Ya
                                                </option>
                                                <option value="tidak"
                                                    @if (old('fr_quest3') == 'tidak') {{ 'selected' }} @endif>Tidak
                                                </option>
                                            </select>
                                        @endif
                                    </div>
                                    <div class="col-sm-9">
                                        <label for="fr_quest3">Jika Major PR, IP & MarCom (dengan batas min kuota)
                                            direncanakan akan dibuka di kampus LSPR Transpark, apakah anda tertarik untuk
                                            kuliah disana? </label>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        @if ($infos['status'] > 1)
                            <div class="col-lg-12 no-border p-2 mt-5">
                                <div class="alert alert-success" role="alert">
                                    <p>Haloo {{ Auth::user()->name }}, terima kasih anda sudah melakukan pengisian minat
                                        bakat untuk penjurusan yang anda minati</p>

                                </div>
                            @else
                                <div class="col-12 border p-3 bg-light">
                                    @include('member.profile.petunjukAplikasi')
                                </div>
                        @endif
                        <div class="col-12 p-5 text-center">

                            @if ($infos['status'] > 1)
                                <button type="submit" class="btn btn-lg btn-outline-secondary">VIEW RESULT</button>
                            @else
                                <button type="submit" class="btn btn-lg btn-outline-secondary">START</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </form>
    </div>
@endsection

@section('scriptBottom')
    <script>
        var num = 1;
        var dat = [];
        for (let i = 0; i < 5; i++) {
            dat[i] = $('[id="fr_quest' + num + '"]');
            dat[i].change(function() {
                var v = $(this).val()
                if ($(this).val() != "") {
                    $(this).removeClass('is-invalid');
                }
            });
            num++;
        }

        jur = $('[id="fr_jurusan"]');
        jur.change(function() {
            var v = $(this).val()
            if ($(this).val() != "") {
                $(this).removeClass('is-invalid');
            }
        });
    </script>
@endsection
