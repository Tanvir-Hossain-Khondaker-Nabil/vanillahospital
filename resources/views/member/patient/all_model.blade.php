
<div id="modal-chkstatus"  class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
    <form id="form-chkstatus" action="" method="POST">
        <div class="modal-content">
            <div class="">
                <button type="button" class="close modalclosezoom" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body" id="zoom_details">

            </div>
        </div>
    </form>
    </div>
</div>

<!-- Add Doctors -->
<div class="modal fade" id="add_doctor" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Doctor </h4>
            </div>
            <form id="form_doctor" accept-charset="utf-8"  enctype="multipart/form-data" method="post">
                <input type="hidden" name="ipdid_doctor" id="ipdid_doctor" value="97">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">


                           <select placeholder="select" name="doctorOpt[]" class="doctorinput select2" style="width: 100%" multiple id="doctorOpt">

                                <option value="2">
                                        Sonia Bush (9002)
                                </option>

                                <option value="4">
                                        Sansa Gomez (9008)
                                </option>

                                <option value="12">
                                        Reyan Jain (9011)
                                </option>


                            </select>
                             <span class="text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="form_doctorbtn" data-loading-text="Processing..." class="btn btn-info pull-right"> <i class="fa fa-check-circle"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="discharge_revert" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Discharge Revert </h4>
            </div>
            <form id="form_discharge_revert" accept-charset="utf-8"  enctype="multipart/form-data" method="post">
                <input type="hidden" name="ipd_details_id" id="ipd_details_id" value="97">
                <input type="hidden" name="opd_details_id" id="opd_details_id" >
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="exampleInputFile">
                                        Bed Group</label>
                                <div>
                                    <select class="form-control" name='bed_group_id' id='bed_group_id' onchange="getBed(this.value, '', 'yes')">
                                        <option value="">Select</option>
                                                                                    <option value="1">VIP Ward - Ground  Floor</option>
                                                                                    <option value="2">Private Ward - 3rd Floor</option>
                                                                                    <option value="3">General Ward Male - 3rd Floor</option>
                                                                                    <option value="4">ICU - 2nd Floor</option>
                                                                                    <option value="5">NICU - 2nd Floor</option>
                                                                                    <option value="6">AC (Normal) - 1st Floor</option>
                                                                                    <option value="7">Non AC - 4th Floor</option>
                                                                            </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="exampleInputFile">
                                    Bed No.</label><small class="req"> *</small>
                                <div><select class="form-control select2" style="width:100%" name='bed_no' id='bed_nos'>
                                        <option value="">Select</option>

                                    </select>
                                </div>
                                <span class="text-danger"></span></div>
                        </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="exampleInputFile">
                                                        Revert Reason</label><small class="req"> *</small>
                                                    <div>
                                                        <textarea name="discharge_revert_reason" rows="3" class="form-control"></textarea>
                                                    </div>
                                                    <span class="text-danger"></span></div>
                                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="submit_discharge_revert" data-loading-text="Processing..." class="btn btn-info pull-right"> <i class="fa fa-check-circle"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Timeline -->
<div class="modal fade" id="myTimelineModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Timeline</h4>
            </div>
            <form id="add_timeline" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                <div class="scroll-area">
                    <div class="modal-body pb0 ptt10">
                            <div class="row">
                                <div class=" col-md-12">
                                    <div class="form-group">
                                        <label>Title</label><small class="req"> *</small>
                                        <input type="hidden" name="patient_id" id="patient_id" value="980">
                                        <input id="timeline_title" name="timeline_title" placeholder="" type="text" class="form-control" />
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Date                                            <small class="req"> *</small>
                                        </label>
                                        <input id="timeline_date" name="timeline_date" value="06/13/2024" placeholder="" type="text" class="form-control date"  />
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea id="timeline_desc" name="timeline_desc" placeholder=""  class="form-control" rows=6></textarea>
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Attach Document</label>
                                        <div>
                                            <input id="timeline_doc_id" name="timeline_doc" placeholder="" type="file"  class="filestyle form-control" data-height="40" value="" />
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="align-top">Visible to this person</label>
                                        <input id="visible_check" checked="checked" name="visible_check" value="yes" placeholder="" type="checkbox" />
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="add_timelinebtn" data-loading-text="Processing..." class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                </div>
            </form>


        </div>
    </div>
</div>

<div class="modal fade" id="nursenoteEditModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Nurse Note</h4>
            </div>
            <form id="edit_nursenote" accept-charset="utf-8" enctype="multipart/form-data" method="post" class="">
                <div class="scroll-area">
                    <div class="modal-body pt0 pb0">
                        <div class="ptt10">
                            <div class="row">

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Date                                        <small class="req"> *</small>
                                        </label>
                                        <input type="text" name="date" id="endate" value="" class="form-control datetime">
                                        <input type="hidden" name="nurseid" id="nurse_id">

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Nurse<small class="req"> *</small> </label>

                                        <select name="nurse"  style="width: 100%" id="edit_nurse" class="form-control select2">
                                            <option value="">Select</option>
                                                                                        <option   value="16">
                                            April Clinton (9020)
                                            </option>
                                                                                        <option   value="10">
                                            Natasha  Romanoff (9010)
                                            </option>
                                                                                    </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Note <small class="req"> *</small> </label>
                                        <textarea name="note" id="enote" style="height:50px" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Comment <small class="req"> *</small> </label>
                                        <textarea name="comment" id="ecomment" style="height:50px" class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="" id="customfieldnurse" ></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" data-loading-text="Processing..." id="edit_nursenotebtn" class="btn btn-info"><i class="fa fa-check-circle"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="nursenoteCommentModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> Add Comment</h4>
            </div>
            <form id="comment_nursenote" accept-charset="utf-8" enctype="multipart/form-data" method="post" class="">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="row">
                            <!-- <input type="hidden" name="nurseid" id="enurse_id"> -->
                            <input type="hidden" name="nurseid" id="nurse_noteid">
                            <!--  <input type="hidden" name="ipd_id" id="nurse_ipdid"> -->
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Comment <small class="req"> *</small> </label>
                                    <textarea name="comment_staff" id="comment_staff" style="height:100px" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" data-loading-text="Processing..." id="comment_nursenotebtn" class="btn btn-info"><i class="fa fa-check-circle"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="patient_discharge" role="dialog" aria-labelledby="follow_up">
    <div class="modal-dialog modal-mid modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close"  data-dismiss="modal">&times;</button>
               <div class="modalicon">
                     <div id='allpayments_print'>
                    </div>
                </div>
                <h4 class="modal-title">Patient Discharge</h4>
            </div>
            <div class="modal-body pb0" id="patient_discharge_result">

            </div>
        </div>
    </div>
</div>
<!-- Add OT -->
<div class="modal fade" id="add_operationtheatre" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Operation</h4>
            </div>
            <div class="scroll-area">
               <form id="form_operationtheatre" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                    <div class="modal-body pb0 ptt10">
                        <input type="hidden" value="97" name="ipdid" class="form-control" id="ipdid" />
                        <input type="hidden" value="6290" name="case_id" />
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Operation Category</label><small class="req"> *</small>
<select name="operation_category" id="operation_category" class="form-control select2" onchange="getcategory(this.value)" style="width:100%">
                                                    <option value="">Select</option>
                                                                                                        <option value="1">ENT and Oral Surgery</option>
                                                                                                    <option value="6">Gynaecology</option>
                                                                                                    <option value="5">Ophthalmology</option>
                                                                                                    <option value="2">Orthopedic Surgery</option>
                                                                                                    <option value="3">Plastic Surgery</option>
                                                                                                    <option value="4">Thoracic Surgery</option>
                                                                                                    <option value="8">Thoracic Surgery</option>
                                                                                                    <option value="7">Urology</option>
                                                                                                </select>
                                                <span class="text-danger"></span>
                                        </div>
                                     </div>
                                     <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="operation_name">Operation Name</label>
                                                <small class="req"> *</small>
                                               <div>
                                                <select name="operation_name" id="operation_name" class="form-control select2" style="width:100%">

                                                </select>
                                            </div>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>


                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Operation Date</label>
                                                <small class="req"> *</small>
        <input type="text" value="" id="date" name="date" class="form-control datetime">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                    Consultant Doctor</label>
                                                <small class="req"> *</small>
                                                <div><select class="form-control select2"   style="width:100%" id='consultant_doctorid' name='consultant_doctor' >
                                                        <option value="">Select</option>
                                                                                                                    <option value="11" >Amit  Singh (9009)</option>
                                                                                                                                <option value="12" >Reyan Jain (9011)</option>
                                                                                                                                <option value="4" >Sansa Gomez (9008)</option>
                                                                                                                                <option value="2" >Sonia Bush (9002)</option>
                                                                                                                        </select>

                                                </div>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Assistant Consultant 1</label>
                                                <input type="text" name="ass_consultant_1" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Assistant Consultant 2</label>
                                                <input type="text" name="ass_consultant_2" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Anesthetist</label>
                                                <input type="text" name="anesthetist" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Anesthesia Type</label>
                                                <input type="text" name="anaethesia_type" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>OT Technician</label>
                                                <input type="text" name="ot_technician" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>OT Assistant</label>
                                                <input type="text" value="" name="ot_assistant" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Remark</label>
                                                <textarea name="ot_remark" id="ot_remark" class="form-control" ></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Result</label>
                                                <textarea name="ot_result" id="ot_result" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="">
                                                                                    </div>


                                </div>
                    </div>

                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" id="form_addoperationtheatrbtn" data-loading-text="Processing..." class="btn btn-info"><i class="fa fa-check-circle"></i> Save</button>
                    </div>
                </div>
            </form>

            </div> <!-- scroll-area -->
        </div>
    </div>
</div>
<!-- Edit Operation Theatre -->

<div class="modal fade" id="edit_operationtheatre" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Operation</h4>
            </div>
               <form id="form_editoperationtheatre" accept-charset="utf-8" enctype="multipart/form-data" method="post" class="ptt10">
                    <div class="scroll-area">
                        <div class="modal-body pt0 pb0">
                                <div class="row">
                                      <input type="hidden" value="97" name="opdid" class="form-control" id="opdid" />
                                    <input type="hidden" value="" name="otid" class="form-control" id="otid" />
                                    <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Operation Category</label> <small class="req"> *</small>

                                                <select name="eoperation_category" id="eoperation_category" class="form-control select2" onchange="getcategory(this.value)" style="width:100%">
                                                    <option value="">Select</option>
                                                                                                        <option value="1">ENT and Oral Surgery</option>
                                                                                                    <option value="6">Gynaecology</option>
                                                                                                    <option value="5">Ophthalmology</option>
                                                                                                    <option value="2">Orthopedic Surgery</option>
                                                                                                    <option value="3">Plastic Surgery</option>
                                                                                                    <option value="4">Thoracic Surgery</option>
                                                                                                    <option value="8">Thoracic Surgery</option>
                                                                                                    <option value="7">Urology</option>
                                                                                                </select>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>

                                     <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="operation_name">Operation Name</label>
                                                <small class="req"> *</small>

                                                <select name="eoperation_name" id="eoperation_name" class="form-control select2" style="width:100%">
                                                    <option value="">Select</option>
                                                                                                        <option value="7"> Facelift Surgery</option>
                                                                                                    <option value="5"> Tooth extraction</option>
                                                                                                    <option value="6">Arthroscopic surgery including ACL repair</option>
                                                                                                    <option value="1">Bronchoscopy</option>
                                                                                                    <option value="3">Cataract extraction and most other ophthalmological procedures</option>
                                                                                                    <option value="8">Coronary artery bypass</option>
                                                                                                    <option value="4">Dilation and curettage</option>
                                                                                                    <option value="2">Hydrocele and varicocele excision</option>
                                                                                                </select>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>


                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Operation Date</label>
                                                <small class="req"> *</small>
                                                <input type="text" value="" id="edate" name="date" class="form-control datetime">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                    Consultant Doctor</label> <small class="req"> *</small>
                                                <div><select class="form-control select2"   style="width:100%" id='econsultant_doctorid' name='consultant_doctor' >
                                                        <option value="">Select</option>
                                                                                                                    <option value="11" >Amit  Singh (9009)</option>
                                                                                                                                <option value="12" >Reyan Jain (9011)</option>
                                                                                                                                <option value="4" >Sansa Gomez (9008)</option>
                                                                                                                                <option value="2" >Sonia Bush (9002)</option>
                                                                                                                        </select>

                                                </div>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Assistant Consultant 1</label>
                                                <input type="text" name="ass_consultant_1" id="eass_consultant_1" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Assistant Consultant 2</label>
                                                <input type="text" name="ass_consultant_2"  id="eass_consultant_2" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Anesthetist</label>
                                                <input type="text" name="anesthetist" id="eanesthetist" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Anaethesia Type</label>
                                                <input type="text" name="anaethesia_type" id="eanaethesia_type" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>OT Technician</label>
                                                <input type="text" name="ot_technician" id="eot_technician" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>OT Assistant</label>
                                                <input type="text" value="" name="ot_assistant"  id="eot_assistant"  class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Remark</label>
                                                <textarea name="eot_remark" id="eot_remark" class="form-control" ></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Result</label>
                                                <textarea name="eot_result" id="eot_result" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div id="custom_field_ot">

                                        </div>

                                </div>
                        </div>
                  </div><!-- scroll-area -->
               <div class="modal-footer">
                    <div class="pull-right">
                    <button type="submit" id="form_editoperationtheatrebtn" data-loading-text="Processing..." class="btn btn-info"><i class="fa fa-check-circle"></i> Save</button>
                   </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Timeline -->
<div class="modal fade" id="myTimelineEditModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Timeline</h4>
            </div>
            <form id="edit_timeline" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                <div class="scroll-area">
                    <div class="modal-body pb0">
                        <div class="row">
                            <div class=" col-md-12">
                                <div class="form-group">
                                        <label>Title</label><small class="req"> *</small>
                                        <input type="hidden" name="patient_id" id="epatientid" value="">
                                        <input type="hidden" name="timeline_id" id="etimelineid" value="">
                                        <input id="etimelinetitle" name="timeline_title" placeholder="" type="text" class="form-control"  />
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Date</label><small class="req"> *</small>

                                        <input type="text" name="timeline_date" class="form-control date" id="etimelinedate"/>
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea id="timelineedesc" name="timeline_desc" placeholder=""  class="form-control"></textarea>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="form-group">
                                        <label>Attach Document</label>
                                        <div><input id="etimeline_doc_id" name="timeline_doc" placeholder="" type="file"  class="filestyle form-control" data-height="40"  value="" />
                                            <span class="text-danger"></span></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="align-top">Visible to this person</label>
                                        <input id="evisible_check"  name="visible_check" value="yes" placeholder="" type="checkbox" />

                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" data-loading-text="Processing..." id="edit_timelinebtn" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Diagnosis -->
<div class="modal fade" id="edit_diagnosis" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Diagnosis</h4>
            </div>
            <form id="form_editdiagnosis" accept-charset="utf-8"  enctype="multipart/form-data" method="post" class="ptt10">
                <div class="modal-body pt0 pb0">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>
Report Type</label><small class="req"> *</small>
                                    <input type="text" name="report_type" class="form-control" id="ereporttype" />
                                    <input type="hidden" value="" name="diagnosis_id" class="form-control" id="eid" />
                                    <input type="hidden" value="" name="diagnosispatient_id" class="form-control" id="epatient_id" />
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>
Report Date</label><small class="req"> *</small>
                                    <input type="text" name="report_date" class="form-control date" id="ereportdate"/>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="align-top">Document</label> <input type="file" class="form-control filestyle" name="report_document" id="ereportdocument" />
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="align-top">Report Center Name</label> <input type="text" class="form-control" name="report_center" id="ereportcenter" />
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control" id="edescription"></textarea>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" id="form_editdiagnosisbtn" data-loading-text="Processing..." class="btn btn-info">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Prescription -->
<div class="modal fade" id="add_prescription" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close pupclose" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="edit_prescription_title">Add Prescription</h4>
            </div>
            <form id="form_prescription" accept-charset="utf-8" enctype="multipart/form-data" method="post">
            <div class="pup-scroll-area">
                <div class="modal-body pt0 pb0">

                </div> <!--./modal-body-->
            </div>
            <div class="box-footer sticky-footer">
                <div class="pull-right">


                     <button type="submit" name="save_print" value="save_print" data-loading-text="Processing..." class="btn btn-info"><i class="fa fa-print"></i> Save & Print                        </button>
                    <button type="submit" name="save" value="save" data-loading-text="Processing..." class="btn btn-info"><i class="fa fa-check-circle"></i> Save                     </button>


                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- -->
