
<div class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" id="modalHapus" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="titleHapus">asd</h4>
            </div>
            <div class="modal-body">
                <form id="formHapus" method="POST" class="form-horizontal">
                    <input type="hidden" name="id_major_delete" id="Kid_major_delete"> 
                    @csrf
                    <div class="row"> 

                        <div class="col-xs-12 col-sm-12 col-md-12 ">
                            <input type="submit" class="btn btn-danger" id="buttonHapus" value="Delete">
                            </input>
                            <button type="button" class="btn btn-secondary" id="buttonCancel">Cancel</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>