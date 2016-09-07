<!-- MODAL FOR CLASS IsReplacement -->
<form id="classReplaceFrm" name="classReplaceFrm">
    <input type="hidden" id="action" name="action" value=""/>
    <div class="modal fade" role="dialog" aria-labelledby="myModalLabel" id="classReplaceModal">
        <div class="modal-dialog" style="margin-top: 2%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <i class="fa fa-exchange fa-lg"></i> <span
                            id="modal_title">Something is wrong if you see me</span>
                    </h4>
                </div>
                <div id="statusMsg" style="padding-bottom: 0px; margin-bottom: 0px"></div>
                <input type="hidden" id="id" name="id" value=""/>
                <input type="hidden" name="sessionDate" value="<?php echo $currDate; ?>"/>
                <input type="hidden" name="sessionTime" value="<?php echo $next_slot_time; ?>"/>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Search Name/ID</label>
                        <input class="form-control"
                               placeholder="Student Name/ ID/ Passport" id="stdSearch"
                               name="stdSearch" autocomplete="off">
                        <div id="stdSearchResult"></div>
                    </div>
                    <div class="col-md-7 col-sm-7 col-xs-7" style="padding-left: 0px; padding-right: 2px">
                        <div class="form-group">
                            <label>Student Name</label>
                            <input class="form-control" placeholder="Student Name"
                                   id="stdName" name="stdName"
                                   readonly="readonly" required="required"
                                   autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-5" style="padding-left: 0px; padding-right: 2px">
                        <div class="form-group">
                            <label>Student ID</label>
                            <input class="form-control" placeholder="Student ID" id="stdID"
                                   name="stdID"
                                   readonly="readonly" required="required" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-5" style="padding-left: 0px; padding-right: 2px">
                        <div class="form-group">
                            <label>Class Left</label>
                            <input class="form-control" placeholder="Class Left."
                                   id="stdClassleft"
                                   name="stdClassleft" readonly="readonly" required="required"
                                   autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-7 col-sm-7 col-xs-7" style="padding-left: 0px; padding-right: 2px">
                        <div class="form-group">
                            <label>Expiry Date</label>
                            <input class="form-control" placeholder="Expiry Date."
                                   id="stdExpiry" name="stdExpiry"
                                   readonly="readonly" required="required"
                                   autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-5" style="padding-left: 0px; padding-right: 2px">
                        <div class="form-group">
                            <label>Level</label>
                            <input class="form-control" placeholder="Level." id="stdLevel"
                                   name="stdLevel"
                                   readonly="readonly" required="required" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-7 col-sm-7 col-xs-7" style="padding-left: 0px; padding-right: 2px">
                        <div class="form-group">
                            <label>Instructor</label>
                            <input class="form-control" placeholder="Instructor"
                                   id="stdInstructor"
                                   name="stdInstructor" readonly="readonly" required
                                   autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12" style="padding-left: 0px; padding-right: 2px">
                        <div class="form-group">
                            <label>Last log</label>
                            <textarea class="form-control" placeholder="No log" id="stdLog"
                                      readonly="readonly" autocomplete="off"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" disabled="disabled" id="btnSubmitReplace">Submit
                        IsReplacement
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<!--form-IsReplacement -->