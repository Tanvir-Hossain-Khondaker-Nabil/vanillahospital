<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 3/30/2019
 * Time: 10:58 AM
 */
?>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Account Type</h4>
            </div>
            <div class="modal-body">
                <div class="form-group ">
                    <label for="account_type_name">Account Name:  <span class="text-red"> * </span> </label>
                    {!! Form::text('account_type_name', null,['id'=>'account_type_name','class'=>'form-control','placeholder'=>'Enter Account Name', 'required']); !!}
                </div>
                <div class="form-group">
                    <label for="parent_id">Parent Account: </label><br/>
                    {!! Form::select('parent_id', $transaction_categories, null,['id'=>'parent_id', 'class'=>'form-control select2', 'placeholder'=>'Select Parent Account']); !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary " name="save-account" id="save-account">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