<div class="modal fade" id="viewModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Patient Details</h4>
            </div>
            <form id="formrevisit" accept-charset="utf-8" enctype="multipart/form-data" method="post" class="ptt10">
                <div class="modal-body pt0 ">
                            <div class="row">
                               <div class="col-md-12">
                                    <div class="table-responsive">
                                    <table class="table mb0 table-striped table-bordered examples">
                                        <tr>
                                            <th width="15%">Patient Name</th>
                                            <td width="35%"><span id="patient_name"></span> (<span id='patients_id'></span>)</td>
                                            <th width="15%">Guardian Name</th>
                                            <td width="35%"><span id='guardian_name'></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Gender</th>
                                            <td width="35%"><span id='gen'></span></td>
                                            <th width="15%">Marital Status</th>
                                            <td width="35%"><span id="marital_status"></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Phone</th>
                                            <td width="35%"><span id="contact"></span></td>
                                            <th width="15%">Email</th>
                                            <td width="35%"><span id='email' style="text-transform: none"></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Address</th>
                                            <td width="35%"><span id='patient_address'></span></td>
                                            <th width="15%">Age</th>
                                            <td width="35%"><span id="age"></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Blood Group</th>
                                            <td width="35%"><span id="blood_group"></span></td>
                                            <th width="15%">Height</th>
                                            <td width="35%"><span id='height'></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Weight</th>
                                            <td width="35%"><span id="weight"></span></td>
                                            <th width="15%">Temperature</th>
                                            <td width="35%"><span id='temperature'></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Respiration</th>
                                            <td width="35%"><span id="respiration"></span></td>
                                            <th width="15%">Pulse</th>
                                            <td width="35%"><span id='pulse'></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">BP</th>
                                            <td width="35%"><span id='patient_bp'></span></td>
                                            <th width="15%">Symptoms</th>
                                            <td width="35%"><span id='symptoms'></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Known Allergies</th>
                                            <td width="35%"><span id="known_allergies"></span></td>
                                            <th width="15%">Admission Date</th>
                                            <td width="35%"><span id="admission_date"></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Case</th>
                                            <td width="35%"><span id='case'></span></td>
                                            <th width="15%">Old Patient</th>
                                            <td width="35%"><span id='old_patient'></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Casualty</th>
                                            <td width="35%"><span id="casualty"></span></td>
                                            <th width="15%">Reference</th>
                                            <td width="35%"><span id="refference"></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">TPA</th>
                                            <td width="35%"><span id="organisation"></span></td>
                                            <th width="15%">Bed Group</th>
                                            <td width="35%"><span id="bed_group"></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Consultant Doctor</th>
                                            <td width="35%"><span id='doc'></span></td>
                                            <th width="15%">Bed Number</th>
                                            <td width="35%"><span id='bed_name'></span></td>
                                        </tr>

                                    </table>
                                </div>
                                <div id="field_data">
                                </div>
                               </div>
                            </div>
                        </form>
            </div>
        </div>
    </div>
</div>

<!-- -->
<div class="modal fade" id="prescriptionview" role="dialog" aria-labelledby="follow_up">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close sss" data-dismiss="modal">&times;</button>
                <div class="modalicon">
                    <div id='edit_deleteprescription'>
                    </div>
                </div>
                <h4 class="modal-title">Prescription</h4>
            </div>
            <div class="modal-body pt0 pb0" id="getdetails_prescription"></div>
        </div>
    </div>
</div>

<!-- -->
<div class="modal fade" id="myPaymentModal" role="dialog" aria-labelledby="myModalLabel">
    <form id="add_payment" accept-charset="utf-8" method="post" class="ptt10">
        <div class="modal-dialog modal-mid" role="document">
            <div class="modal-content modal-media-content">
                <div class="modal-header modal-media-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Payment</h4>
                </div>
                <!-- <div class="scroll-area"> -->
                    <div class="modal-body pb0 ptt10">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date</label><small class="req"> *</small>
                                    <input type="text" name="payment_date" id="date" class="form-control datetime">
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Amount ($)</label><small class="req"> *</small>
                                    <input type="text" name="amount" id="amount" class="form-control" value="-1800">

                                    <input type="hidden" name="net_amount" class="form-control" value="-1800">
                                    <input type="hidden" name="case_reference_id" id="case_reference_id" class="form-control" value="6290">
                                   <input type="hidden" name="patient_id"  class="form-control" value="980">
                                    <input type="hidden" name="ipdid" value="97">
                                    <input type="hidden" name="total" id="total" class="form-control">
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payment Mode</label>
                                    <select class="form-control payment_mode" name="payment_mode">
                                                                                    <option value="Cash" >Cash</option>
                                                                                    <option value="Cheque" >Cheque</option>
                                                                                    <option value="transfer_to_bank_account" >Transfer to Bank Account</option>
                                                                                    <option value="UPI" >UPI</option>
                                                                                    <option value="Other" >Other</option>
                                                                                    <option value="Online" >Online</option>
                                                                            </select>
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row cheque_div" style="display: none;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cheque No</label><small class="req"> *</small>
                                    <input type="text" name="cheque_no" id="cheque_no" class="form-control">
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cheque Date</label> <small class="req"> *</small>
                                    <input type="text" name="cheque_date" id="cheque_date" class="form-control date">
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Attach Document</label>
                                    <input type="file" id="payment_file" class="filestyle form-control"   name="document">
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Note</label>
                                    <textarea  name="note" id="note" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="add_paymentbtn" data-loading-text="Processing..." class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                    </div>
                <!-- </div> -->
            </div>
        </div>

    </form>
</div>
<!-- -->

<!-- -->
<div class="modal fade" id="myMedicationModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Add Medication Dose</h4>
                            </div>
        <form id="add_medicationdose" accept-charset="utf-8" method="post" class="ptt10">
            <div class="scroll-area">
                <div class="modal-body pt0 pb0">
                        <div class="row">
                                    <input type="hidden" name="ipdid" id="mipdid" value="97" >
                                    <input type="hidden" name="medicine_name_id" id="mpharmacy_id" value="" >
                                    <input type="hidden" name="date"  id="mdate" value="" >
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date</label><small class="req"> *</small>
                                            <input type="text" name="date" id="add_dose_date" class="form-control date">
                                            <span class="text-danger"></span>
                                            <input type="hidden" name="ipdid" value="97">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pwd">Time</label>
                                            <div class="bootstrap-timepicker">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" name="time" class="form-control timepicker" id="add_dose_time" value="">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Medicine Category</label> <small class="req"> *</small>
                                            <select class="form-control medicine_category_medication select2" style="width:100%" id="add_dose_medicine_category" name='medicine_category_id'>
                                                <option value="">Select                                                </option>
                                                                                                        <option value="1">Syrup                                                    </option>
                                                                                                                <option value="2">Capsule                                                    </option>
                                                                                                                <option value="3">Injection                                                    </option>
                                                                                                                <option value="4">Ointment                                                    </option>
                                                                                                                <option value="5">Cream                                                    </option>
                                                                                                                <option value="6">Surgical                                                    </option>
                                                                                                                <option value="7">Drops                                                    </option>
                                                                                                                <option value="8">Inhalers                                                    </option>
                                                                                                                <option value="9">Implants / Patches                                                    </option>
                                                                                                                <option value="10">Liquid                                                    </option>
                                                                                                                <option value="11">Preparations                                                    </option>
                                                                                                                <option value="12">Diaper	                                                    </option>
                                                                                                                <option value="13">Tablet                                                    </option>
                                                                                                            </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Medicine Name</label> <small class="req"> *</small>
                                        <select class="form-control select2 medicine_name_medication" style="width:100%"  id="add_dose_medicine_id" name='medicine_name_id'>
                                                <option value="">Select                                                    </option>
                                                </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Dosage</label> <small class="req"> *</small>
                                        <select class="form-control select2 dosage_medication" style="width:100%"  id="mdosage" onchange="" name='dosage'>
                                                <option value="">Select                                                    </option>
                                                </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Remarks</label>
                                            <textarea  name="remark" id="remark" class="form-control"></textarea>

                                        </div>
                                    </div>
                                </div>
                        </div>

                  </div>
                   <div class="modal-footer">
                        <button type="submit" id="add_medicationdosebtn" data-loading-text="Processing..." class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                    </div>
            </form>
        </div>
    </div>
</div>
<!-- -->


<div class="modal fade" id="myaddMedicationModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Medication Dose</h4>
            </div>
        <form id="add_medication" accept-charset="utf-8" method="post" class="ptt10">
            <div class="scroll-area">
                <div class="modal-body pt0 pb0">

                        <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date</label><small class="req"> *</small>
                                            <input type="text" name="date" id="date" class="form-control date">
                                            <span class="text-danger"></span>
                                            <input type="hidden" name="ipdid" value="97">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pwd">Time</label>
                                            <div class="bootstrap-timepicker">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" name="time" class="form-control timepicker" id="mtime" value="">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Medicine Category</label> <small class="req"> *</small>
                                            <select class="form-control medicine_category_medication select2" style="width:100%" id="mmedicine_category_id" name='medicine_category_id'>
                                                <option value="">Select                                                </option>
                                                                                                        <option value="1">Syrup                                                    </option>
                                                                                                                <option value="2">Capsule                                                    </option>
                                                                                                                <option value="3">Injection                                                    </option>
                                                                                                                <option value="4">Ointment                                                    </option>
                                                                                                                <option value="5">Cream                                                    </option>
                                                                                                                <option value="6">Surgical                                                    </option>
                                                                                                                <option value="7">Drops                                                    </option>
                                                                                                                <option value="8">Inhalers                                                    </option>
                                                                                                                <option value="9">Implants / Patches                                                    </option>
                                                                                                                <option value="10">Liquid                                                    </option>
                                                                                                                <option value="11">Preparations                                                    </option>
                                                                                                                <option value="12">Diaper	                                                    </option>
                                                                                                                <option value="13">Tablet                                                    </option>
                                                                                                            </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Medicine Name</label> <small class="req"> *</small>
                                        <select class="form-control select2 medicine_name_medication" style="width:100%"  id="mmedicine_id" name='medicine_name_id'>
                                                <option value="">Select                                                    </option>
                                                </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Dosage</label> <small class="req"> *</small>
                                        <select class="form-control select2 dosage_medication" style="width:100%"  id="dosage" onchange="get_dosagename(this.value)" name='dosage'>
                                                <option value="">Select                                                    </option>
                                                </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Remarks</label>
                                            <textarea  name="remark" id="remark" class="form-control"></textarea>

                                        </div>
                                    </div>
                                </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" id="add_medicationbtn" data-loading-text="Processing..." class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- -->

<!-- -->
<div class="modal fade" id="myMedicationDoseModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modalicon">
                    <div id='edit_delete'></div>
                </div>
                <h4 class="modal-title">Edit Medication Dose</h4>
            </div>

                <form id="update_medication" accept-charset="utf-8" method="post" class="ptt10">
                    <div class="modal-body pt0 pb0">
                        <input type="hidden" name="medication_id" class="" id="medication_id" value="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date</label><small class="req"> *</small>
                                        <input type="text" name="date" id="date_edit_medication" class="form-control date">
                                        <span class="text-danger"></span>
                                        <input type="hidden" name="ipdid" value="97">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd">Time</label>
                                        <div class="bootstrap-timepicker">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" name="time" class="form-control timepicker" id="dosagetime" value="">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Medicine Category</label> <small class="req"> *</small>
                                        <select class="form-control medicine_category_medication select2" style="width:100%" id="mmedicine_category_edit_id" name='medicine_category_id'>
                                            <option value="">Select                                            </option>
                                                                                                <option value="1">Syrup                                                </option>
                                                                                                        <option value="2">Capsule                                                </option>
                                                                                                        <option value="3">Injection                                                </option>
                                                                                                        <option value="4">Ointment                                                </option>
                                                                                                        <option value="5">Cream                                                </option>
                                                                                                        <option value="6">Surgical                                                </option>
                                                                                                        <option value="7">Drops                                                </option>
                                                                                                        <option value="8">Inhalers                                                </option>
                                                                                                        <option value="9">Implants / Patches                                                </option>
                                                                                                        <option value="10">Liquid                                                </option>
                                                                                                        <option value="11">Preparations                                                </option>
                                                                                                        <option value="12">Diaper	                                                </option>
                                                                                                        <option value="13">Tablet                                                </option>
                                                                                                    </select>
                                        <span class="text-danger"></span>
                                    </div>
                                </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Medicine Name</label> <small class="req"> *</small>
                                    <select class="form-control select2 medicine_name_medication" style="width:100%"  id="mmedicine_edit_id" name='medicine_name_id'>
                                            <option value="">Select                                                </option>
                                            </select>
                                        <span class="text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Dosage</label> <small class="req"> *</small>
                                        <select class="form-control  select2" style="width:100%" id="medicine_dose_edit_id" name='dosage_id'>
                                        <option value="">Select                                        </option>
                                                                                <option value="1">1  (ML)                                                </option>

                                                                                <option value="2">1 MG                                                </option>

                                                                                <option value="3">1 (ML)                                                </option>

                                                                                <option value="4">1 Day                                                </option>

                                                                                <option value="5">1/2 Day                                                </option>

                                                                                <option value="6">1 Hour                                                </option>

                                                                                <option value="7">0.5 (ML)                                                </option>

                                                                                <option value="8">1 Day                                                </option>

                                                                                </select>
                                        <span class="text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <textarea  name="remark" id="medicine_dosage_remark" class="form-control"></textarea>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" id="update_medicationbtn" data-loading-text="Processing..." class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                        </div>

                </form>
        </div>
    </div>
</div>
<!-- -->

<!--Add Charges-->
<div class="modal fade" id="myChargesModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close pupclose" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Charges</h4>
            </div>
            <form id="add_charges" accept-charset="utf-8" method="post" class="ptt10">
                <div class="pup-scroll-area">
                    <div class="modal-body pb0 pt0">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <input type="hidden" name="patient_id" value="980">
                                <input type="hidden" name="ipdid" value="97" >
                                <input type="hidden" name="patient_charge_id" id="editpatient_charge_id" value="0" >
                                <input type="hidden" name="organisation_id" id="organisation_id" value="4" >
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Charge Type</label><small class="req"> *</small>
                                            <select name="charge_type" id="add_charge_type" style="width: 100%"  class="form-control charge_type select2">
                                                <option value="">Select</option>
                                                                                                    <option value="3">
                                                    IPD                                                    </option>
                                                                                                <option value="8">
                                                    Procedures                                                    </option>
                                                                                                <option value="10">
                                                    Supplier                                                    </option>
                                                                                                <option value="11">
                                                    Operations                                                    </option>
                                                                                                <option value="12">
                                                    Others                                                    </option>
                                                                                        </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Charge Category</label><small class="req"> *</small>
                                            <select name="charge_category" id="charge_category" style="width: 100%" class="form-control charge_category select2">
                                                <option value="">Select</option>
                                            </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Charge Name</label><small class="req"> *</small>
                                            <select name="charge_id" id="charge_id" style="width: 100%" class="form-control charge select2" >
                                                <option value="">Select</option>
                                            </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Standard Charge ($)</label>
                                            <input type="text" readonly name="standard_charge" id="standard_charge" class="form-control" value="">
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>TPA Charge ($)</label>
                                            <input type="text" readonly name="schedule_charge" id="schedule_charge" placeholder="" class="form-control" value="">
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Qty</label><small class="req"> *</small>
                                        <input type="text" name="qty" id="qty" class="form-control" >
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                    <div class="row">
                                        <div class="col-sm-5 mb10">
                                            <table class="printablea4">
                                                <tr>
                                                    <th width="40%">Total ($)</th>
                                                    <td width="60%" colspan="2" class="text-right ipdbilltable">
                                                        <input type="text" placeholder="Total" value="0" name="apply_charge" id="apply_charge" style="width: 30%; float: right" class="form-control total" readonly />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Tax ($)</th>
                                                    <td class="text-right ipdbilltable">
                                                        <h4 style="float: right;font-size: 12px; padding-left: 5px;"> %</h4>
                                                        <input type="text" placeholder="Tax" name="charge_tax" id="charge_tax" class="form-control charge_tax" readonly style="width: 70%; float: right;font-size: 12px;"/>
                                                    </td>
                                                    <td class="text-right ipdbilltable">
                                                        <input type="text" placeholder="Tax" name="tax" value="0" id="tax" style="width: 50%; float: right" class="form-control tax" readonly/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Net Amount ($)</th>
                                                    <td colspan="2" class="text-right ipdbilltable">
                                                        <input type="text" placeholder="Net Amount" value="0" name="amount" id="final_amount" style="width: 30%; float: right" class="form-control net_amount" readonly/>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>Charge Note</label>
                                                        <textarea name="note" id="edit_note" rows="3" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!--./col-sm-6-->

                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Date</label> <small class="req"> *</small>
                                                <input id="charge_date" name="date" placeholder="" type="text" class="form-control datetime" />
                                            </div>
                                            <button type="submit" data-loading-text="Processing..." name="charge_data" value="add" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Add</button>
                                        </div>

                                    </div><!--./row-->

                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12" class="table-responsive">
                                    <table class="table table-striped table-bordered ">
                                        <tr>
                                            <th>Date</th>
                                            <th>Charge Type</th>
                                            <th>Charge Category</th>
                                            <th>Charge Name</th>
                                            <th>Standard Charge ($)</th>
                                            <th>TPA Charge ($)</th>
                                            <th>Qty</th>
                                            <th>Total ($)</th>
                                            <th>Tax ($)</th>
                                            <th>Net Amount ($)</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                        <tbody id="preview_charges">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div><!--./scroll-area-->
                    <div class="modal-footer sticky-footer">
                        <div class="pull-right">


                            <button type="submit" data-loading-text="Processing..." value="save" name="charge_data" class="btn btn-info"><i class="fa fa-check-circle"></i> Save</button>
                        </div>
                    </div>
                </form>
        </div>
    </div>
</div>

