<form id="staffInfoFrm" name="staffInfoFrm" autocomplete="off">
    <input type="hidden" id="staffID" name="staffID" value="" />
    <div class="modal fade" role="dialog" aria-labelledby="myModalLabel" id="staffInfoModal">
        <div class="modal-dialog" style="margin-top: 2%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <i class="fa fa-user-plus fa-lg"></i><span id="staffInfoModalTitle">If you see me smth is wrong yo</span>
                    </h4>
                </div>
                <div id="statusMsg" style="padding-bottom: 0px; margin-bottom: 0px"></div>

                <div class="col-lg-6 col-xs-6" style="padding-right: 0px">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" placeholder="staff Full Name" id="staffName" name="staffName" required="required">
                        </div>
                        <div class="form-group">
                            <label>Short Name</label>
                            <input class="form-control" placeholder="Staff Short Name, Max 8 Character" id="staffShortName" name="staffShortName" required="required" maxlength="8">
                        </div>
                        <div class="form-group">
                            <label>IC/ Passport</label>
                            <input class="form-control" placeholder="IC/ Passport Number" id="staffIdentity" name="staffIdentity" required="required">
                        </div>
                        <div class="col-lg-7 col-xs-7" style="padding-left: 0px; padding-right: 2px">
                            <div class="form-group">
                                <label>Contact No.</label>
                                <input class="form-control" placeholder="Phone Number" id="staffContact" name="staffContact" required="required"
                                       data-inputmask='"mask": "999-9999999[9]"' data-mask>
                            </div>
                        </div>
                        <div class="col-lg-5 col-xs-5" style="padding-left: 0px; padding-right: 2px">
                            <div class="form-group">
                                <label>Gender</label>
                                <select class="form-control" id="staffGender" name="staffGender" required="required">
                                    <option value="m">Male</option>
                                    <option value="f">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input class="form-control" placeholder="Email" id="staffEmail" name="staffEmail"
                                   data-inputmask="'alias': 'email'" data-mask>
                        </div>
                        <div class="form-group">
                            <label>System Login Name</label>
                            <input class="form-control" placeholder="Login Name" id="loginName" name="loginName">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xs-6" style="padding-left: 2px">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Address</label>
                            <input class="form-control" placeholder="Address line 1" id="staffAddr1" name="staffAddr1">
                            <input class="form-control" placeholder="Address line 2" id="staffAddr2" name="staffAddr2">
                            <div class="col-lg-6 col-xs-6" style="padding-left: 0px; padding-right: 2px">
                                <input class="form-control" placeholder="PostCode" id="Postcode" name="Postcode">
                            </div>
                            <div class="col-lg-6 col-xs-6" style="padding-left: 0px; padding-right: 0px">
                                <input class="form-control" placeholder="City" id="City" name="City">
                            </div>
                            <input class="form-control" placeholder="State" id="State" name="State" value="Johor">
                            <input class="form-control" placeholder="Country" id="Country" name="Country" value="Malaysia">
                        </div>
                        <div class="form-group" style="padding-bottom: 0px">
                            <label>Staff Type</label>
                            <select class="form-control" id="staffType" name="staffType">
                                <?php foreach($get_staff_type as $row){ ?>
                                    <option value="<?php echo $row['type_id'];?>" <?php if($row['default']=='Y'){echo "SELECTED";}?>><?php echo $row['type_name']?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>System Login Password</label>
                            <input type="password" class="form-control" placeholder="Login Password" id="loginPassword" name="loginPassword">
                            <input type="password" class="form-control" placeholder="Confirm your password" id="loginPassword2" name="loginPassword2">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitstaffInfo">Update!</button>
                </div>
            </div>
        </div>
    </div>
</form>