
<div class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" id="data-modal-show" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="userApproveModal"></h4>
            </div>
            <div class="modal-body">
                <form id="formData" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="pertanyaan_id" id="Kpertanyaan_id">
                    @csrf
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 mb-5">
                            <div class="alert alert-danger" style="display:none"></div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="forPertanyaan"><strong>Pertanyaan</strong></label>
                                    <textarea name="forPertanyaan" value="{{ old('forPertanyaan') ?? '' }}" class="form-control" id="forPertanyaan" rows="2"></textarea>
                                    <div class="invalid-feedback" id="errforPertanyaan"></div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><strong>Pilihan</strong></label>
                                    <div id="boxPilihan"></div>
                                    <!-- <?php
                                    $no = 1;
                                    for ($i = 0; $i < 8; $i++) {  ?>
                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">{{ $no }}</div>
                                            </div>
                                            <input name="forPilihan{{ $no }}" value="{{ old('forPilihan'.$no) ?? '' }}" class="form-control" id="forPilihan{{ $no }}">
                                            <div class="invalid-feedback" id="errforPilihan{{ $no }}"></div>
                                        </div>
                                    <?php
                                        $no++;
                                    } ?> -->
                                </div>
                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 ">
                            <button type="button" class="btn btn-danger" id="deleteData">Delete</button> 
                            <button type="button" class="btn btn-secondary float-right" id="close_modal">Cancel</button>
                            <input type="submit" class="btn btn-primary float-right mr-2" id="action_button" value="Edit">
                            </input>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>