<div class="modal fade" id="myChargeseditModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Charges</h4>
            </div>
         <form id="edit_charges" accept-charset="utf-8" method="post" class="ptt10">
            <div class="scroll-area">
                <div class="modal-body pb0 pt0">
                     <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                                <input type="hidden" name="patient_id" value="980">
                                    <!-- <input type="hidden" name="org_id" id="org_id" value="0"> -->
                                    <input type="hidden" name="ipdid" value="97" >
                                    <input type="hidden" name="patient_charge_id" id="patient_charge_id" value="0" >
                                        <input type="hidden" name="organisation_id" id="organisation_id" value="4" >
                                <div class="row">

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Charge Type</label><small class="req"> *</small>

                                            <select name="charge_type" id="edit_charge_type" class="form-control charge_type select2">
                                                <option value="">Select</option>
                                                                                                    <option value="3">
                                                    IPD                                                    </option>
                                                                                                <option value="8">
                                                    Procedures                                                    </option>
                                                                                                <option value="10">
                                                    Supplier                                                    </option>
                                                                                                <option value="11">
                                                    Operations                                                    </option>
                                                                                                <option value="12">
                                                    Others                                                    </option>
                                                                                        </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Charge Category</label><small class="req"> *</small>
                                            <select name="charge_category" id="editcharge_category" style="width: 100%" class="form-control charge_category select2">
                                                <option value="">Select</option>
                                            </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Charge Name</label><small class="req"> *</small>
                                            <select name="charge_id" id="editcharge_id" style="width: 100%" class="form-control charge select2" >
                                                <option value="">Select</option>
                                            </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>


                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Standard Charge ($)</label>
                                            <input type="text" readonly name="standard_charge" id="editstandard_charge" class="form-control" value="">
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>TPA Charge ($)</label>
                                            <input type="text" readonly name="schedule_charge" id="editschedule_charge" placeholder="" class="form-control" value="">
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Qty</label><small class="req"> *</small>
                                        <input type="text" name="qty" id="editqty" class="form-control" >
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>

                                    <div class="divider"></div>

                                        <div class="row">
                                            <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Date</label> <small class="req"> *</small>
                                            <input id="editcharge_date" name="date" placeholder="" type="text" class="form-control datetime" />
                                        </div>
                                    </div>
                                            <div class="col-sm-3">
                                                <div class="row">

                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label>Charge Note</label>

                                                            <textarea name="note" id="enote" rows="3" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!--./col-sm-6-->


                                            <div class="col-sm-6 mb10">

                                                <table class="printablea4">


                                                    <tr>
                                                        <th width="40%">Total ($)</th>
                                                        <td width="60%" colspan="2" class="text-right ipdbilltable">
                                                        <input type="text" placeholder="Total" value="0" name="apply_charge" id="editapply_charge" style="width: 30%; float: right" class="form-control total" readonly /></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tax ($)</th>
                                                        <td class="text-right ipdbilltable">
                                                            <h4 style="float: right;font-size: 12px; padding-left: 5px;"> %</h4>
                                                    <input type="text" placeholder="Tax" name="charge_tax" id="editcharge_tax" class="form-control charge_tax" readonly style="width: 70%; float: right;font-size: 12px;"/></td>

                                                        <td class="text-right ipdbilltable">
                                                            <input type="text" placeholder="Tax" name="tax" value="0" id="edittax" style="width: 50%; float: right" class="form-control tax" readonly/>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Net Amount ($)</th>
                                                        <td colspan="2" class="text-right ipdbilltable">
                                                            <input type="text" placeholder="Net Amount" value="0" name="amount" id="editfinal_amount" style="width: 30%; float: right" class="form-control net_amount" readonly/></td>
                                                    </tr>
                                                </table>


                                            </div>

                                        </div><!--./row-->
                        </div>

                       </div>
                     </div>
                   </div><!--./scroll-area-->
                    <div class="box-footer">


                 <button type="submit"  data-loading-text="Processing..." name="charge_data" value="add" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
            </div>

                </form>

        </div>
    </div>
</div>
<!-- -->
<div class="modal fade" id="myModaledit"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100 modalfullmobile" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="row">
                    <div class="col-sm-6 col-xs-8">
                        <div class="form-group15">
                            <div>
                                <select onchange="get_ePatientDetails(this.value)" disabled class="form-control select2" style="width:100%" id="evaddpatient_id" name='' >
                                    <option value="">Select</option>
                                                                            <option value="1072" >Aadish Mody (1072)                                        </option>
                                                                            <option value="1073" >Aditya Mannan (1073)                                        </option>
                                                                            <option value="994" >Albert (994)                                        </option>
                                                                            <option value="1057" >Albert (1057)                                        </option>
                                                                            <option value="987" >Alfred (987)                                        </option>
                                                                            <option value="1001" >Alice (1001)                                        </option>
                                                                            <option value="896" >Alisha Knowles (896)                                        </option>
                                                                            <option value="1045" >Alwin (1045)                                        </option>
                                                                            <option value="954" >Amit Deshpande (954)                                        </option>
                                                                            <option value="1070" >Ananya Sarma (1070)                                        </option>
                                                                            <option value="1069" >Anees Kakar (1069)                                        </option>
                                                                            <option value="873" >Angela Clark (873)                                        </option>
                                                                            <option value="924" >Angela S. Dildy (924)                                        </option>
                                                                            <option value="493" >Ankit Singh (493)                                        </option>
                                                                            <option value="1009" >Archie (1009)                                        </option>
                                                                            <option value="1021" >Archie (1021)                                        </option>
                                                                            <option value="190" >Arnav Joshi (190)                                        </option>
                                                                            <option value="872" >Arthur Wood (872)                                        </option>
                                                                            <option value="578" >Ashutosh pandey (578)                                        </option>
                                                                            <option value="1014" >Averitt (1014)                                        </option>
                                                                            <option value="1081" >Baber Devan (1081)                                        </option>
                                                                            <option value="1083" >Babita Krishnamurthy (1083)                                        </option>
                                                                            <option value="1082" >Bharat Naidu (1082)                                        </option>
                                                                            <option value="1024" >Bowie (1024)                                        </option>
                                                                            <option value="1029" >Brennen (1029)                                        </option>
                                                                            <option value="1017" >Briggs (1017)                                        </option>
                                                                            <option value="914" >Callie L. Milam (914)                                        </option>
                                                                            <option value="641" >Cameron Martin (641)                                        </option>
                                                                            <option value="533" >Candey wood (533)                                        </option>
                                                                            <option value="1074" >Carolyn G. Rister (1074)                                        </option>
                                                                            <option value="827" >Carolyn Wright (827)                                        </option>
                                                                            <option value="1022" >Cavan (1022)                                        </option>
                                                                            <option value="940" >Charles J. Wagner (940)                                        </option>
                                                                            <option value="937" >Christy D. Murray (937)                                        </option>
                                                                            <option value="1053" >Clinton (1053)                                        </option>
                                                                            <option value="1019" >Corbin (1019)                                        </option>
                                                                            <option value="843" >Cristian Messina (843)                                        </option>
                                                                            <option value="509" >Daniel Wood (509)                                        </option>
                                                                            <option value="991" >Darcy (991)                                        </option>
                                                                            <option value="1059" >Darcy (1059)                                        </option>
                                                                            <option value="1026" >Darwin (1026)                                        </option>
                                                                            <option value="1058" >Darwin (1058)                                        </option>
                                                                            <option value="539" >David Hussan (539)                                        </option>
                                                                            <option value="1034" >Destan (1034)                                        </option>
                                                                            <option value="879" >Devendra Kothari (879)                                        </option>
                                                                            <option value="484" >Dhawan Kulkarni (484)                                        </option>
                                                                            <option value="1025" >Dhuruv Rana (1025)                                        </option>
                                                                            <option value="1061" >Ebenezer (1061)                                        </option>
                                                                            <option value="986" >Edison (986)                                        </option>
                                                                            <option value="921" >Ehsaan Lanka (921)                                        </option>
                                                                            <option value="868" >Elliot Coates (868)                                        </option>
                                                                            <option value="1054" >Ellis (1054)                                        </option>
                                                                            <option value="984" >Elvio (984)                                        </option>
                                                                            <option value="1042" >Ember (1042)                                        </option>
                                                                            <option value="642" >Emma Watson (642)                                        </option>
                                                                            <option value="1027" >Ennis (1027)                                        </option>
                                                                            <option value="288" >Evander Jonh (288)                                        </option>
                                                                            <option value="1032" >Fiora (1032)                                        </option>
                                                                            <option value="1041" >Ford (1041)                                        </option>
                                                                            <option value="1051" >Frank (1051)                                        </option>
                                                                            <option value="489" >Gaurav Patel (489)                                        </option>
                                                                            <option value="643" >Gaurav Shrivastava (643)                                        </option>
                                                                            <option value="985" >George (985)                                        </option>
                                                                            <option value="1062" >George (1062)                                        </option>
                                                                            <option value="878" >George Parker (878)                                        </option>
                                                                            <option value="990" >George R. Garcia (990)                                        </option>
                                                                            <option value="1063" >Gladwin (1063)                                        </option>
                                                                            <option value="1055" >Goodwin (1055)                                        </option>
                                                                            <option value="845" >Hanna Salo (845)                                        </option>
                                                                            <option value="998" >Hardy (998)                                        </option>
                                                                            <option value="1052" >Hardy (1052)                                        </option>
                                                                            <option value="1013" >Harper (1013)                                        </option>
                                                                            <option value="884" >Harpreet Varkey (884)                                        </option>
                                                                            <option value="1008" >Harrison (1008)                                        </option>
                                                                            <option value="655" >Harry Martin (655)                                        </option>
                                                                            <option value="627" >harry mount (627)                                        </option>
                                                                            <option value="1010" >Hazel (1010)                                        </option>
                                                                            <option value="877" >Henry Cooper (877)                                        </option>
                                                                            <option value="1044" >Huxley (1044)                                        </option>
                                                                            <option value="1037" >Isabell (1037)                                        </option>
                                                                            <option value="1071" >Jagat Nawal (1071)                                        </option>
                                                                            <option value="532" >Jamesh Wood (532)                                        </option>
                                                                            <option value="584" >Jamesh Wood (584)                                        </option>
                                                                            <option value="1033" >Jarad (1033)                                        </option>
                                                                            <option value="1076" >Jasmine Rudd (1076)                                        </option>
                                                                            <option value="1065" >Jasper (1065)                                        </option>
                                                                            <option value="781" >Jeffrey M. Ransom (781)                                        </option>
                                                                            <option value="1011" >Jemma (1011)                                        </option>
                                                                            <option value="2" >John Marshall (2)                                        </option>
                                                                            <option value="992" >Jones (992)                                        </option>
                                                                            <option value="1000" >Jordon (1000)                                        </option>
                                                                            <option value="1049" >Josie (1049)                                        </option>
                                                                            <option value="887" >kailash punti (887)                                        </option>
                                                                            <option value="639" >Kalvin Martin (639)                                        </option>
                                                                            <option value="840" >Kathleen Campbell (840)                                        </option>
                                                                            <option value="886" >Kelvin Octamin (886)                                        </option>
                                                                            <option value="1003" >Kingston (1003)                                        </option>
                                                                            <option value="1020" >Kingston (1020)                                        </option>
                                                                            <option value="538" >Kittu Mac (538)                                        </option>
                                                                            <option value="567" >Kittu Mac (567)                                        </option>
                                                                            <option value="908" >Koushtubh Parmar (908)                                        </option>
                                                                            <option value="1084" >Lal Krishna Advani (1084)                                        </option>
                                                                            <option value="1016" >Lathen (1016)                                        </option>
                                                                            <option value="1004" >Lennon (1004)                                        </option>
                                                                            <option value="1075" >Lincoln Yeo (1075)                                        </option>
                                                                            <option value="1066" >Louis (1066)                                        </option>
                                                                            <option value="1077" >Mackenzie Cotter (1077)                                        </option>
                                                                            <option value="363" >Mahima Shinde (363)                                        </option>
                                                                            <option value="1005" >MAIREENA GOMAZ (1005)                                        </option>
                                                                            <option value="1012" >Maizie (1012)                                        </option>
                                                                            <option value="121" >Maria Taylor (121)                                        </option>
                                                                            <option value="830" >Martin Opega (830)                                        </option>
                                                                            <option value="1047" >Mason (1047)                                        </option>
                                                                            <option value="1067" >Matthew (1067)                                        </option>
                                                                            <option value="1040" >Matthew J. Brown (1040)                                        </option>
                                                                            <option value="1038" >Mayer (1038)                                        </option>
                                                                            <option value="1007" >Milo (1007)                                        </option>
                                                                            <option value="1043" >Myla (1043)                                        </option>
                                                                            <option value="658" >NAMIT AGGRAWAL (658)                                        </option>
                                                                            <option value="997" >Nelson (997)                                        </option>
                                                                            <option value="1050" >Nelson (1050)                                        </option>
                                                                            <option value="1023" >Neville (1023)                                        </option>
                                                                            <option value="980" >Nishant Kadakia (980)                                        </option>
                                                                            <option value="563" >Nivetha Thomas (563)                                        </option>
                                                                            <option value="1064" >Oakley (1064)                                        </option>
                                                                            <option value="765" >Obaid Venkatesh (765)                                        </option>
                                                                            <option value="1" >Olivier Thomas (1)                                        </option>
                                                                            <option value="531" >Olivier Thomas (531)                                        </option>
                                                                            <option value="542" >Olivier Thomas (542)                                        </option>
                                                                            <option value="561" >Olivier Thomas (561)                                        </option>
                                                                            <option value="1046" >Parson (1046)                                        </option>
                                                                            <option value="999" >Patrick (999)                                        </option>
                                                                            <option value="917" >Patrick Cummins (917)                                        </option>
                                                                            <option value="1036" >Pearson (1036)                                        </option>
                                                                            <option value="1060" >Percy (1060)                                        </option>
                                                                            <option value="993" >Perry (993)                                        </option>
                                                                            <option value="628" >Preeti Desmukh (628)                                        </option>
                                                                            <option value="1080" >Qadim Shetty (1080)                                        </option>
                                                                            <option value="1085" >Qazi Asad Ullah (1085)                                        </option>
                                                                            <option value="1028" >Rayn (1028)                                        </option>
                                                                            <option value="1030" >Rayn (1030)                                        </option>
                                                                            <option value="996" >Richard (996)                                        </option>
                                                                            <option value="918" >Riley Lawry (918)                                        </option>
                                                                            <option value="916" >Robert J. Glenn (916)                                        </option>
                                                                            <option value="844" >Robin Dahlberg (844)                                        </option>
                                                                            <option value="1039" >Royal (1039)                                        </option>
                                                                            <option value="1048" >Ruby (1048)                                        </option>
                                                                            <option value="1068" >Rupal Gala (1068)                                        </option>
                                                                            <option value="1015" >Ruta (1015)                                        </option>
                                                                            <option value="939" >Ryan D. Spangler (939)                                        </option>
                                                                            <option value="1078" >Ryan Dampier (1078)                                        </option>
                                                                            <option value="1002" >Scott (1002)                                        </option>
                                                                            <option value="520" >Shakib Khanna (520)                                        </option>
                                                                            <option value="1006" >Sonny (1006)                                        </option>
                                                                            <option value="1035" >Stan (1035)                                        </option>
                                                                            <option value="989" >Stanley (989)                                        </option>
                                                                            <option value="874" >Stephen Jackson (874)                                        </option>
                                                                            <option value="1018" >Sterling (1018)                                        </option>
                                                                            <option value="580" >Stuart Wood (580)                                        </option>
                                                                            <option value="1031" >Vanita (1031)                                        </option>
                                                                            <option value="629" >varun mahajan (629)                                        </option>
                                                                            <option value="1079" >Venkat Nath (1079)                                        </option>
                                                                            <option value="919" >Wesley Barresi (919)                                        </option>
                                                                            <option value="922" >Wesley Barresi (922)                                        </option>
                                                                            <option value="928" >Wiktor Jaworski (928)                                        </option>
                                                                            <option value="151" >Wordey Limpi (151)                                        </option>
                                                                    </select>
                            </div>
                            <span class="text-danger"></span>
                        </div>
                    </div><!--./col-sm-6 col-xs-8 -->
                </div><!--./row-->
            </div>
            <form id="formeditrecord" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                <div class="pup-scroll-area">
                    <div class="modal-body pt0 pb0">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="row row-eq">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <div class="ptt10">
                                            <div id="evajax_load"></div>
                                            <div class="row" id="evpatientDetails" style="display:none">
                                                <div class="col-md-9 col-sm-9 col-xs-9">
                                                    <ul class="singlelist">
                                                        <li class="singlelist24bold">
                                                            <span id="evlistname"></span></li>
                                                        <li>
                                                            <i class="fas fa-user-secret" data-toggle="tooltip" data-placement="top" title="Guardian"></i>
                                                            <span id="evguardian"></span>
                                                        </li>
                                                    </ul>
                                                    <ul class="multilinelist">
                                                        <li>
                                                            <i class="fas fa-venus-mars" data-toggle="tooltip" data-placement="top" title="Gender"></i>
                                                            <span id="evgenders" ></span>
                                                        </li>
                                                        <li>
                                                            <i class="fas fa-tint" data-toggle="tooltip" data-placement="top" title="Blood Group"></i>
                                                            <span id="evblood_group"></span>
                                                        </li>
                                                        <li>
                                                            <i class="fas fa-ring" data-toggle="tooltip" data-placement="top" title="Marital Status"></i>
                                                            <span id="evmarital_status"></span>
                                                        </li>
                                                    </ul>
                                                    <ul class="singlelist">
                                                        <li>
                                                            <i class="fas fa-hourglass-half" data-toggle="tooltip" data-placement="top" title="Age"></i>
                                                            <span id="evage"></span>
                                                        </li>
                                                        <li>
                                                            <i class="fa fa-phone-square" data-toggle="tooltip" data-placement="top" title="Phone"></i>
                                                            <span id="evlistnumber"></span>
                                                        </li>
                                                        <li>
                                                            <i class="fa fa-envelope" data-toggle="tooltip" data-placement="top" title="Email"></i>
                                                            <span id="evemail"></span>
                                                        </li>
                                                        <li>
                                                            <i class="fas fa-street-view" data-toggle="tooltip" data-placement="top" title="Address"></i>
                                                            <span id="evaddress" ></span>
                                                        </li>
                                                        <li>
                                                            <b>Any Known Allergies </b>
                                                            <span id="evallergies" ></span>
                                                        </li>
                                                        <li>
                                                            <b>Remarks </b>
                                                            <span id="evnote"></span>
                                                        </li>
                                                        <li>
                                                            <b>TPA ID </b>
                                                            <span id="etpa_id"></span>
                                                        </li>
                                                        <li>
                                                            <b>TPA Validity </b>
                                                            <span id="etpa_validity"></span>
                                                        </li>
                                                        <li>
                                                            <b>National Identification Number </b>
                                                            <span id="eidentification_number"></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-3 col-sm-3 col-xs-3">
                                                    <div class="pull-right">

                                                        <img class="profile-user-img img-responsive" src="https://demo.smart-hospital.in/uploads/patient_images/no_image.png?1718275779" id="evimage" alt="User profile picture">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="dividerhr"></div>
                                                </div><!--./col-md-12-->
                                                <div class="col-sm-2 col-xs-4">
                                                    <div class="form-group">
                                                        <label for="pwd">Height</label>
                                                        <input name="height" id="evheight" type="text" class="relative zindex-1 form-control" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 col-xs-4">
                                                    <div class="form-group">
                                                        <label for="pwd">Weight</label>
                                                        <input name="weight" id="evweight" type="text" class="relative zindex-1 form-control" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 col-xs-4">
                                                    <div class="form-group">
                                                        <label for="pwd">BP</label>
                                                        <input name="bp" id="evbp" type="text" class="form-control relative zindex-1" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 col-xs-4">
                                                    <div class="form-group">
                                                        <label for="pwd">Pulse</label>
                                                        <input name="pulse" id="evpulse" type="text" class="form-control relative zindex-1" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 col-xs-4">
                                                    <div class="form-group">
                                                        <label for="pwd">Temperature</label>
                                                        <input name="temperature" id="evtemperature" type="text" class="form-control relative zindex-1" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 col-xs-4">
                                                    <div class="form-group">
                                                        <label for="pwd">Respiration</label>
                                                        <input name="respiration" id="evrespiration" type="text" class="form-control relative zindex-1" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-xs-4">
                                                <div class="form-group relative zindex-1">
                                                    <label for="exampleInputFile">
                                                        Symptoms Type</label>
                                                    <div><select name='symptoms_type' id="act" class="form-control select2 act" style="width:100%">
                                                            <option value="">Select</option>
                                                                                                                        <option value="1">Eating or weight problems</option>
                                                                                                                    <option value="2">Emotional problems</option>
                                                                                                                    <option value="3">Muscle or joint problems</option>
                                                                                                                    <option value="4">Skin problems</option>
                                                                                                                    <option value="5">Bladder problems</option>
                                                                                                                    <option value="6">Stomach  problems</option>
                                                                                                                    <option value="7">Lung problems</option>
                                                                                                                </select>
                                                    </div>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                                <div class="col-sm-3">
                                                    <label for="exampleInputFile">
                                                        Symptoms Title</label>
                                                    <div id="dd" class="wrapper-dropdown-3">
                                                        <input class="form-control filterinput relative zindex-1" type="text">
                                                        <ul class="dropdown scroll150 section_ul">
                                                            <li><label class="checkbox">Select</label></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="email">Symptoms Description</label>
                                                        <textarea row="3" name="symptoms" id="symptoms_description" class="form-control relative zindex-1" ></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="pwd">Note</label>
                                                        <textarea name="note" id='evnoteipd' rows="3" class="form-control relative zindex-1"></textarea>
                                                    </div>
                                                </div>
                                                <div class="" id="customfield" >

                                                </div>
                                            </div>
                                            <input name="patient_id" id="evpatients_id" type="hidden" class="form-control" value="" />
                                            <input name="otid" id="otid" type="hidden" class="form-control"  value="" />
                                            <input type="hidden" id="updateid" name="updateid">
                                            <input type="hidden" id="ipdid_edit" name="ipdid">
                                            <input type="hidden" id="previous_bed_id" name="previous_bed_id">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-eq ptt10">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Admission Date<small class="req"> *</small> </label>
                                                    <input id="edit_admission_date" name="appointment_date" placeholder="" type="text" class="form-control datetime" />
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Case</label>
                                                    <div><input class="form-control" type='text' id="patient_case" name='case_type' />
                                                    </div>
                                                    <span class="text-danger"></span></div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Casualty</label>
                                                    <div>
                                                        <select name="casualty" id="patient_casualty" class="form-control" >
                                                            <option value="Yes">Yes</option>
                                                            <option value="No" selected>No</option>
                                                        </select>
                                                    </div>
                                                <span class="text-danger"></span></div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputFile">
                                                    Old Patient</label>
                                                    <div>
                                                        <select name="old_patient" id="old" class="form-control">
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                    </div>
                                                    <span class="text-danger"></span></div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Credit Limit ($)</label>
                                                    <input type="text" id="credits_limits" value="" name="credit_limit" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputFile">
                                                    TPA</label>
                                                    <div><select class="form-control" name='organisation' id="edit_organisations">
                                                            <option value="">Select</option>
                                                                                                                            <option value="4">Health Life Insurance</option>
                                                                                                                        <option value="3">Star Health Insurance</option>
                                                                                                                        <option value="2">IDBI Federal</option>
                                                                                                                        <option value="1">CGHS</option>
                                                                                                                </select>
                                                    </div>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputFile">
                                                    Reference</label>
                                                    <div><input class="form-control" type='text' name='refference' id="patient_refference" />
                                                    </div>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputFile">
                                                        Consultant Doctor <small class="req"> *</small> </label>
                                                    <div>
                                                        <select class="form-control select2" style="width: 100%;"  name='cons_doctor' id="patient_consultant" >
                                                            <option value="">Select</option>
                                                                                                                            <option value="11" >Amit  Singh (9009)</option>
                                                                                                                        <option value="12" >Reyan Jain (9011)</option>
                                                                                                                        <option value="4" >Sansa Gomez (9008)</option>
                                                                                                                        <option value="2" >Sonia Bush (9002)</option>
                                                                                                                </select>
                                                                                                            </div>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="exampleInputFile">
                                                            Bed Group</label>
                                                    <div>
                                                        <select class="form-control" name='bed_group_id' id='ebed_group_id' onchange="getBed(this.value, '', 'yes','ebed_nos')">
                                                            <option value="">Select</option>
                                                                                                                            <option value="1">VIP Ward - Ground  Floor</option>
                                                                                                                            <option value="2">Private Ward - 3rd Floor</option>
                                                                                                                            <option value="3">General Ward Male - 3rd Floor</option>
                                                                                                                            <option value="4">ICU - 2nd Floor</option>
                                                                                                                            <option value="5">NICU - 2nd Floor</option>
                                                                                                                            <option value="6">AC (Normal) - 1st Floor</option>
                                                                                                                            <option value="7">Non AC - 4th Floor</option>
                                                                                                                    </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="exampleInputFile">
                                                        Bed No.</label><small class="req"> *</small>
                                                    <div><select class="form-control select2" style="width:100%" name='bed_no' id='ebed_nos'>
                                                            <option value="">Select</option>

                                                        </select>
                                                    </div>
                                                    <span class="text-danger"></span></div>
                                            </div>
                                        </div><!--./row-->
                                    </div><!--./col-md-4-->
                                </div><!--./row-->
                            </div><!--./col-md-12-->
                        </div><!--./row-->
                    </div>
                </div>
                <div class="modal-footer sticky-footer">
                    <div class="pull-right">
                        <button type="submit" id="formeditrecordbtn" data-loading-text="Processing..." class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- discharged summary   -->
