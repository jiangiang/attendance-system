
<style>

</style>
<form id = "FormClassAssignment" name = "FormClassAssignment" autocomplete = "off">
    <input type = "hidden" id = "classesID" name = "classesID" value = ""/>
    <div class = "modal fade" role = "dialog" aria-labelledby = "myModalLabel" id = "ModalClassAssignment">
        <div class = "modal-dialog modal-sm" style = "margin-top: 2%;">
            <div class = "modal-content">
                <div class = "modal-header">
                    <h4 class = "modal-title">
                        <span id = "ModalTitleClassAssignment">ERROR</span>
                    </h4>
                </div>
                <div id = "statusMsg" class = "status-message"></div>
                <div class = "modal-body">
                    <div class = "form-group">
                        <label for = "ClassVenue">ClassVenue</label>
                        <select class = "form-control" id = "ClassVenue" name = "ClassVenue">
                            <?php foreach ($venue_list as $row) { ?>
                                <option value = "<?php echo $row['venue_id']; ?>" <?php if ($row['default_place'] == 'Y') { ?> selected = "selected"<?php } ?>>
                                    <?php echo $row['venue_name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class = "form-group">
                        <label>Instructor</label>
                        <select class = "form-control" id = "ClassInstructor" name = "ClassInstructor" required = "required">
                            <option value = "" selected disabled>Please select an option...</option>
                            <?php foreach ($instructor_list as $row) { ?>
                                <option value = "<?php echo $row['id']; ?>"><?php echo $row['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class = "form-group">
                        <label>Type</label>
                        <select class = "form-control" id = "ClassLevel" name = "ClassLevel" required = "required">
                            <option value = "" disabled selected>Select a classes</option>
                            <?php foreach ($classes_level_list as $row) { ?>
                                <option value = "<?php echo $row['level_id']; ?>"><?php echo $row['level_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>


                <div class = "modal-footer">
                    <button type = "button" class = "btn btn-default" data-dismiss = "modal">Close</button>
                    <button type = "submit" class = "btn btn-primary" id = "btn-SubmitAssignment">Update!</button>
                </div>
            </div>
        </div>
    </div>
</form>