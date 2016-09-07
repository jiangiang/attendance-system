/**
 *  js script for course
 */
var post_url;
var activation_url;
var who_click;
// Modal for new student registration
$(document).ready(function () {

    $('#ModalClassAssignmentModal').on('shown.bs.modal', function () {
        if (who_click == 'NewAssignment')
            $('#ClassVenue').focus();
        else if (who_click == 'UpdateAssignment')
            $('#ClassInstructor').focus();
    });

    // To make sure it is clean for the next show
    $('#ModalClassAssignmentModal').on('hidden.bs.modal', function () {
        $('#help-block').remove();
        document.getElementById("FormClassAssignment").reset();
    });

    $('#btn-AssignmentCreate').on('click', function () {
        post_url = 'create';
        who_click = 'NewAssignment';
        $("#ClassVenue").prop('disabled', false);
        $("#ClassLevel").prop('disabled', false);
        $('#ClassDay').prop('disabled', false);
        $('#ModalTitleClassAssignment').text(' New Assignment');
        $('#ModalClassAssignment').modal('show');
        $('#btn-SubmitAssignment').text('Create');
    });

    $('.btnCourseUpdate').on('click', function () {
        post_url = 'update';
        who_click = 'UpdateAssignment';
        $('#ClassName').prop("readonly", true);
        $('#ModalClassAssignmentModalTitle').text(' Update Course Info');
        $('#btn-SubmitAssignment').text(' Update!');
        $('#btn-SubmitAssignment').prop("disabled", false);
        $('#ModalClassAssignmentModal').modal('show');
    });

    // Course De-Activate
    $('#tbl-Active').on('click', '.AssignmentDeactivate', function () {

        activation_url = 'delete';
        var id = $(this).closest('tr').children('td#AssignmentActivationForm').text();

        $('#TargetID').val( id );

        var r = confirm("Deactivate this assignment ?");
        if (r == true) {
            $('#AssignmentActivationForm').submit();
        }
    });

    // Submission
    $("#FormClassAssignment").validate({
        rules: {
            capacity: {
                number: true
            }
        },
        submitHandler: function (form, event) {

            $('#help-block').remove();
            $('#btn-SubmitAssignment').prop('disabled', true);
            $('#btn-SubmitAssignment').text('In Progress');
            var formData = $(form).serialize();

            $.ajax({
                    type: 'POST',
                    url: post_url,
                    data: formData,
                    dataType: 'json',
                    encode: true
                })
                // using the done promise callback
                .done(function (data) {
                    console.log(data);
                    if (data.error) {
                        $('#btn-SubmitAssignment').prop('disabled', false);
                        $('#btn-SubmitAssignment').text('Retry');
                        $('#help-block').remove();
                        $('#statusMsg').append('<div class="alert alert-danger" id="help-block">' + data.message + '</div>');
                    } else {// Success !
                        document.getElementById("FormClassAssignment").reset();
                        $('#help-block').remove();
                        $('#statusMsg').append('<div class="alert alert-success" id="help-block">' + data.message + '</div>');
                        setTimeout(function () {
                            $('#ModalClassAssignment').modal('hide');
                        }, 100);
                        setTimeout(function () {
                            location.reload();
                        }, 100);

                    }
                }).fail(function (data) {
                $('#btn-SubmitAssignment').prop('disabled', false);
                $('#btn-SubmitAssignment').text('Retry');
                console.log(data);
            });
            event.preventDefault();

        }
    });

    $("#AssignmentActivationForm").validate({
        submitHandler: function (form, event) {
            var formData = $(form).serialize();

            $.ajax({
                type: 'POST',
                url: activation_url,
                data: formData,
                dataType: 'json',
                encode: true
            }).done(function (data) {

                console.log(data);
                if (!data.error) {
                    setTimeout(function () {
                        location.reload();
                    }, 100);
                } else {
                    alert("Fail to deactivate.");
                }
            }).fail(function (data) {
                console.log(data);
            });
            event.preventDefault();

        }
    });

});


