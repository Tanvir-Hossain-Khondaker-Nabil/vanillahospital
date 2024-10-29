
<div style="max-width: 900px !important" class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title text-success" id="exampleModalLongTitle1">Manage Labels</h3>

            <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="form-group col-md-12">

                    <span style="background-color:#83c340" class="color-tag clickable mr15 "
                        data-color="#83c340"></span>
                    <span style="background-color:#29c2c2" class="color-tag clickable mr15 "
                        data-color="#29c2c2"></span>
                    <span style="background-color:#2d9cdb" class="color-tag clickable mr15 "
                        data-color="#2d9cdb"></span>
                    <span style="background-color:#aab7b7" class="color-tag clickable mr15 "
                        data-color="#aab7b7"></span>
                    <span style="background-color:#f1c40f" class="color-tag clickable mr15 "
                        data-color="#f1c40f"></span>
                    <span style="background-color:#e18a00" class="color-tag clickable mr15 "
                        data-color="#e18a00"></span>
                    <span style="background-color:#e74c3c" class="color-tag clickable mr15 "
                        data-color="#e74c3c"></span>
                    <span style="background-color:#d43480" class="color-tag clickable mr15 "
                        data-color="#d43480"></span>
                    <span style="background-color:#ad159e" class="color-tag clickable mr15 "
                        data-color="#ad159e"></span>
                    <span style="background-color:#37b4e1" class="color-tag clickable mr15 "
                        data-color="#37b4e1"></span>
                    <span style="background-color:#34495e" class="color-tag clickable mr15 "
                        data-color="#34495e"></span>
                    <span style="background-color:#dbadff" class="color-tag clickable mr15 "
                        data-color="#dbadff"></span>
                    <input type="color" id="custom-color" class="input-color active" name="color"
                        value="#83c340">
                </div>

                <div class="col-md-10">
                    <input type="text" id="label-name" class="form-control" name="name" value="">
                    <h5 id="reqire-text" class="text-danger" style="display: none">This field is required</h5>
                    <input type="hidden" id="label-type" class="form-control" name="label_type"
                        value="task">
                    <input type="hidden" id="bg-color" class="form-control" name="bg_color" value="#83c340">

                </div>
                <div class="col-md-2">
                    <button type="button" onclick="saveLabel()" class="btn btn-primary w-100">Save</button>
                </div>

                <div id="show-label-div" class="col-md-12 mt-5">

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" id="delete-label-button" onclick="deleteLabel()" class="d-none btn btn-secondary">Delete</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

    </div>
</div>