<div class="modal fade" id="myModaldischarged"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <div class="modalicon">
                     <div id='summary_print'>
                    </div>
                </div>
                <h4 class="modal-title">Discharged Summary</h4>
                <div class="row">
                </div><!--./row-->
            </div>
            <form id="formdishrecord" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row row-eq">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="ptt10">
                                        <div id="evajax_load"></div>
                                        <div class="row" id="" >
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <ul class="multilinelist">
                                                      <li>  <label for="pwd">Name</label>
                                                        <span id="disevlistname"></span>
                                                    </li>
                                                     <li>
                                                        <label for="pwd">Age</label>
                                                        <span id="disevage"></span>
                                                    </li>
                                                     <li>
                                                        <label for="pwd">Gender</label>
                                                        <span id="disevgenders" ></span>
                                                    </li>
                                                </ul>
                                                <ul class="multilinelist">
                                                    <li>
                                                         <label>Admission Date</label>
                                                        <span id="disedit_admission_date"></span>
                                                    </li>
                                                    <li>
                                                         <label>Discharged Date</label>
                                                        <span id="disedit_discharge_date"></span>
                                                    </li>
                                                </ul>
                                            <ul class="singlelist">
                                                    <li>
                                                        <label>Address</label>
                                                        <span id="disevaddress"></span>
                                                    </li>
                                            </ul>
                                            </div>
                                        </div>
                                        <div class="row">
                                             <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="pwd">Diagnosis</label>
                                                    <input name="diagnosis" id='disdiagnosis' rows="3" class="form-control" >
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="pwd">Operation</label>
                                                    <input name="operation" id='disoperation'  class="form-control" >
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="pwd">Note</label>
                                                    <textarea name="note" id='disevnoteipd' rows="3" class="form-control" ></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="dividerhr"></div>
                                            </div><!--./col-md-12-->
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="pwd">Investigation</label>
                                                    <textarea name="investigations" id='disinvestigations' rows="3" class="form-control" ></textarea>
                                                </div>
                                            </div>

                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label for="pwd">Treatment At Home</label>
                                                    <textarea name="treatment_at_home" id='distreatment_at_home' rows="3" class="form-control" ></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <input name="patient_id" id="disevpatients_id" type="hidden">
                                        <input type="hidden" id="disupdateid" name="updateid">
                                        <input type="hidden" id="disipdid" name="ipdid">
                                        </div>
                                </div>
                            </div><!--./row-->
                        </div><!--./col-md-12-->
                    </div><!--./row-->
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" id="formdishrecordbtn" data-loading-text="Processing..." class="btn btn-info pull-right"> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- discharged summary   -->

<!-- Add Instruction -->
<div class="modal fade" id="add_instruction" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close close_button" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Consultant Register </h4>
            </div>

            <form id="consultant_register_form" accept-charset="utf-8" enctype="multipart/form-data" method="post" >
                <input name="patient_id" placeholder="" id="ins_patient_id" value="980" type="hidden" class="form-control" />
                <div class="scroll-area">
                    <div class="modal-body pb0 ptt10">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Applied Date<small class="req"> *</small>
                                        </label>
                                        <input type="text" name="date" value="" class="form-control datetime">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Instruction Date  <small class="req"> *</small> </label>
                                        <input type="text" id="instruction_date"  name="insdate" value="06/13/2024" class="form-control date">
                                        <input type="hidden" name="ipdid" value="97">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Consultant Doctor<small class="req"> *</small> </label>
                                    <input type="hidden" name="doctor" id="doctor_set">
                                        <select name="doctor_field"  style="width: 100%" id="doctor_field" class="form-control select2">
                                            <option value="">Select</option>
                                                                                        <option   value="11">Amit  Singh (9009)</option>
                                                                                        <option   value="12">Reyan Jain (9011)</option>
                                                                                        <option   value="4">Sansa Gomez (9008)</option>
                                                                                        <option   value="2">Sonia Bush (9002)</option>
                                                                                    </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Instruction <small class="req"> *</small> </label>
                                        <textarea name="instruction" rows="5"class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="">
                                                                        </div>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" id="consultant_registerbtn" data-loading-text="Processing..." class="btn btn-info"><i class="fa fa-check-circle"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_instruction" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Consultant Register </h4>
            </div>

            <form id="editconsultant_register_form" accept-charset="utf-8"  enctype="multipart/form-data" method="post" class="ptt10">
                <div class="scroll-area">
                    <div class="modal-body pt0 pb0">
                            <div class="row">
                                <input type="hidden" name="instruction_id" value="" id="instruction_id">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Applied Date                                                    <small class="req"> *</small>
                                        </label>
                                        <input type="text" name="date" id="ecdate" value="" class="form-control datetime">

                                    </div> </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Instruction Date  <small class="req"> *</small> </label>
                                        <input type="text"  id="ecinsdate" name="insdate" value="06/13/2024" class="form-control date">
                                        <input type="hidden" name="ipdid" value="97">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Consultant Doctor<small class="req"> *</small> </label>
                                    <input type="hidden" name="doctor" id="editdoctor_set">
                                        <select name="doctor_field"  style="width: 100%" id="editdoctor_field" class="form-control select2">
                                            <option value="">Select</option>
                                                                                        <option   value="11">Amit  Singh (9009)</option>
                                                                                        <option   value="12">Reyan Jain (9011)</option>
                                                                                        <option   value="4">Sansa Gomez (9008)</option>
                                                                                        <option   value="2">Sonia Bush (9002)</option>
                                                                                    </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Instruction <small class="req"> *</small> </label>
                                        <textarea name="instruction" id="ecinstruction" rows="5" class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="" id="customfieldconsult">
                                </div>
                            </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="editconsultant_registerbtn" data-loading-text="Processing..." class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="add_nurse_note" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close close_button" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Nurse Note </h4>
            </div>

            <form id="nurse_note_form" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                <input name="patient_id" placeholder="" id="nurse_patient_id" value="980" type="hidden" class="form-control" />
                <input type="hidden" name="ipdid" value="97">
                <div class="scroll-area">
                    <div class="modal-body pb0 ptt10">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Date                                        <small class="req"> *</small>
                                        </label>
                                        <input type="text" name="date" value="" class="form-control datetime">

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Nurse<small class="req"> *</small> </label>
                                    <input type="hidden" name="nurse" id="nurse_set">
                                        <select name="nurse_field"  style="width: 100%" id="nurse_field" class="form-control select2">
                                            <option value="">Select</option>
                                                                                        <option   value="16">April Clinton (9020)</option>
                                                                                        <option   value="10">Natasha  Romanoff (9010)</option>
                                                                                    </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Note <small class="req"> *</small> </label>
                                        <textarea name="note" style="height:50px" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Comment <small class="req"> *</small> </label>
                                        <textarea name="comment" style="height:50px" class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="">
                                                                        </div>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="nurse_notebtn" data-loading-text="Processing..." class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                </div>
            </form>


        </div>
    </div>

</div>


<!-- change bed -->
<div class="modal fade" id="alot_bed" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Bed</h4>
            </div>
         <form id="alot_bed_form" accept-charset="utf-8" enctype="multipart/form-data" method="post" class="ptt10">
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">

                            <div class="alert alert-info">

                            </div>
                            <div class="row">
                                <input name="patient_id" placeholder=""  value="980" type="hidden" class="form-control"   />

                                <div class="col-md-12">
                                    <label>Bed Group<small class="req"> *</small></label>
                                    <select class="form-control" onchange="getBed(this.value, '', 'yes', 'alotbedoption')" name="bedgroup">
                                        <option value="">Select</option>
                                            <option value="1">VIP Ward - Ground  Floor</option>
                                            <option value="2">Private Ward - 3rd Floor</option>
                                            <option value="3">General Ward Male - 3rd Floor</option>
                                            <option value="4">ICU - 2nd Floor</option>
                                            <option value="5">NICU - 2nd Floor</option>
                                            <option value="6">AC (Normal) - 1st Floor</option>
                                            <option value="7">Non AC - 4th Floor</option>
                                    </select>
                                </div>
                                <div class="col-md-12" style="margin-top: 10px;">
                                    <label>Bed No<small class="req"> *</small></label>
                                    <select class="form-control select2" style="width: 100%" id="alotbedoption" name="bedno">
                                    </select>
                                </div>
                                <div class="col-md-12" style="margin-top: 10px;">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="pull-right">
                                    <button type="submit" id="alotbedbtn" data-loading-text="Processing..."  class="btn btn-info">Save</button>
                                </div>
                            </div>

                       </div>
                    </div>
                 </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="view_ot_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modalicon">
                    <div id='action_detail_modal'>

                   </div>


                </div>

                <h4 class="modal-title">Operation Details</h4>
            </div>
            <div class="modal-body min-h-3">
               <div id="show_ot_data"></div>
            </div>
        </div>
    </div>
</div>

<!--lab investigation modal-->
<div class="modal fade" id="viewDetailReportModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-toggle="tooltip" title="" data-dismiss="modal">&times;</button>
                <div class="modalicon">
                    <div id='action_detail_report_modal'>

                   </div>
                </div>
                <h4 class="modal-title" id="modal_head"></h4>
            </div>
            <div class="modal-body ptt10 pb0">
                <div id="reportbilldata"></div>
            </div>
        </div>
    </div>
</div>
<!-- end lab investigation modal-->

<div class="modal fade" id="editpayment_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <form id="editpaymentform" accept-charset="utf-8" method="post">
             <div class="modal-header modal-media-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="modalicon">
                    </div>

                    <h4 class="modal-title">Payment Details</h4>
                </div>
                <div class="modal-body ">
                   <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                    <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date</label><small class="req"> *</small>


                                                <input type="text" name="payment_date" id="payment_date" class="form-control datetime" autocomplete="off">
                                                 <input type="hidden" class="form-control" id="edit_payment_id" name="edit_payment_id" >
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Amount ($)</label><small class="req"> *</small>

                                                <input type="text" name="amount" id="edit_payment" class="form-control" value="">

                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Payment Mode</label>
                                                <select class="form-control payment_mode" name="payment_mode" id="payment_mode">

                                                                                                    <option value="Cash" >Cash</option>
                                                                                                    <option value="Cheque" >Cheque</option>
                                                                                                    <option value="transfer_to_bank_account" >Transfer to Bank Account</option>
                                                                                                    <option value="UPI" >UPI</option>
                                                                                                    <option value="Other" >Other</option>
                                                                                                    <option value="Online" >Online</option>
                                                                                                </select>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                <div class="row cheque_div" style="display: none;">

                                            <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Cheque No</label><small class="req"> *</small>
                                                <input type="text" name="cheque_no" id="edit_cheque_no" class="form-control">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Cheque Date</label><small class="req"> *</small>
                                                <input type="text" name="cheque_date" id="edit_cheque_date" class="form-control date">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Attach Document</label>
                                                <input type="file" class="filestyle form-control"   name="document">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Note</label>
                                                <input type="text" name="note" id="edit_payment_note" class="form-control"/>
                                            </div>
                                        </div>

                                    </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="editpaymentbtn" data-loading-text="Processing..." class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal-chkstatus"  class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
    <form id="form-chkstatus" action="" method="POST">
        <div class="modal-content">
            <div class="">
                <button type="button" class="close modalclosezoom" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body" id="zoom_details">

            </div>
        </div>
    </form>
    </div>
