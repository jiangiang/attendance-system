<form id="FormEliteStudent" name="FormEliteStudent" autocomplete="off">
    <div class="modal fade" role="dialog" aria-labelledby="myModalLabel" id="ModalEliteStudent">
        <div class="modal-dialog" style="margin-top: 2%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <i class="fa fa-user-plus fa-lg"></i><span id="ModalTitle"></span>
                    </h4>
                </div>
                <div id="statusMsg" style="padding-bottom: 0px; margin-bottom: 0px"></div>

                <!-- ID for update -->
                <input type="hidden" id="SwimmerID" name="SwimmerID" value="" />

                <div class="col-lg-6" style="padding-right: 0px">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" placeholder="Student Full Name" id="StudentName" name="StudentName" required="required">
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6" style="padding-left: 0px; padding-right: 2px">
                            <div class="form-group">
                                <label>IC/ Passport</label>
                                <input class="form-control" placeholder="IC/Passport Number" id="StudentIdentity" name="StudentIdentity" required="required">
                            </div>
                        </div>
                        <div class = "col-md-6 col-sm-6 col-xs-6" style = "padding-left: 0px; padding-right: 2px">
                            <div class = "form-group">
                                <label>DOB</label>
                                <input type = "text" id = "StudentDOB" name = "StudentDOB" class = "form-control" value = "" required
                                       data-inputmask = '"mask": "9999-99-99"' data-mask></div>
                        </div>
                        <div class="col-lg-7" style="padding-left: 0px; padding-right: 2px">
                            <div class="form-group">
                                <label>Contact No.</label>
                                <input class="form-control" placeholder="Phone Number" id="StudentPhone" name="StudentPhone" required="required" data-inputmask='"mask": "999-9999999[9]"' data-mask>
                            </div>
                        </div>
                        <div class="col-lg-5" style="padding-left: 0px; padding-right: 2px">
                            <div class="form-group">
                                <label>Gender</label>
                                <select class="form-control" id="StudentGender" name="StudentGender" required="required">
                                <?php foreach( $gender_list as $gender){ ?>
                                    <option value="<?php echo $gender['gender_id'] ?>"><?php echo $gender['gender'] ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input class="form-control" placeholder="Email" id="StudentEmail" name="StudentEmail" data-inputmask="'alias': 'email'" data-mask>
                        </div>
                        <div class="form-group">
                            <label>Guardian Name</label>
                            <input class="form-control" placeholder="Student Guardian" id="GuardianName" name="GuardianName">
                        </div>
                        <div class="col-lg-7" style="padding-left: 0px; padding-right: 0px">
                            <div class="form-group">
                                <label>Guardian Contact</label>
                                <input class="form-control" placeholder="Contact" id="GuardianPhone" name="GuardianPhone" data-inputmask='"mask": "999-9999999[9]"' data-mask>
                            </div>
                        </div>
                        <div class="col-lg-5" style="padding-left: 0px; padding-right: 0px">
                            <div class="form-group">
                                <label>Relation</label>
                                <select class="form-control" id="GuardianRelation" name="GuardianRelation">
                                    <option value=" ">NA</option>
                                <?php foreach( $relationship_list as $relation){ ?>
                                    <option value="<?php echo $relation['id'] ?>"><?php echo $relation['relation'] ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" style="padding-left: 2px">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nationality</label>
                            <select class="form-control" id="StudentNationality" name="StudentNationality" required="required">
                                <?php foreach( $gender_list as $gender){ ?>
                                    <option value="<?php echo $gender['gender_id'] ?>"><?php echo $gender['gender'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>School</label>
                            <input class="form-control" placeholder="Student Full Name" id="StudentSchool" name="StudentSchool" required="required">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input class="form-control" placeholder="Address line 1" id="AddressLine1" name="AddressLine1">
                            <input class="form-control" placeholder="Address line 2" id="AddressLine2" name="AddressLine2">
                            <div class="col-lg-6" style="padding-left: 0px; padding-right: 2px">
                                <input class="form-control" placeholder="PostCode" id="PostCode" name="PostCode">
                            </div>
                            <div class="col-lg-6" style="padding-left: 0px; padding-right: 0px">
                                <input class="form-control" placeholder="City" id="City" name="City">
                            </div>
                            <input class="form-control" placeholder="State" id="State" name="State" value="Johor">
                            <input class="form-control" placeholder="Country" id="Country" name="Country" value="Malaysia">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-Submit" ></button>
                </div>
            </div>
        </div>
    </div>
</form>