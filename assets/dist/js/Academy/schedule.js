/**
 *  js script for course
 */
var create_url = "../create";
var update_url = "../update"


var post_url;
var activation_url;
var who_click;
// Modal for new student registration
$(document).ready(function () {

    $('#modal_schedule').on('shown.bs.modal', function () {
        if (who_click == 'NewCourse')
            $('#lessonVenue').focus();
        else if (who_click == 'UpdateCourse')
            $('#courseInstructor').focus();
    });

    // To make sure it is clean for the next show
    $('#modal_schedule').on('hidden.bs.modal', function () {
        $('#help-block').remove();
        document.getElementById("form_schedule").reset();
    });

    $('#search_venue').on('change', function () {

        $('#search_form').submit();
    });

    $('#btn_schedule_new').on('click', function () {
        post_url = create_url;
        who_click = 'NewCourse';
        $('#modalTitle').text(' New Schedule');
        $("#lessonVenue").prop('disabled', false);
        $("#courseLevel").prop('disabled', false);
        $('#courseDay').prop('disabled', false);
        $("#courseTimeHour").prop('disabled', false);
        $("#courseTimeMinute").prop('disabled', false);

        $('#modal_schedule').modal('show');
        $('#btnSubmitCourseInfo').text('Create');
    });

    // De-Activate
    $('#tbl_content_main').on('click', '.deactivate_schedule', function () {

        activation_url = '../delete';
        var activation_id = $(this).closest('tr').children('td#current_schedule_id').text();

        $('#activation_id').val(activation_id);

        var r = confirm("Deactivate this ID ?");
        if (r == true) {
            $('#form_activation').submit();
        }
    });

    /*
     // Course Update - retrieve lastest info from db
     $('#tblActiveCourse').on('click', '.btnCourseUpdate', function () {

     var course_id = $(this).closest('tr').children('td#course_id').text();
     var url = "../getCourseInfo/" + course_id;

     $.getJSON(url, function (data) {
     console.log(data);
     }).done(function (data) {
     // lessonVenueReload();
     var slot_time = data.slot_time;

     $("#courseID").val(data.id);
     $("#lessonVenue").val(data.venue_id).prop("selected", true);
     $("#courseLevel").val(data.level_id).prop("selected", true);
     $("#courseInstructor").val(data.instructor_id).prop("selected", true);

     $("#lessonVenue").prop('disabled', true);
     $("#courseLevel").prop('disabled', true);

     }).fail(function (data) {
     //alert("fail");
     console.log(data);
     });
     });
     */
    // Submission
    $("#form_schedule").validate({
        rules: {
            capacity: {
                number: true
            }
        },
        submitHandler: function (form, event) {

            $('#help-block').remove();
            $('#btn_submit_modal').prop('disabled', true);
            $('#btn_submit_modal').text('In Progress');
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
                        $('#btn_submit_modal').text('Retry');
                        $('#btn_submit_modal').prop('disabled', false);
                        $('#help-block').remove();
                        $('#statusMsg').append('<div class="alert alert-danger" id="help-block">' + data.message + '</div>');
                    } else {// Success !
                        $('#btn_submit_modal').text('Done!');
                        document.getElementById("form_schedule").reset();
                        $('#help-block').remove();
                        $('#statusMsg').append('<div class="alert alert-success" id="help-block">' + data.message + '</div>');
                        setTimeout(function () {
                            $('#modal_schedule').modal('hide');
                        }, 100);
                        setTimeout(function () {
                            location.reload();
                        }, 100);

                    }
                }).fail(function (data) {
                $('#btn_submit_modal').prop('disabled', false);
                $('#btn_submit_modal').text('Retry');
                console.log(data);
            });
            event.preventDefault();

        }
    });

    // detect input change, get the name from DB
    $("#instructor_id").on("change", function () {
        get_course();

    });

    // detect input change, get the name from DB
    $("#class_id").on("change", function () {
        var ele = $(this).find('option:selected');
        var duration = ele.attr('data-duration');
        //alert(duration);
        $('#class_duration').val(duration);
    });

    $("#form_activation").validate({
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

function get_course() {
    var UrlGetName = "../ajax_get_class/" + $("#instructor_id").val() + "/" + $("#venue_id").val();
console.log( UrlGetName )
    $("#class_id").prop('disabled', true);

    $.getJSON(UrlGetName, function (data) {
        console.log(data);
    }).done(function (data) {
        $("#class_id").find('option').remove();
        $("#class_id").append('<option value="" disabled="disabled" selected="selected"></option>');
        $.each(data, function (index, item) {
            $("#class_id").append('<option value="' + item.id + '" data-duration="' + item.duration_minute + '">' + item.level_name + '</option>');
        });
        $("#class_id").prop('disabled', false);
    }).fail(function (data) {
        $("#class_id").prop('disabled', false);
    });
}