</div>

<!-- Add Doctors -->
<div class="modal fade" id="add_doctor" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Doctor </h4>
            </div>
            <form id="form_doctor" accept-charset="utf-8"  enctype="multipart/form-data" method="post">
                <input type="hidden" name="ipdid_doctor" id="ipdid_doctor" value="97">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">


                           <select placeholder="select" name="doctorOpt[]" class="doctorinput select2" style="width: 100%" multiple id="doctorOpt">

                                <option value="2">
                                        Sonia Bush (9002)
                                </option>

                                <option value="4">
                                        Sansa Gomez (9008)
                                </option>

                                <option value="12">
                                        Reyan Jain (9011)
                                </option>


                            </select>
                             <span class="text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="form_doctorbtn" data-loading-text="Processing..." class="btn btn-info pull-right"> <i class="fa fa-check-circle"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="discharge_revert" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Discharge Revert </h4>
            </div>
            <form id="form_discharge_revert" accept-charset="utf-8"  enctype="multipart/form-data" method="post">
                <input type="hidden" name="ipd_details_id" id="ipd_details_id" value="97">
                <input type="hidden" name="opd_details_id" id="opd_details_id" >
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="exampleInputFile">
                                        Bed Group</label>
                                <div>
                                    <select class="form-control" name='bed_group_id' id='bed_group_id' onchange="getBed(this.value, '', 'yes')">
                                        <option value="">Select</option>
                                                                                    <option value="1">VIP Ward - Ground  Floor</option>
                                                                                    <option value="2">Private Ward - 3rd Floor</option>
                                                                                    <option value="3">General Ward Male - 3rd Floor</option>
                                                                                    <option value="4">ICU - 2nd Floor</option>
                                                                                    <option value="5">NICU - 2nd Floor</option>
                                                                                    <option value="6">AC (Normal) - 1st Floor</option>
                                                                                    <option value="7">Non AC - 4th Floor</option>
                                                                            </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="exampleInputFile">
                                    Bed No.</label><small class="req"> *</small>
                                <div><select class="form-control select2" style="width:100%" name='bed_no' id='bed_nos'>
                                        <option value="">Select</option>

                                    </select>
                                </div>
                                <span class="text-danger"></span></div>
                        </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="exampleInputFile">
                                                        Revert Reason</label><small class="req"> *</small>
                                                    <div>
                                                        <textarea name="discharge_revert_reason" rows="3" class="form-control"></textarea>
                                                    </div>
                                                    <span class="text-danger"></span></div>
                                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="submit_discharge_revert" data-loading-text="Processing..." class="btn btn-info pull-right"> <i class="fa fa-check-circle"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Timeline -->
<div class="modal fade" id="myTimelineModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Timeline</h4>
            </div>
            <form id="add_timeline" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                <div class="scroll-area">
                    <div class="modal-body pb0 ptt10">
                            <div class="row">
                                <div class=" col-md-12">
                                    <div class="form-group">
                                        <label>Title</label><small class="req"> *</small>
                                        <input type="hidden" name="patient_id" id="patient_id" value="980">
                                        <input id="timeline_title" name="timeline_title" placeholder="" type="text" class="form-control" />
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Date                                            <small class="req"> *</small>
                                        </label>
                                        <input id="timeline_date" name="timeline_date" value="06/13/2024" placeholder="" type="text" class="form-control date"  />
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea id="timeline_desc" name="timeline_desc" placeholder=""  class="form-control" rows=6></textarea>
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Attach Document</label>
                                        <div>
                                            <input id="timeline_doc_id" name="timeline_doc" placeholder="" type="file"  class="filestyle form-control" data-height="40" value="" />
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="align-top">Visible to this person</label>
                                        <input id="visible_check" checked="checked" name="visible_check" value="yes" placeholder="" type="checkbox" />
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="add_timelinebtn" data-loading-text="Processing..." class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                </div>
            </form>


        </div>
    </div>
</div>

<div class="modal fade" id="nursenoteEditModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Nurse Note</h4>
            </div>
            <form id="edit_nursenote" accept-charset="utf-8" enctype="multipart/form-data" method="post" class="">
                <div class="scroll-area">
                    <div class="modal-body pt0 pb0">
                        <div class="ptt10">
                            <div class="row">

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Date                                        <small class="req"> *</small>
                                        </label>
                                        <input type="text" name="date" id="endate" value="" class="form-control datetime">
                                        <input type="hidden" name="nurseid" id="nurse_id">

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Nurse<small class="req"> *</small> </label>

                                        <select name="nurse"  style="width: 100%" id="edit_nurse" class="form-control select2">
                                            <option value="">Select</option>
                                                                                        <option   value="16">
                                            April Clinton (9020)
                                            </option>
                                                                                        <option   value="10">
                                            Natasha  Romanoff (9010)
                                            </option>
                                                                                    </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Note <small class="req"> *</small> </label>
                                        <textarea name="note" id="enote" style="height:50px" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Comment <small class="req"> *</small> </label>
                                        <textarea name="comment" id="ecomment" style="height:50px" class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="" id="customfieldnurse" ></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" data-loading-text="Processing..." id="edit_nursenotebtn" class="btn btn-info"><i class="fa fa-check-circle"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="nursenoteCommentModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> Add Comment</h4>
            </div>
            <form id="comment_nursenote" accept-charset="utf-8" enctype="multipart/form-data" method="post" class="">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="row">
                            <!-- <input type="hidden" name="nurseid" id="enurse_id"> -->
                            <input type="hidden" name="nurseid" id="nurse_noteid">
                            <!--  <input type="hidden" name="ipd_id" id="nurse_ipdid"> -->
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Comment <small class="req"> *</small> </label>
                                    <textarea name="comment_staff" id="comment_staff" style="height:100px" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" data-loading-text="Processing..." id="comment_nursenotebtn" class="btn btn-info"><i class="fa fa-check-circle"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="patient_discharge" role="dialog" aria-labelledby="follow_up">
    <div class="modal-dialog modal-mid modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close"  data-dismiss="modal">&times;</button>
               <div class="modalicon">
                     <div id='allpayments_print'>
                    </div>
                </div>
                <h4 class="modal-title">Patient Discharge</h4>
            </div>
            <div class="modal-body pb0" id="patient_discharge_result">

            </div>
        </div>
    </div>
</div>
<!-- Add OT -->
<div class="modal fade" id="add_operationtheatre" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Operation</h4>
            </div>
            <div class="scroll-area">
               <form id="form_operationtheatre" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                    <div class="modal-body pb0 ptt10">
                        <input type="hidden" value="97" name="ipdid" class="form-control" id="ipdid" />
                        <input type="hidden" value="6290" name="case_id" />
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Operation Category</label><small class="req"> *</small>
<select name="operation_category" id="operation_category" class="form-control select2" onchange="getcategory(this.value)" style="width:100%">
                                                    <option value="">Select</option>
                                                                                                        <option value="1">ENT and Oral Surgery</option>
                                                                                                    <option value="6">Gynaecology</option>
                                                                                                    <option value="5">Ophthalmology</option>
                                                                                                    <option value="2">Orthopedic Surgery</option>
                                                                                                    <option value="3">Plastic Surgery</option>
                                                                                                    <option value="4">Thoracic Surgery</option>
                                                                                                    <option value="8">Thoracic Surgery</option>
                                                                                                    <option value="7">Urology</option>
                                                                                                </select>
                                                <span class="text-danger"></span>
                                        </div>
                                     </div>
                                     <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="operation_name">Operation Name</label>
                                                <small class="req"> *</small>
                                               <div>
                                                <select name="operation_name" id="operation_name" class="form-control select2" style="width:100%">

                                                </select>
                                            </div>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>


                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Operation Date</label>
                                                <small class="req"> *</small>
        <input type="text" value="" id="date" name="date" class="form-control datetime">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                    Consultant Doctor</label>
                                                <small class="req"> *</small>
                                                <div><select class="form-control select2"   style="width:100%" id='consultant_doctorid' name='consultant_doctor' >
                                                        <option value="">Select</option>
                                                                                                                    <option value="11" >Amit  Singh (9009)</option>
                                                                                                                                <option value="12" >Reyan Jain (9011)</option>
                                                                                                                                <option value="4" >Sansa Gomez (9008)</option>
                                                                                                                                <option value="2" >Sonia Bush (9002)</option>
                                                                                                                        </select>

                                                </div>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Assistant Consultant 1</label>
                                                <input type="text" name="ass_consultant_1" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Assistant Consultant 2</label>
                                                <input type="text" name="ass_consultant_2" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Anesthetist</label>
                                                <input type="text" name="anesthetist" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Anesthesia Type</label>
                                                <input type="text" name="anaethesia_type" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>OT Technician</label>
                                                <input type="text" name="ot_technician" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>OT Assistant</label>
                                                <input type="text" value="" name="ot_assistant" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Remark</label>
                                                <textarea name="ot_remark" id="ot_remark" class="form-control" ></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Result</label>
                                                <textarea name="ot_result" id="ot_result" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="">
                                                                                    </div>


                                </div>
                    </div>

                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" id="form_addoperationtheatrbtn" data-loading-text="Processing..." class="btn btn-info"><i class="fa fa-check-circle"></i> Save</button>
                    </div>
                </div>
            </form>

            </div> <!-- scroll-area -->
        </div>
    </div>
</div>
<!-- Edit Operation Theatre -->

<div class="modal fade" id="edit_operationtheatre" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Operation</h4>
            </div>
               <form id="form_editoperationtheatre" accept-charset="utf-8" enctype="multipart/form-data" method="post" class="ptt10">
                    <div class="scroll-area">
                        <div class="modal-body pt0 pb0">
                                <div class="row">
                                      <input type="hidden" value="97" name="opdid" class="form-control" id="opdid" />
                                    <input type="hidden" value="" name="otid" class="form-control" id="otid" />
                                    <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Operation Category</label> <small class="req"> *</small>

                                                <select name="eoperation_category" id="eoperation_category" class="form-control select2" onchange="getcategory(this.value)" style="width:100%">
                                                    <option value="">Select</option>
                                                                                                        <option value="1">ENT and Oral Surgery</option>
                                                                                                    <option value="6">Gynaecology</option>
                                                                                                    <option value="5">Ophthalmology</option>
                                                                                                    <option value="2">Orthopedic Surgery</option>
                                                                                                    <option value="3">Plastic Surgery</option>
                                                                                                    <option value="4">Thoracic Surgery</option>
                                                                                                    <option value="8">Thoracic Surgery</option>
                                                                                                    <option value="7">Urology</option>
                                                                                                </select>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>

                                     <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="operation_name">Operation Name</label>
                                                <small class="req"> *</small>

                                                <select name="eoperation_name" id="eoperation_name" class="form-control select2" style="width:100%">
                                                    <option value="">Select</option>
                                                                                                        <option value="7"> Facelift Surgery</option>
                                                                                                    <option value="5"> Tooth extraction</option>
                                                                                                    <option value="6">Arthroscopic surgery including ACL repair</option>
                                                                                                    <option value="1">Bronchoscopy</option>
                                                                                                    <option value="3">Cataract extraction and most other ophthalmological procedures</option>
                                                                                                    <option value="8">Coronary artery bypass</option>
                                                                                                    <option value="4">Dilation and curettage</option>
                                                                                                    <option value="2">Hydrocele and varicocele excision</option>
                                                                                                </select>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>


                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Operation Date</label>
                                                <small class="req"> *</small>
                                                <input type="text" value="" id="edate" name="date" class="form-control datetime">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                    Consultant Doctor</label> <small class="req"> *</small>
                                                <div><select class="form-control select2"   style="width:100%" id='econsultant_doctorid' name='consultant_doctor' >
                                                        <option value="">Select</option>
                                                                                                                    <option value="11" >Amit  Singh (9009)</option>
                                                                                                                                <option value="12" >Reyan Jain (9011)</option>
                                                                                                                                <option value="4" >Sansa Gomez (9008)</option>
                                                                                                                                <option value="2" >Sonia Bush (9002)</option>
                                                                                                                        </select>

                                                </div>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Assistant Consultant 1</label>
                                                <input type="text" name="ass_consultant_1" id="eass_consultant_1" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Assistant Consultant 2</label>
                                                <input type="text" name="ass_consultant_2"  id="eass_consultant_2" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Anesthetist</label>
                                                <input type="text" name="anesthetist" id="eanesthetist" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Anaethesia Type</label>
                                                <input type="text" name="anaethesia_type" id="eanaethesia_type" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>OT Technician</label>
                                                <input type="text" name="ot_technician" id="eot_technician" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>OT Assistant</label>
                                                <input type="text" value="" name="ot_assistant"  id="eot_assistant"  class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Remark</label>
                                                <textarea name="eot_remark" id="eot_remark" class="form-control" ></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Result</label>
                                                <textarea name="eot_result" id="eot_result" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div id="custom_field_ot">

                                        </div>

                                </div>
                        </div>
                  </div><!-- scroll-area -->
               <div class="modal-footer">
                    <div class="pull-right">
                    <button type="submit" id="form_editoperationtheatrebtn" data-loading-text="Processing..." class="btn btn-info"><i class="fa fa-check-circle"></i> Save</button>
                   </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Timeline -->
<div class="modal fade" id="myTimelineEditModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Timeline</h4>
            </div>
            <form id="edit_timeline" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                <div class="scroll-area">
                    <div class="modal-body pb0">
                        <div class="row">
                            <div class=" col-md-12">
                                <div class="form-group">
                                        <label>Title</label><small class="req"> *</small>
                                        <input type="hidden" name="patient_id" id="epatientid" value="">
                                        <input type="hidden" name="timeline_id" id="etimelineid" value="">
                                        <input id="etimelinetitle" name="timeline_title" placeholder="" type="text" class="form-control"  />
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Date</label><small class="req"> *</small>

                                        <input type="text" name="timeline_date" class="form-control date" id="etimelinedate"/>
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea id="timelineedesc" name="timeline_desc" placeholder=""  class="form-control"></textarea>
                                        <span class="text-danger"></span>
                                    </div>

                                    <div class="form-group">
                                        <label>Attach Document</label>
                                        <div><input id="etimeline_doc_id" name="timeline_doc" placeholder="" type="file"  class="filestyle form-control" data-height="40"  value="" />
                                            <span class="text-danger"></span></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="align-top">Visible to this person</label>
                                        <input id="evisible_check"  name="visible_check" value="yes" placeholder="" type="checkbox" />

                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" data-loading-text="Processing..." id="edit_timelinebtn" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Diagnosis -->
<div class="modal fade" id="edit_diagnosis" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Diagnosis</h4>
            </div>
            <form id="form_editdiagnosis" accept-charset="utf-8"  enctype="multipart/form-data" method="post" class="ptt10">
                <div class="modal-body pt0 pb0">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>
Report Type</label><small class="req"> *</small>
                                    <input type="text" name="report_type" class="form-control" id="ereporttype" />
                                    <input type="hidden" value="" name="diagnosis_id" class="form-control" id="eid" />
                                    <input type="hidden" value="" name="diagnosispatient_id" class="form-control" id="epatient_id" />
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>
Report Date</label><small class="req"> *</small>
                                    <input type="text" name="report_date" class="form-control date" id="ereportdate"/>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="align-top">Document</label> <input type="file" class="form-control filestyle" name="report_document" id="ereportdocument" />
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="align-top">Report Center Name</label> <input type="text" class="form-control" name="report_center" id="ereportcenter" />
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control" id="edescription"></textarea>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" id="form_editdiagnosisbtn" data-loading-text="Processing..." class="btn btn-info">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Prescription -->
<div class="modal fade" id="add_prescription" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close pupclose" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="edit_prescription_title">Add Prescription</h4>
            </div>
            <form id="form_prescription" accept-charset="utf-8" enctype="multipart/form-data" method="post">
            <div class="pup-scroll-area">
                <div class="modal-body pt0 pb0">

                </div> <!--./modal-body-->
            </div>
            <div class="box-footer sticky-footer">
                <div class="pull-right">


                     <button type="submit" name="save_print" value="save_print" data-loading-text="Processing..." class="btn btn-info"><i class="fa fa-print"></i> Save & Print                        </button>
                    <button type="submit" name="save" value="save" data-loading-text="Processing..." class="btn btn-info"><i class="fa fa-check-circle"></i> Save                     </button>


                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- -->
