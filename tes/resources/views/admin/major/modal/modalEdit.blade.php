
<div class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" id="data-modal-show" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="userApproveModal"></h4>
            </div>
            <div class="modal-body">
                <form id="formData" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="major_id" id="Kmajor_id">
                    @csrf
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 mb-5">
                            <div class="alert alert-danger" style="display:none"></div> 
                            <div class="col-md-12">
                                <div class="form-group"> 
                                    <div id="boxPilihan"></div> 
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 ">
                            <button type="button" class="btn btn-danger" id="deleteData">Delete</button> 
                            <button type="button" class="btn btn-secondary float-right" id="close_modal">Cancel</button>
                            <input type="submit" class="btn btn-primary mr-2 float-right" id="action_button" value="Edit"></input> 
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>