/**
 *  js script for course
 */
var post_url;
var activation_url;
var who_click;
// Modal for new student registration
$(document).ready(function () {

    get_course_schedule();

    $('#courseInfoModal').on('shown.bs.modal', function () {
        if (who_click == 'NewCourse')
            $('#lessonVenue').focus();
        else if (who_click == 'UpdateCourse')
            $('#courseInstructor').focus();
    });

    // To make sure it is clean for the next show
    $('#courseInfoModal').on('hidden.bs.modal', function () {
        $('#help-block').remove();
        document.getElementById("courseInfoFrm").reset();
    });

    $('#btncourseNew').on('click', function () {
        post_url = '../courseNew';
        who_click = 'NewCourse';
        $("#lessonVenue").prop('disabled', false);
        $("#courseLevel").prop('disabled', false);
        $('#courseDay').prop('disabled', false);
        $("#courseTimeHour").prop('disabled', false);
        $("#courseTimeMinute").prop('disabled', false);
        $('#courseInfoModalTitle').text(' New Course');
        $('#courseInfoModal').modal('show');
        $('#btnSubmitCourseInfo').text('Create');
    });

    $('.btnCourseUpdate').on('click', function () {
        post_url = '../courseUpdate';
        who_click = 'UpdateCourse';
        $('#courseName').prop("readonly", true);
        $('#courseInfoModalTitle').text(' Update Course Info');
        $('#btnSubmitcourseInfo').text(' Update!');
        $('#btnSubmitCourseInfo').prop("disabled", false);
        $('#courseInfoModal').modal('show');
    });

    // Course De-Activate
    $('#tblActiveCourse').on('click', '.courseDeactivate', function () {

        activation_url = '../courseDeactivate';
        var course_id = $(this).closest('tr').children('td#course_id').text();

        $('#activationID').val(course_id);

        var r = confirm("Deactivate this course ?");
        if (r == true) {
            $('#courseActivationFrm').submit();
        }
    });

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
            $("#courseCapacity").val(data.max_capacity).prop("selected", true);
            $('#courseDay').val(data.slot_day).prop("selected", true);

            $("#course_schedule").find('option').remove();
            $("#course_schedule").prop('disabled', true);
            $("#course_schedule").append('<option value="">' + data.slot_time + ' (' + data.duration_minute + ' mins)' + '</option>');
            $("#courseInstructor").val(data.instructor_id).prop("selected", true);

            $("#lessonVenue").prop('disabled', true);
            $("#courseLevel").prop('disabled', true);
            $('#courseDay').prop('disabled', true);
            $("#courseTimeHour").prop('disabled', true);
            $("#courseTimeMinute").prop('disabled', true);

        }).fail(function (data) {
            //alert("fail");
            console.log(data);
        });
    });

    // Submission
    $("#courseInfoFrm").validate({
        rules: {
            capacity: {
                number: true
            }
        },
        submitHandler: function (form, event) {

            $('#help-block').remove();
            $('#btnSubmitCourseInfo').prop('disabled', true);
            $('#btnSubmitCourseInfo').text('In Progress');
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
                        $('#help-block').remove();
                        $('#statusMsg').append('<div class="alert alert-danger" id="help-block">' + data.message + '</div>');
                    } else {// Success !
                        document.getElementById("courseInfoFrm").reset();
                        $('#help-block').remove();
                        $('#statusMsg').append('<div class="alert alert-success" id="help-block">' + data.message + '</div>');
                        setTimeout(function () {
                            $('#courseInfoModal').modal('hide');
                        }, 100);
                        setTimeout(function () {
                            location.reload();
                        }, 100);

                    }
                }).fail(function (data) {
                $('#btnSubmitcourseInfo').prop('disabled', false);
                $('#btnSubmitCourseInfo').text('Retry');
                console.log(data);
            });
            event.preventDefault();

        }
    });

    // detect input change, get the name from DB
    $("#courseDay").on("change", function () {
        get_course_schedule();

    });

    $("#courseActivationFrm").validate({
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

function get_course_schedule() {
    var UrlGetName = "../get_schedule/" + $("#courseDay").val();
    $.getJSON(UrlGetName, function (data) {
        console.log(data);
    }).done(function (data) {
        $("#course_schedule").find('option').remove();
        $("#course_schedule").append('<option value="" disabled selected></option>');
        $.each(data, function (index, item) {
            $("#course_schedule").append('<option value="' + item.schedule_id + '">' + item.slot_time + '</option>');
        });

    }).fail(function (data) {

        console.log(data);
    });
}