<div class="modal fade" id="viewModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Patient Details</h4>
            </div>
            <form id="formrevisit" accept-charset="utf-8" enctype="multipart/form-data" method="post" class="ptt10">
                <div class="modal-body pt0 ">
                            <div class="row">
                               <div class="col-md-12">
                                    <div class="table-responsive">
                                    <table class="table mb0 table-striped table-bordered examples">
                                        <tr>
                                            <th width="15%">Patient Name</th>
                                            <td width="35%"><span id="patient_name"></span> (<span id='patients_id'></span>)</td>
                                            <th width="15%">Guardian Name</th>
                                            <td width="35%"><span id='guardian_name'></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Gender</th>
                                            <td width="35%"><span id='gen'></span></td>
                                            <th width="15%">Marital Status</th>
                                            <td width="35%"><span id="marital_status"></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Phone</th>
                                            <td width="35%"><span id="contact"></span></td>
                                            <th width="15%">Email</th>
                                            <td width="35%"><span id='email' style="text-transform: none"></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Address</th>
                                            <td width="35%"><span id='patient_address'></span></td>
                                            <th width="15%">Age</th>
                                            <td width="35%"><span id="age"></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Blood Group</th>
                                            <td width="35%"><span id="blood_group"></span></td>
                                            <th width="15%">Height</th>
                                            <td width="35%"><span id='height'></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Weight</th>
                                            <td width="35%"><span id="weight"></span></td>
                                            <th width="15%">Temperature</th>
                                            <td width="35%"><span id='temperature'></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Respiration</th>
                                            <td width="35%"><span id="respiration"></span></td>
                                            <th width="15%">Pulse</th>
                                            <td width="35%"><span id='pulse'></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">BP</th>
                                            <td width="35%"><span id='patient_bp'></span></td>
                                            <th width="15%">Symptoms</th>
                                            <td width="35%"><span id='symptoms'></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Known Allergies</th>
                                            <td width="35%"><span id="known_allergies"></span></td>
                                            <th width="15%">Admission Date</th>
                                            <td width="35%"><span id="admission_date"></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Case</th>
                                            <td width="35%"><span id='case'></span></td>
                                            <th width="15%">Old Patient</th>
                                            <td width="35%"><span id='old_patient'></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Casualty</th>
                                            <td width="35%"><span id="casualty"></span></td>
                                            <th width="15%">Reference</th>
                                            <td width="35%"><span id="refference"></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">TPA</th>
                                            <td width="35%"><span id="organisation"></span></td>
                                            <th width="15%">Bed Group</th>
                                            <td width="35%"><span id="bed_group"></span></td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Consultant Doctor</th>
                                            <td width="35%"><span id='doc'></span></td>
                                            <th width="15%">Bed Number</th>
                                            <td width="35%"><span id='bed_name'></span></td>
                                        </tr>

                                    </table>
                                </div>
                                <div id="field_data">
                                </div>
                               </div>
                            </div>
                        </form>
            </div>
        </div>
    </div>
</div>

<!-- -->
<div class="modal fade" id="prescriptionview" role="dialog" aria-labelledby="follow_up">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close sss" data-dismiss="modal">&times;</button>
                <div class="modalicon">
                    <div id='edit_deleteprescription'>
                    </div>
                </div>
                <h4 class="modal-title">Prescription</h4>
            </div>
            <div class="modal-body pt0 pb0" id="getdetails_prescription"></div>
        </div>
    </div>
</div>

<!-- -->
<div class="modal fade" id="myPaymentModal" role="dialog" aria-labelledby="myModalLabel">
    <form id="add_payment" accept-charset="utf-8" method="post" class="ptt10">
        <div class="modal-dialog modal-mid" role="document">
            <div class="modal-content modal-media-content">
                <div class="modal-header modal-media-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Payment</h4>
                </div>
                <!-- <div class="scroll-area"> -->
                    <div class="modal-body pb0 ptt10">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date</label><small class="req"> *</small>
                                    <input type="text" name="payment_date" id="date" class="form-control datetime">
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Amount ($)</label><small class="req"> *</small>
                                    <input type="text" name="amount" id="amount" class="form-control" value="-1800">

                                    <input type="hidden" name="net_amount" class="form-control" value="-1800">
                                    <input type="hidden" name="case_reference_id" id="case_reference_id" class="form-control" value="6290">
                                   <input type="hidden" name="patient_id"  class="form-control" value="980">
                                    <input type="hidden" name="ipdid" value="97">
                                    <input type="hidden" name="total" id="total" class="form-control">
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payment Mode</label>
                                    <select class="form-control payment_mode" name="payment_mode">
                                                                                    <option value="Cash" >Cash</option>
                                                                                    <option value="Cheque" >Cheque</option>
                                                                                    <option value="transfer_to_bank_account" >Transfer to Bank Account</option>
                                                                                    <option value="UPI" >UPI</option>
                                                                                    <option value="Other" >Other</option>
                                                                                    <option value="Online" >Online</option>
                                                                            </select>
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row cheque_div" style="display: none;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cheque No</label><small class="req"> *</small>
                                    <input type="text" name="cheque_no" id="cheque_no" class="form-control">
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cheque Date</label> <small class="req"> *</small>
                                    <input type="text" name="cheque_date" id="cheque_date" class="form-control date">
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Attach Document</label>
                                    <input type="file" id="payment_file" class="filestyle form-control"   name="document">
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Note</label>
                                    <textarea  name="note" id="note" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="add_paymentbtn" data-loading-text="Processing..." class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                    </div>
                <!-- </div> -->
            </div>
        </div>

    </form>
</div>
<!-- -->

<!-- -->
<div class="modal fade" id="myMedicationModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Add Medication Dose</h4>
                            </div>
        <form id="add_medicationdose" accept-charset="utf-8" method="post" class="ptt10">
            <div class="scroll-area">
                <div class="modal-body pt0 pb0">
                        <div class="row">
                                    <input type="hidden" name="ipdid" id="mipdid" value="97" >
                                    <input type="hidden" name="medicine_name_id" id="mpharmacy_id" value="" >
                                    <input type="hidden" name="date"  id="mdate" value="" >
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date</label><small class="req"> *</small>
                                            <input type="text" name="date" id="add_dose_date" class="form-control date">
                                            <span class="text-danger"></span>
                                            <input type="hidden" name="ipdid" value="97">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pwd">Time</label>
                                            <div class="bootstrap-timepicker">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" name="time" class="form-control timepicker" id="add_dose_time" value="">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Medicine Category</label> <small class="req"> *</small>
                                            <select class="form-control medicine_category_medication select2" style="width:100%" id="add_dose_medicine_category" name='medicine_category_id'>
                                                <option value="">Select                                                </option>
                                                                                                        <option value="1">Syrup                                                    </option>
                                                                                                                <option value="2">Capsule                                                    </option>
                                                                                                                <option value="3">Injection                                                    </option>
                                                                                                                <option value="4">Ointment                                                    </option>
                                                                                                                <option value="5">Cream                                                    </option>
                                                                                                                <option value="6">Surgical                                                    </option>
                                                                                                                <option value="7">Drops                                                    </option>
                                                                                                                <option value="8">Inhalers                                                    </option>
                                                                                                                <option value="9">Implants / Patches                                                    </option>
                                                                                                                <option value="10">Liquid                                                    </option>
                                                                                                                <option value="11">Preparations                                                    </option>
                                                                                                                <option value="12">Diaper	                                                    </option>
                                                                                                                <option value="13">Tablet                                                    </option>
                                                                                                            </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Medicine Name</label> <small class="req"> *</small>
                                        <select class="form-control select2 medicine_name_medication" style="width:100%"  id="add_dose_medicine_id" name='medicine_name_id'>
                                                <option value="">Select                                                    </option>
                                                </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Dosage</label> <small class="req"> *</small>
                                        <select class="form-control select2 dosage_medication" style="width:100%"  id="mdosage" onchange="" name='dosage'>
                                                <option value="">Select                                                    </option>
                                                </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Remarks</label>
                                            <textarea  name="remark" id="remark" class="form-control"></textarea>

                                        </div>
                                    </div>
                                </div>
                        </div>

                  </div>
                   <div class="modal-footer">
                        <button type="submit" id="add_medicationdosebtn" data-loading-text="Processing..." class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                    </div>
            </form>
        </div>
    </div>
</div>
<!-- -->


<div class="modal fade" id="myaddMedicationModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Medication Dose</h4>
            </div>
        <form id="add_medication" accept-charset="utf-8" method="post" class="ptt10">
            <div class="scroll-area">
                <div class="modal-body pt0 pb0">

                        <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date</label><small class="req"> *</small>
                                            <input type="text" name="date" id="date" class="form-control date">
                                            <span class="text-danger"></span>
                                            <input type="hidden" name="ipdid" value="97">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pwd">Time</label>
                                            <div class="bootstrap-timepicker">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" name="time" class="form-control timepicker" id="mtime" value="">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Medicine Category</label> <small class="req"> *</small>
                                            <select class="form-control medicine_category_medication select2" style="width:100%" id="mmedicine_category_id" name='medicine_category_id'>
                                                <option value="">Select                                                </option>
                                                                                                        <option value="1">Syrup                                                    </option>
                                                                                                                <option value="2">Capsule                                                    </option>
                                                                                                                <option value="3">Injection                                                    </option>
                                                                                                                <option value="4">Ointment                                                    </option>
                                                                                                                <option value="5">Cream                                                    </option>
                                                                                                                <option value="6">Surgical                                                    </option>
                                                                                                                <option value="7">Drops                                                    </option>
                                                                                                                <option value="8">Inhalers                                                    </option>
                                                                                                                <option value="9">Implants / Patches                                                    </option>
                                                                                                                <option value="10">Liquid                                                    </option>
                                                                                                                <option value="11">Preparations                                                    </option>
                                                                                                                <option value="12">Diaper	                                                    </option>
                                                                                                                <option value="13">Tablet                                                    </option>
                                                                                                            </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Medicine Name</label> <small class="req"> *</small>
                                        <select class="form-control select2 medicine_name_medication" style="width:100%"  id="mmedicine_id" name='medicine_name_id'>
                                                <option value="">Select                                                    </option>
                                                </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Dosage</label> <small class="req"> *</small>
                                        <select class="form-control select2 dosage_medication" style="width:100%"  id="dosage" onchange="get_dosagename(this.value)" name='dosage'>
                                                <option value="">Select                                                    </option>
                                                </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Remarks</label>
                                            <textarea  name="remark" id="remark" class="form-control"></textarea>

                                        </div>
                                    </div>
                                </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" id="add_medicationbtn" data-loading-text="Processing..." class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- -->

<!-- -->
<div class="modal fade" id="myMedicationDoseModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modalicon">
                    <div id='edit_delete'></div>
                </div>
                <h4 class="modal-title">Edit Medication Dose</h4>
            </div>

                <form id="update_medication" accept-charset="utf-8" method="post" class="ptt10">
                    <div class="modal-body pt0 pb0">
                        <input type="hidden" name="medication_id" class="" id="medication_id" value="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date</label><small class="req"> *</small>
                                        <input type="text" name="date" id="date_edit_medication" class="form-control date">
                                        <span class="text-danger"></span>
                                        <input type="hidden" name="ipdid" value="97">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd">Time</label>
                                        <div class="bootstrap-timepicker">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" name="time" class="form-control timepicker" id="dosagetime" value="">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Medicine Category</label> <small class="req"> *</small>
                                        <select class="form-control medicine_category_medication select2" style="width:100%" id="mmedicine_category_edit_id" name='medicine_category_id'>
                                            <option value="">Select                                            </option>
                                                                                                <option value="1">Syrup                                                </option>
                                                                                                        <option value="2">Capsule                                                </option>
                                                                                                        <option value="3">Injection                                                </option>
                                                                                                        <option value="4">Ointment                                                </option>
                                                                                                        <option value="5">Cream                                                </option>
                                                                                                        <option value="6">Surgical                                                </option>
                                                                                                        <option value="7">Drops                                                </option>
                                                                                                        <option value="8">Inhalers                                                </option>
                                                                                                        <option value="9">Implants / Patches                                                </option>
                                                                                                        <option value="10">Liquid                                                </option>
                                                                                                        <option value="11">Preparations                                                </option>
                                                                                                        <option value="12">Diaper	                                                </option>
                                                                                                        <option value="13">Tablet                                                </option>
                                                                                                    </select>
                                        <span class="text-danger"></span>
                                    </div>
                                </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Medicine Name</label> <small class="req"> *</small>
                                    <select class="form-control select2 medicine_name_medication" style="width:100%"  id="mmedicine_edit_id" name='medicine_name_id'>
                                            <option value="">Select                                                </option>
                                            </select>
                                        <span class="text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Dosage</label> <small class="req"> *</small>
                                        <select class="form-control  select2" style="width:100%" id="medicine_dose_edit_id" name='dosage_id'>
                                        <option value="">Select                                        </option>
                                                                                <option value="1">1  (ML)                                                </option>

                                                                                <option value="2">1 MG                                                </option>

                                                                                <option value="3">1 (ML)                                                </option>

                                                                                <option value="4">1 Day                                                </option>

                                                                                <option value="5">1/2 Day                                                </option>

                                                                                <option value="6">1 Hour                                                </option>

                                                                                <option value="7">0.5 (ML)                                                </option>

                                                                                <option value="8">1 Day                                                </option>

                                                                                </select>
                                        <span class="text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <textarea  name="remark" id="medicine_dosage_remark" class="form-control"></textarea>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" id="update_medicationbtn" data-loading-text="Processing..." class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                        </div>

                </form>
        </div>
    </div>
</div>
<!-- -->

<!--Add Charges-->
<div class="modal fade" id="myChargesModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close pupclose" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Charges</h4>
            </div>
            <form id="add_charges" accept-charset="utf-8" method="post" class="ptt10">
                <div class="pup-scroll-area">
                    <div class="modal-body pb0 pt0">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <input type="hidden" name="patient_id" value="980">
                                <input type="hidden" name="ipdid" value="97" >
                                <input type="hidden" name="patient_charge_id" id="editpatient_charge_id" value="0" >
                                <input type="hidden" name="organisation_id" id="organisation_id" value="4" >
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Charge Type</label><small class="req"> *</small>
                                            <select name="charge_type" id="add_charge_type" style="width: 100%"  class="form-control charge_type select2">
                                                <option value="">Select</option>
                                                                                                    <option value="3">
                                                    IPD                                                    </option>
                                                                                                <option value="8">
                                                    Procedures                                                    </option>
                                                                                                <option value="10">
                                                    Supplier                                                    </option>
                                                                                                <option value="11">
                                                    Operations                                                    </option>
                                                                                                <option value="12">
                                                    Others                                                    </option>
                                                                                        </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Charge Category</label><small class="req"> *</small>
                                            <select name="charge_category" id="charge_category" style="width: 100%" class="form-control charge_category select2">
                                                <option value="">Select</option>
                                            </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Charge Name</label><small class="req"> *</small>
                                            <select name="charge_id" id="charge_id" style="width: 100%" class="form-control charge select2" >
                                                <option value="">Select</option>
                                            </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Standard Charge ($)</label>
                                            <input type="text" readonly name="standard_charge" id="standard_charge" class="form-control" value="">
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>TPA Charge ($)</label>
                                            <input type="text" readonly name="schedule_charge" id="schedule_charge" placeholder="" class="form-control" value="">
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Qty</label><small class="req"> *</small>
                                        <input type="text" name="qty" id="qty" class="form-control" >
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                    <div class="row">
                                        <div class="col-sm-5 mb10">
                                            <table class="printablea4">
                                                <tr>
                                                    <th width="40%">Total ($)</th>
                                                    <td width="60%" colspan="2" class="text-right ipdbilltable">
                                                        <input type="text" placeholder="Total" value="0" name="apply_charge" id="apply_charge" style="width: 30%; float: right" class="form-control total" readonly />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Tax ($)</th>
                                                    <td class="text-right ipdbilltable">
                                                        <h4 style="float: right;font-size: 12px; padding-left: 5px;"> %</h4>
                                                        <input type="text" placeholder="Tax" name="charge_tax" id="charge_tax" class="form-control charge_tax" readonly style="width: 70%; float: right;font-size: 12px;"/>
                                                    </td>
                                                    <td class="text-right ipdbilltable">
                                                        <input type="text" placeholder="Tax" name="tax" value="0" id="tax" style="width: 50%; float: right" class="form-control tax" readonly/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Net Amount ($)</th>
                                                    <td colspan="2" class="text-right ipdbilltable">
                                                        <input type="text" placeholder="Net Amount" value="0" name="amount" id="final_amount" style="width: 30%; float: right" class="form-control net_amount" readonly/>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>Charge Note</label>
                                                        <textarea name="note" id="edit_note" rows="3" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!--./col-sm-6-->

                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Date</label> <small class="req"> *</small>
                                                <input id="charge_date" name="date" placeholder="" type="text" class="form-control datetime" />
                                            </div>
                                            <button type="submit" data-loading-text="Processing..." name="charge_data" value="add" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Add</button>
                                        </div>

                                    </div><!--./row-->

                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12" class="table-responsive">
                                    <table class="table table-striped table-bordered ">
                                        <tr>
                                            <th>Date</th>
                                            <th>Charge Type</th>
                                            <th>Charge Category</th>
                                            <th>Charge Name</th>
                                            <th>Standard Charge ($)</th>
                                            <th>TPA Charge ($)</th>
                                            <th>Qty</th>
                                            <th>Total ($)</th>
                                            <th>Tax ($)</th>
                                            <th>Net Amount ($)</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                        <tbody id="preview_charges">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div><!--./scroll-area-->
                    <div class="modal-footer sticky-footer">
                        <div class="pull-right">


                            <button type="submit" data-loading-text="Processing..." value="save" name="charge_data" class="btn btn-info"><i class="fa fa-check-circle"></i> Save</button>
                        </div>
                    </div>
                </form>
        </div>
    </div>
</div>

<div class="modal fade" id="myChargeseditModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Charges</h4>
            </div>
         <form id="edit_charges" accept-charset="utf-8" method="post" class="ptt10">
            <div class="scroll-area">
                <div class="modal-body pb0 pt0">
                     <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                                <input type="hidden" name="patient_id" value="980">
                                    <!-- <input type="hidden" name="org_id" id="org_id" value="0"> -->
                                    <input type="hidden" name="ipdid" value="97" >
                                    <input type="hidden" name="patient_charge_id" id="patient_charge_id" value="0" >
                                        <input type="hidden" name="organisation_id" id="organisation_id" value="4" >
                                <div class="row">

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Charge Type</label><small class="req"> *</small>

                                            <select name="charge_type" id="edit_charge_type" class="form-control charge_type select2">
                                                <option value="">Select</option>
                                                                                                    <option value="3">
                                                    IPD                                                    </option>
                                                                                                <option value="8">
                                                    Procedures                                                    </option>
                                                                                                <option value="10">
                                                    Supplier                                                    </option>
                                                                                                <option value="11">
                                                    Operations                                                    </option>
                                                                                                <option value="12">
                                                    Others                                                    </option>
                                                                                        </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Charge Category</label><small class="req"> *</small>
                                            <select name="charge_category" id="editcharge_category" style="width: 100%" class="form-control charge_category select2">
                                                <option value="">Select</option>
                                            </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Charge Name</label><small class="req"> *</small>
                                            <select name="charge_id" id="editcharge_id" style="width: 100%" class="form-control charge select2" >
                                                <option value="">Select</option>
                                            </select>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>


                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Standard Charge ($)</label>
                                            <input type="text" readonly name="standard_charge" id="editstandard_charge" class="form-control" value="">
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>TPA Charge ($)</label>
                                            <input type="text" readonly name="schedule_charge" id="editschedule_charge" placeholder="" class="form-control" value="">
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Qty</label><small class="req"> *</small>
                                        <input type="text" name="qty" id="editqty" class="form-control" >
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>

                                    <div class="divider"></div>

                                        <div class="row">
                                            <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Date</label> <small class="req"> *</small>
                                            <input id="editcharge_date" name="date" placeholder="" type="text" class="form-control datetime" />
                                        </div>
                                    </div>
                                            <div class="col-sm-3">
                                                <div class="row">

                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label>Charge Note</label>

                                                            <textarea name="note" id="enote" rows="3" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!--./col-sm-6-->


                                            <div class="col-sm-6 mb10">

                                                <table class="printablea4">


                                                    <tr>
                                                        <th width="40%">Total ($)</th>
                                                        <td width="60%" colspan="2" class="text-right ipdbilltable">
                                                        <input type="text" placeholder="Total" value="0" name="apply_charge" id="editapply_charge" style="width: 30%; float: right" class="form-control total" readonly /></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tax ($)</th>
                                                        <td class="text-right ipdbilltable">
                                                            <h4 style="float: right;font-size: 12px; padding-left: 5px;"> %</h4>
                                                    <input type="text" placeholder="Tax" name="charge_tax" id="editcharge_tax" class="form-control charge_tax" readonly style="width: 70%; float: right;font-size: 12px;"/></td>

                                                        <td class="text-right ipdbilltable">
                                                            <input type="text" placeholder="Tax" name="tax" value="0" id="edittax" style="width: 50%; float: right" class="form-control tax" readonly/>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Net Amount ($)</th>
                                                        <td colspan="2" class="text-right ipdbilltable">
                                                            <input type="text" placeholder="Net Amount" value="0" name="amount" id="editfinal_amount" style="width: 30%; float: right" class="form-control net_amount" readonly/></td>
                                                    </tr>
                                                </table>


                                            </div>

                                        </div><!--./row-->
                        </div>

                       </div>
                     </div>
                   </div><!--./scroll-area-->
                    <div class="box-footer">


                 <button type="submit"  data-loading-text="Processing..." name="charge_data" value="add" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
            </div>

                </form>

        </div>
    </div>
</div>
<!-- -->
<div class="modal fade" id="myModaledit"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100 modalfullmobile" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="row">
                    <div class="col-sm-6 col-xs-8">
                        <div class="form-group15">
                            <div>
                                <select onchange="get_ePatientDetails(this.value)" disabled class="form-control select2" style="width:100%" id="evaddpatient_id" name='' >
                                    <option value="">Select</option>
                                                                            <option value="1072" >Aadish Mody (1072)                                        </option>
                                                                            <option value="1073" >Aditya Mannan (1073)                                        </option>
                                                                            <option value="994" >Albert (994)                                        </option>
                                                                            <option value="1057" >Albert (1057)                                        </option>
                                                                            <option value="987" >Alfred (987)                                        </option>
                                                                            <option value="1001" >Alice (1001)                                        </option>
                                                                            <option value="896" >Alisha Knowles (896)                                        </option>
                                                                            <option value="1045" >Alwin (1045)                                        </option>
                                                                            <option value="954" >Amit Deshpande (954)                                        </option>
                                                                            <option value="1070" >Ananya Sarma (1070)                                        </option>
                                                                            <option value="1069" >Anees Kakar (1069)                                        </option>
                                                                            <option value="873" >Angela Clark (873)                                        </option>
                                                                            <option value="924" >Angela S. Dildy (924)                                        </option>
                                                                            <option value="493" >Ankit Singh (493)                                        </option>
                                                                            <option value="1009" >Archie (1009)                                        </option>
                                                                            <option value="1021" >Archie (1021)                                        </option>
                                                                            <option value="190" >Arnav Joshi (190)                                        </option>
                                                                            <option value="872" >Arthur Wood (872)                                        </option>
                                                                            <option value="578" >Ashutosh pandey (578)                                        </option>
                                                                            <option value="1014" >Averitt (1014)                                        </option>
                                                                            <option value="1081" >Baber Devan (1081)                                        </option>
                                                                            <option value="1083" >Babita Krishnamurthy (1083)                                        </option>
                                                                            <option value="1082" >Bharat Naidu (1082)                                        </option>
                                                                            <option value="1024" >Bowie (1024)                                        </option>
                                                                            <option value="1029" >Brennen (1029)                                        </option>
                                                                            <option value="1017" >Briggs (1017)                                        </option>
                                                                            <option value="914" >Callie L. Milam (914)                                        </option>
                                                                            <option value="641" >Cameron Martin (641)                                        </option>
                                                                            <option value="533" >Candey wood (533)                                        </option>
                                                                            <option value="1074" >Carolyn G. Rister (1074)                                        </option>
                                                                            <option value="827" >Carolyn Wright (827)                                        </option>
                                                                            <option value="1022" >Cavan (1022)                                        </option>
                                                                            <option value="940" >Charles J. Wagner (940)                                        </option>
                                                                            <option value="937" >Christy D. Murray (937)                                        </option>
                                                                            <option value="1053" >Clinton (1053)                                        </option>
                                                                            <option value="1019" >Corbin (1019)                                        </option>
                                                                            <option value="843" >Cristian Messina (843)                                        </option>
                                                                            <option value="509" >Daniel Wood (509)                                        </option>
                                                                            <option value="991" >Darcy (991)                                        </option>
                                                                            <option value="1059" >Darcy (1059)                                        </option>
                                                                            <option value="1026" >Darwin (1026)                                        </option>
                                                                            <option value="1058" >Darwin (1058)                                        </option>
                                                                            <option value="539" >David Hussan (539)                                        </option>
                                                                            <option value="1034" >Destan (1034)                                        </option>
                                                                            <option value="879" >Devendra Kothari (879)                                        </option>
                                                                            <option value="484" >Dhawan Kulkarni (484)                                        </option>
                                                                            <option value="1025" >Dhuruv Rana (1025)                                        </option>
                                                                            <option value="1061" >Ebenezer (1061)                                        </option>
                                                                            <option value="986" >Edison (986)                                        </option>
                                                                            <option value="921" >Ehsaan Lanka (921)                                        </option>
                                                                            <option value="868" >Elliot Coates (868)                                        </option>
                                                                            <option value="1054" >Ellis (1054)                                        </option>
                                                                            <option value="984" >Elvio (984)                                        </option>
                                                                            <option value="1042" >Ember (1042)                                        </option>
                                                                            <option value="642" >Emma Watson (642)                                        </option>
                                                                            <option value="1027" >Ennis (1027)                                        </option>
                                                                            <option value="288" >Evander Jonh (288)                                        </option>
                                                                            <option value="1032" >Fiora (1032)                                        </option>
                                                                            <option value="1041" >Ford (1041)                                        </option>
                                                                            <option value="1051" >Frank (1051)                                        </option>
                                                                            <option value="489" >Gaurav Patel (489)                                        </option>
                                                                            <option value="643" >Gaurav Shrivastava (643)                                        </option>
                                                                            <option value="985" >George (985)                                        </option>
                                                                            <option value="1062" >George (1062)                                        </option>
                                                                            <option value="878" >George Parker (878)                                        </option>
                                                                            <option value="990" >George R. Garcia (990)                                        </option>
                                                                            <option value="1063" >Gladwin (1063)                                        </option>
                                                                            <option value="1055" >Goodwin (1055)                                        </option>
                                                                            <option value="845" >Hanna Salo (845)                                        </option>
                                                                            <option value="998" >Hardy (998)                                        </option>
                                                                            <option value="1052" >Hardy (1052)                                        </option>
                                                                            <option value="1013" >Harper (1013)                                        </option>
                                                                            <option value="884" >Harpreet Varkey (884)                                        </option>
                                                                            <option value="1008" >Harrison (1008)                                        </option>
                                                                            <option value="655" >Harry Martin (655)                                        </option>
                                                                            <option value="627" >harry mount (627)                                        </option>
                                                                            <option value="1010" >Hazel (1010)                                        </option>
                                                                            <option value="877" >Henry Cooper (877)                                        </option>
                                                                            <option value="1044" >Huxley (1044)                                        </option>
                                                                            <option value="1037" >Isabell (1037)                                        </option>
                                                                            <option value="1071" >Jagat Nawal (1071)                                        </option>
                                                                            <option value="532" >Jamesh Wood (532)                                        </option>
                                                                            <option value="584" >Jamesh Wood (584)                                        </option>
                                                                            <option value="1033" >Jarad (1033)                                        </option>
                                                                            <option value="1076" >Jasmine Rudd (1076)                                        </option>
                                                                            <option value="1065" >Jasper (1065)                                        </option>
                                                                            <option value="781" >Jeffrey M. Ransom (781)                                        </option>
                                                                            <option value="1011" >Jemma (1011)                                        </option>
                                                                            <option value="2" >John Marshall (2)                                        </option>
                                                                            <option value="992" >Jones (992)                                        </option>
                                                                            <option value="1000" >Jordon (1000)                                        </option>
                                                                            <option value="1049" >Josie (1049)                                        </option>
                                                                            <option value="887" >kailash punti (887)                                        </option>
                                                                            <option value="639" >Kalvin Martin (639)                                        </option>
                                                                            <option value="840" >Kathleen Campbell (840)                                        </option>
                                                                            <option value="886" >Kelvin Octamin (886)                                        </option>
                                                                            <option value="1003" >Kingston (1003)                                        </option>
                                                                            <option value="1020" >Kingston (1020)                                        </option>
                                                                            <option value="538" >Kittu Mac (538)                                        </option>
                                                                            <option value="567" >Kittu Mac (567)                                        </option>
                                                                            <option value="908" >Koushtubh Parmar (908)                                        </option>
                                                                            <option value="1084" >Lal Krishna Advani (1084)                                        </option>
                                                                            <option value="1016" >Lathen (1016)                                        </option>
                                                                            <option value="1004" >Lennon (1004)                                        </option>
                                                                            <option value="1075" >Lincoln Yeo (1075)                                        </option>
                                                                            <option value="1066" >Louis (1066)                                        </option>
                                                                            <option value="1077" >Mackenzie Cotter (1077)                                        </option>
                                                                            <option value="363" >Mahima Shinde (363)                                        </option>
                                                                            <option value="1005" >MAIREENA GOMAZ (1005)                                        </option>
                                                                            <option value="1012" >Maizie (1012)                                        </option>
                                                                            <option value="121" >Maria Taylor (121)                                        </option>
                                                                            <option value="830" >Martin Opega (830)                                        </option>
                                                                            <option value="1047" >Mason (1047)                                        </option>
                                                                            <option value="1067" >Matthew (1067)                                        </option>
                                                                            <option value="1040" >Matthew J. Brown (1040)                                        </option>
                                                                            <option value="1038" >Mayer (1038)                                        </option>
                                                                            <option value="1007" >Milo (1007)                                        </option>
                                                                            <option value="1043" >Myla (1043)                                        </option>
                                                                            <option value="658" >NAMIT AGGRAWAL (658)                                        </option>
                                                                            <option value="997" >Nelson (997)                                        </option>
                                                                            <option value="1050" >Nelson (1050)                                        </option>
                                                                            <option value="1023" >Neville (1023)                                        </option>
                                                                            <option value="980" >Nishant Kadakia (980)                                        </option>
                                                                            <option value="563" >Nivetha Thomas (563)                                        </option>
                                                                            <option value="1064" >Oakley (1064)                                        </option>
                                                                            <option value="765" >Obaid Venkatesh (765)                                        </option>
                                                                            <option value="1" >Olivier Thomas (1)                                        </option>
                                                                            <option value="531" >Olivier Thomas (531)                                        </option>
                                                                            <option value="542" >Olivier Thomas (542)                                        </option>
                                                                            <option value="561" >Olivier Thomas (561)                                        </option>
                                                                            <option value="1046" >Parson (1046)                                        </option>
                                                                            <option value="999" >Patrick (999)                                        </option>
                                                                            <option value="917" >Patrick Cummins (917)                                        </option>
                                                                            <option value="1036" >Pearson (1036)                                        </option>
                                                                            <option value="1060" >Percy (1060)                                        </option>
                                                                            <option value="993" >Perry (993)                                        </option>
                                                                            <option value="628" >Preeti Desmukh (628)                                        </option>
                                                                            <option value="1080" >Qadim Shetty (1080)                                        </option>
                                                                            <option value="1085" >Qazi Asad Ullah (1085)                                        </option>
                                                                            <option value="1028" >Rayn (1028)                                        </option>
                                                                            <option value="1030" >Rayn (1030)                                        </option>
                                                                            <option value="996" >Richard (996)                                        </option>
                                                                            <option value="918" >Riley Lawry (918)                                        </option>
                                                                            <option value="916" >Robert J. Glenn (916)                                        </option>
                                                                            <option value="844" >Robin Dahlberg (844)                                        </option>
                                                                            <option value="1039" >Royal (1039)                                        </option>
                                                                            <option value="1048" >Ruby (1048)                                        </option>
                                                                            <option value="1068" >Rupal Gala (1068)                                        </option>
                                                                            <option value="1015" >Ruta (1015)                                        </option>
                                                                            <option value="939" >Ryan D. Spangler (939)                                        </option>
                                                                            <option value="1078" >Ryan Dampier (1078)                                        </option>
                                                                            <option value="1002" >Scott (1002)                                        </option>
                                                                            <option value="520" >Shakib Khanna (520)                                        </option>
                                                                            <option value="1006" >Sonny (1006)                                        </option>
                                                                            <option value="1035" >Stan (1035)                                        </option>
                                                                            <option value="989" >Stanley (989)                                        </option>
                                                                            <option value="874" >Stephen Jackson (874)                                        </option>
                                                                            <option value="1018" >Sterling (1018)                                        </option>
                                                                            <option value="580" >Stuart Wood (580)                                        </option>
                                                                            <option value="1031" >Vanita (1031)                                        </option>
                                                                            <option value="629" >varun mahajan (629)                                        </option>
                                                                            <option value="1079" >Venkat Nath (1079)                                        </option>
                                                                            <option value="919" >Wesley Barresi (919)                                        </option>
                                                                            <option value="922" >Wesley Barresi (922)                                        </option>
                                                                            <option value="928" >Wiktor Jaworski (928)                                        </option>
                                                                            <option value="151" >Wordey Limpi (151)                                        </option>
                                                                    </select>
                            </div>
                            <span class="text-danger"></span>
                        </div>
                    </div><!--./col-sm-6 col-xs-8 -->
                </div><!--./row-->
            </div>
            <form id="formeditrecord" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                <div class="pup-scroll-area">
                    <div class="modal-body pt0 pb0">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="row row-eq">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <div class="ptt10">
                                            <div id="evajax_load"></div>
                                            <div class="row" id="evpatientDetails" style="display:none">
                                                <div class="col-md-9 col-sm-9 col-xs-9">
                                                    <ul class="singlelist">
                                                        <li class="singlelist24bold">
                                                            <span id="evlistname"></span></li>
                                                        <li>
                                                            <i class="fas fa-user-secret" data-toggle="tooltip" data-placement="top" title="Guardian"></i>
                                                            <span id="evguardian"></span>
                                                        </li>
                                                    </ul>
                                                    <ul class="multilinelist">
                                                        <li>
                                                            <i class="fas fa-venus-mars" data-toggle="tooltip" data-placement="top" title="Gender"></i>
                                                            <span id="evgenders" ></span>
                                                        </li>
                                                        <li>
                                                            <i class="fas fa-tint" data-toggle="tooltip" data-placement="top" title="Blood Group"></i>
                                                            <span id="evblood_group"></span>
                                                        </li>
                                                        <li>
                                                            <i class="fas fa-ring" data-toggle="tooltip" data-placement="top" title="Marital Status"></i>
                                                            <span id="evmarital_status"></span>
                                                        </li>
                                                    </ul>
                                                    <ul class="singlelist">
                                                        <li>
                                                            <i class="fas fa-hourglass-half" data-toggle="tooltip" data-placement="top" title="Age"></i>
                                                            <span id="evage"></span>
                                                        </li>
                                                        <li>
                                                            <i class="fa fa-phone-square" data-toggle="tooltip" data-placement="top" title="Phone"></i>
                                                            <span id="evlistnumber"></span>
                                                        </li>
                                                        <li>
                                                            <i class="fa fa-envelope" data-toggle="tooltip" data-placement="top" title="Email"></i>
                                                            <span id="evemail"></span>
                                                        </li>
                                                        <li>
                                                            <i class="fas fa-street-view" data-toggle="tooltip" data-placement="top" title="Address"></i>
                                                            <span id="evaddress" ></span>
                                                        </li>
                                                        <li>
                                                            <b>Any Known Allergies </b>
                                                            <span id="evallergies" ></span>
                                                        </li>
                                                        <li>
                                                            <b>Remarks </b>
                                                            <span id="evnote"></span>
                                                        </li>
                                                        <li>
                                                            <b>TPA ID </b>
                                                            <span id="etpa_id"></span>
                                                        </li>
                                                        <li>
                                                            <b>TPA Validity </b>
                                                            <span id="etpa_validity"></span>
                                                        </li>
                                                        <li>
                                                            <b>National Identification Number </b>
                                                            <span id="eidentification_number"></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-3 col-sm-3 col-xs-3">
                                                    <div class="pull-right">

                                                        <img class="profile-user-img img-responsive" src="https://demo.smart-hospital.in/uploads/patient_images/no_image.png?1718275779" id="evimage" alt="User profile picture">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="dividerhr"></div>
                                                </div><!--./col-md-12-->
                                                <div class="col-sm-2 col-xs-4">
                                                    <div class="form-group">
                                                        <label for="pwd">Height</label>
                                                        <input name="height" id="evheight" type="text" class="relative zindex-1 form-control" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 col-xs-4">
                                                    <div class="form-group">
                                                        <label for="pwd">Weight</label>
                                                        <input name="weight" id="evweight" type="text" class="relative zindex-1 form-control" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 col-xs-4">
                                                    <div class="form-group">
                                                        <label for="pwd">BP</label>
                                                        <input name="bp" id="evbp" type="text" class="form-control relative zindex-1" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 col-xs-4">
                                                    <div class="form-group">
                                                        <label for="pwd">Pulse</label>
                                                        <input name="pulse" id="evpulse" type="text" class="form-control relative zindex-1" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 col-xs-4">
                                                    <div class="form-group">
                                                        <label for="pwd">Temperature</label>
                                                        <input name="temperature" id="evtemperature" type="text" class="form-control relative zindex-1" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 col-xs-4">
                                                    <div class="form-group">
                                                        <label for="pwd">Respiration</label>
                                                        <input name="respiration" id="evrespiration" type="text" class="form-control relative zindex-1" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-xs-4">
                                                <div class="form-group relative zindex-1">
                                                    <label for="exampleInputFile">
                                                        Symptoms Type</label>
                                                    <div><select name='symptoms_type' id="act" class="form-control select2 act" style="width:100%">
                                                            <option value="">Select</option>
                                                                                                                        <option value="1">Eating or weight problems</option>
                                                                                                                    <option value="2">Emotional problems</option>
                                                                                                                    <option value="3">Muscle or joint problems</option>
                                                                                                                    <option value="4">Skin problems</option>
                                                                                                                    <option value="5">Bladder problems</option>
                                                                                                                    <option value="6">Stomach  problems</option>
                                                                                                                    <option value="7">Lung problems</option>
                                                                                                                </select>
                                                    </div>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                                <div class="col-sm-3">
                                                    <label for="exampleInputFile">
                                                        Symptoms Title</label>
                                                    <div id="dd" class="wrapper-dropdown-3">
                                                        <input class="form-control filterinput relative zindex-1" type="text">
                                                        <ul class="dropdown scroll150 section_ul">
                                                            <li><label class="checkbox">Select</label></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="email">Symptoms Description</label>
                                                        <textarea row="3" name="symptoms" id="symptoms_description" class="form-control relative zindex-1" ></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="pwd">Note</label>
                                                        <textarea name="note" id='evnoteipd' rows="3" class="form-control relative zindex-1"></textarea>
                                                    </div>
                                                </div>
                                                <div class="" id="customfield" >

                                                </div>
                                            </div>
                                            <input name="patient_id" id="evpatients_id" type="hidden" class="form-control" value="" />
                                            <input name="otid" id="otid" type="hidden" class="form-control"  value="" />
                                            <input type="hidden" id="updateid" name="updateid">
                                            <input type="hidden" id="ipdid_edit" name="ipdid">
                                            <input type="hidden" id="previous_bed_id" name="previous_bed_id">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-eq ptt10">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Admission Date<small class="req"> *</small> </label>
                                                    <input id="edit_admission_date" name="appointment_date" placeholder="" type="text" class="form-control datetime" />
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Case</label>
                                                    <div><input class="form-control" type='text' id="patient_case" name='case_type' />
                                                    </div>
                                                    <span class="text-danger"></span></div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Casualty</label>
                                                    <div>
                                                        <select name="casualty" id="patient_casualty" class="form-control" >
                                                            <option value="Yes">Yes</option>
                                                            <option value="No" selected>No</option>
                                                        </select>
                                                    </div>
                                                <span class="text-danger"></span></div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputFile">
                                                    Old Patient</label>
                                                    <div>
                                                        <select name="old_patient" id="old" class="form-control">
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                    </div>
                                                    <span class="text-danger"></span></div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Credit Limit ($)</label>
                                                    <input type="text" id="credits_limits" value="" name="credit_limit" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputFile">
                                                    TPA</label>
                                                    <div><select class="form-control" name='organisation' id="edit_organisations">
                                                            <option value="">Select</option>
                                                                                                                            <option value="4">Health Life Insurance</option>
                                                                                                                        <option value="3">Star Health Insurance</option>
                                                                                                                        <option value="2">IDBI Federal</option>
                                                                                                                        <option value="1">CGHS</option>
                                                                                                                </select>
                                                    </div>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputFile">
                                                    Reference</label>
                                                    <div><input class="form-control" type='text' name='refference' id="patient_refference" />
                                                    </div>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputFile">
                                                        Consultant Doctor <small class="req"> *</small> </label>
                                                    <div>
                                                        <select class="form-control select2" style="width: 100%;"  name='cons_doctor' id="patient_consultant" >
                                                            <option value="">Select</option>
                                                                                                                            <option value="11" >Amit  Singh (9009)</option>
                                                                                                                        <option value="12" >Reyan Jain (9011)</option>
                                                                                                                        <option value="4" >Sansa Gomez (9008)</option>
                                                                                                                        <option value="2" >Sonia Bush (9002)</option>
                                                                                                                </select>
                                                                                                            </div>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="exampleInputFile">
                                                            Bed Group</label>
                                                    <div>
                                                        <select class="form-control" name='bed_group_id' id='ebed_group_id' onchange="getBed(this.value, '', 'yes','ebed_nos')">
                                                            <option value="">Select</option>
                                                                                                                            <option value="1">VIP Ward - Ground  Floor</option>
                                                                                                                            <option value="2">Private Ward - 3rd Floor</option>
                                                                                                                            <option value="3">General Ward Male - 3rd Floor</option>
                                                                                                                            <option value="4">ICU - 2nd Floor</option>
                                                                                                                            <option value="5">NICU - 2nd Floor</option>
                                                                                                                            <option value="6">AC (Normal) - 1st Floor</option>
                                                                                                                            <option value="7">Non AC - 4th Floor</option>
                                                                                                                    </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="exampleInputFile">
                                                        Bed No.</label><small class="req"> *</small>
                                                    <div><select class="form-control select2" style="width:100%" name='bed_no' id='ebed_nos'>
                                                            <option value="">Select</option>

                                                        </select>
                                                    </div>
                                                    <span class="text-danger"></span></div>
                                            </div>
                                        </div><!--./row-->
                                    </div><!--./col-md-4-->
                                </div><!--./row-->
                            </div><!--./col-md-12-->
                        </div><!--./row-->
                    </div>
                </div>
                <div class="modal-footer sticky-footer">
                    <div class="pull-right">
                        <button type="submit" id="formeditrecordbtn" data-loading-text="Processing..." class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- discharged summary   -->
<div class="modal fade" id="myModaldischarged"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <div class="modalicon">
                     <div id='summary_print'>
                    </div>
                </div>
                <h4 class="modal-title">Discharged Summary</h4>
                <div class="row">
                </div><!--./row-->
            </div>
            <form id="formdishrecord" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row row-eq">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="ptt10">
                                        <div id="evajax_load"></div>
                                        <div class="row" id="" >
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <ul class="multilinelist">
                                                      <li>  <label for="pwd">Name</label>
                                                        <span id="disevlistname"></span>
                                                    </li>
                                                     <li>
                                                        <label for="pwd">Age</label>
                                                        <span id="disevage"></span>
                                                    </li>
                                                     <li>
                                                        <label for="pwd">Gender</label>
                                                        <span id="disevgenders" ></span>
                                                    </li>
                                                </ul>
                                                <ul class="multilinelist">
                                                    <li>
                                                         <label>Admission Date</label>
                                                        <span id="disedit_admission_date"></span>
                                                    </li>
                                                    <li>
                                                         <label>Discharged Date</label>
                                                        <span id="disedit_discharge_date"></span>
                                                    </li>
                                                </ul>
                                            <ul class="singlelist">
                                                    <li>
                                                        <label>Address</label>
                                                        <span id="disevaddress"></span>
                                                    </li>
                                            </ul>
                                            </div>
                                        </div>
                                        <div class="row">
                                             <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="pwd">Diagnosis</label>
                                                    <input name="diagnosis" id='disdiagnosis' rows="3" class="form-control" >
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="pwd">Operation</label>
                                                    <input name="operation" id='disoperation'  class="form-control" >
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="pwd">Note</label>
                                                    <textarea name="note" id='disevnoteipd' rows="3" class="form-control" ></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="dividerhr"></div>
                                            </div><!--./col-md-12-->
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="pwd">Investigation</label>
                                                    <textarea name="investigations" id='disinvestigations' rows="3" class="form-control" ></textarea>
                                                </div>
                                            </div>

                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label for="pwd">Treatment At Home</label>
                                                    <textarea name="treatment_at_home" id='distreatment_at_home' rows="3" class="form-control" ></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <input name="patient_id" id="disevpatients_id" type="hidden">
                                        <input type="hidden" id="disupdateid" name="updateid">
                                        <input type="hidden" id="disipdid" name="ipdid">
                                        </div>
                                </div>
                            </div><!--./row-->
                        </div><!--./col-md-12-->
                    </div><!--./row-->
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" id="formdishrecordbtn" data-loading-text="Processing..." class="btn btn-info pull-right"> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- discharged summary   -->

<!-- Add Instruction -->
<div class="modal fade" id="add_instruction" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close close_button" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Consultant Register </h4>
            </div>

            <form id="consultant_register_form" accept-charset="utf-8" enctype="multipart/form-data" method="post" >
                <input name="patient_id" placeholder="" id="ins_patient_id" value="980" type="hidden" class="form-control" />
                <div class="scroll-area">
                    <div class="modal-body pb0 ptt10">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Applied Date<small class="req"> *</small>
                                        </label>
                                        <input type="text" name="date" value="" class="form-control datetime">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Instruction Date  <small class="req"> *</small> </label>
                                        <input type="text" id="instruction_date"  name="insdate" value="06/13/2024" class="form-control date">
                                        <input type="hidden" name="ipdid" value="97">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Consultant Doctor<small class="req"> *</small> </label>
                                    <input type="hidden" name="doctor" id="doctor_set">
                                        <select name="doctor_field"  style="width: 100%" id="doctor_field" class="form-control select2">
                                            <option value="">Select</option>
                                                                                        <option   value="11">Amit  Singh (9009)</option>
                                                                                        <option   value="12">Reyan Jain (9011)</option>
                                                                                        <option   value="4">Sansa Gomez (9008)</option>
                                                                                        <option   value="2">Sonia Bush (9002)</option>
                                                                                    </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Instruction <small class="req"> *</small> </label>
                                        <textarea name="instruction" rows="5"class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="">
                                                                        </div>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" id="consultant_registerbtn" data-loading-text="Processing..." class="btn btn-info"><i class="fa fa-check-circle"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_instruction" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Consultant Register </h4>
            </div>

            <form id="editconsultant_register_form" accept-charset="utf-8"  enctype="multipart/form-data" method="post" class="ptt10">
                <div class="scroll-area">
                    <div class="modal-body pt0 pb0">
                            <div class="row">
                                <input type="hidden" name="instruction_id" value="" id="instruction_id">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Applied Date                                                    <small class="req"> *</small>
                                        </label>
                                        <input type="text" name="date" id="ecdate" value="" class="form-control datetime">

                                    </div> </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Instruction Date  <small class="req"> *</small> </label>
                                        <input type="text"  id="ecinsdate" name="insdate" value="06/13/2024" class="form-control date">
                                        <input type="hidden" name="ipdid" value="97">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Consultant Doctor<small class="req"> *</small> </label>
                                    <input type="hidden" name="doctor" id="editdoctor_set">
                                        <select name="doctor_field"  style="width: 100%" id="editdoctor_field" class="form-control select2">
                                            <option value="">Select</option>
                                                                                        <option   value="11">Amit  Singh (9009)</option>
                                                                                        <option   value="12">Reyan Jain (9011)</option>
                                                                                        <option   value="4">Sansa Gomez (9008)</option>
                                                                                        <option   value="2">Sonia Bush (9002)</option>
                                                                                    </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Instruction <small class="req"> *</small> </label>
                                        <textarea name="instruction" id="ecinstruction" rows="5" class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="" id="customfieldconsult">
                                </div>
                            </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="editconsultant_registerbtn" data-loading-text="Processing..." class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="add_nurse_note" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close close_button" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Nurse Note </h4>
            </div>

            <form id="nurse_note_form" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                <input name="patient_id" placeholder="" id="nurse_patient_id" value="980" type="hidden" class="form-control" />
                <input type="hidden" name="ipdid" value="97">
                <div class="scroll-area">
                    <div class="modal-body pb0 ptt10">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Date                                        <small class="req"> *</small>
                                        </label>
                                        <input type="text" name="date" value="" class="form-control datetime">

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Nurse<small class="req"> *</small> </label>
                                    <input type="hidden" name="nurse" id="nurse_set">
                                        <select name="nurse_field"  style="width: 100%" id="nurse_field" class="form-control select2">
                                            <option value="">Select</option>
                                                                                        <option   value="16">April Clinton (9020)</option>
                                                                                        <option   value="10">Natasha  Romanoff (9010)</option>
                                                                                    </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Note <small class="req"> *</small> </label>
                                        <textarea name="note" style="height:50px" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Comment <small class="req"> *</small> </label>
                                        <textarea name="comment" style="height:50px" class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="">
                                                                        </div>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="nurse_notebtn" data-loading-text="Processing..." class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                </div>
            </form>


        </div>
    </div>

</div>


<!-- change bed -->
<div class="modal fade" id="alot_bed" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Bed</h4>
            </div>
         <form id="alot_bed_form" accept-charset="utf-8" enctype="multipart/form-data" method="post" class="ptt10">
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">

                            <div class="alert alert-info">

                            </div>
                            <div class="row">
                                <input name="patient_id" placeholder=""  value="980" type="hidden" class="form-control"   />

                                <div class="col-md-12">
                                    <label>Bed Group<small class="req"> *</small></label>
                                    <select class="form-control" onchange="getBed(this.value, '', 'yes', 'alotbedoption')" name="bedgroup">
                                        <option value="">Select</option>
                                            <option value="1">VIP Ward - Ground  Floor</option>
                                            <option value="2">Private Ward - 3rd Floor</option>
                                            <option value="3">General Ward Male - 3rd Floor</option>
                                            <option value="4">ICU - 2nd Floor</option>
                                            <option value="5">NICU - 2nd Floor</option>
                                            <option value="6">AC (Normal) - 1st Floor</option>
                                            <option value="7">Non AC - 4th Floor</option>
                                    </select>
                                </div>
                                <div class="col-md-12" style="margin-top: 10px;">
                                    <label>Bed No<small class="req"> *</small></label>
                                    <select class="form-control select2" style="width: 100%" id="alotbedoption" name="bedno">
                                    </select>
                                </div>
                                <div class="col-md-12" style="margin-top: 10px;">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="pull-right">
                                    <button type="submit" id="alotbedbtn" data-loading-text="Processing..."  class="btn btn-info">Save</button>
                                </div>
                            </div>

                       </div>
                    </div>
                 </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="view_ot_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modalicon">
                    <div id='action_detail_modal'>

                   </div>


                </div>

                <h4 class="modal-title">Operation Details</h4>
            </div>
            <div class="modal-body min-h-3">
               <div id="show_ot_data"></div>
            </div>
        </div>
    </div>
</div>

<!--lab investigation modal-->
<div class="modal fade" id="viewDetailReportModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-toggle="tooltip" title="" data-dismiss="modal">&times;</button>
                <div class="modalicon">
                    <div id='action_detail_report_modal'>

                   </div>
                </div>
                <h4 class="modal-title" id="modal_head"></h4>
            </div>
            <div class="modal-body ptt10 pb0">
                <div id="reportbilldata"></div>
            </div>
        </div>
    </div>
</div>
<!-- end lab investigation modal-->

<div class="modal fade" id="editpayment_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <form id="editpaymentform" accept-charset="utf-8" method="post">
             <div class="modal-header modal-media-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="modalicon">
                    </div>

                    <h4 class="modal-title">Payment Details</h4>
                </div>
                <div class="modal-body ">
                   <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                    <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date</label><small class="req"> *</small>


                                                <input type="text" name="payment_date" id="payment_date" class="form-control datetime" autocomplete="off">
                                                 <input type="hidden" class="form-control" id="edit_payment_id" name="edit_payment_id" >
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Amount ($)</label><small class="req"> *</small>

                                                <input type="text" name="amount" id="edit_payment" class="form-control" value="">

                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Payment Mode</label>
                                                <select class="form-control payment_mode" name="payment_mode" id="payment_mode">

                                                                                                    <option value="Cash" >Cash</option>
                                                                                                    <option value="Cheque" >Cheque</option>
                                                                                                    <option value="transfer_to_bank_account" >Transfer to Bank Account</option>
                                                                                                    <option value="UPI" >UPI</option>
                                                                                                    <option value="Other" >Other</option>
                                                                                                    <option value="Online" >Online</option>
                                                                                                </select>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                <div class="row cheque_div" style="display: none;">

                                            <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Cheque No</label><small class="req"> *</small>
                                                <input type="text" name="cheque_no" id="edit_cheque_no" class="form-control">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Cheque Date</label><small class="req"> *</small>
                                                <input type="text" name="cheque_date" id="edit_cheque_date" class="form-control date">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Attach Document</label>
                                                <input type="file" class="filestyle form-control"   name="document">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Note</label>
                                                <input type="text" name="note" id="edit_payment_note" class="form-control"/>
                                            </div>
                                        </div>

                                    </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="editpaymentbtn" data-loading-text="Processing..." class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
