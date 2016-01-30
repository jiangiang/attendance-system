/**
 *
 */
var post_url;
var activation_url;
var who_click;
var schedule_id = "";
// Modal for new student registration
$(document).ready(function () {
    $(":input").inputmask();

    $('#student_dob').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true
    });

    $('#modal_main').on('shown.bs.modal', function () {
        if (who_click == 'NewStudent')
            $('#student_name').focus();
        else if (who_click == 'UpdateStudent')
            $('#student_contact').focus();
    });

    // To make sure it is clean for the next show
    $('#modal_main').on('hidden.bs.modal', function () {
        course_id = "";

        $('#help-block').remove();
        document.getElementById("studentInfoFrm").reset();
    });

    $('.btn-new_student').on('click', function () {
        post_url = 'stdNew';
        who_click = 'NewStudent';
        $('#student_name').prop("readonly", false);
        $('#student_gender').prop("disabled", false);
        $('#lesson_venue').prop("disabled", false);
        $('#modal_title_text').text(' New Student');
        $('#btn-modal_submit').text('Create');
        lessonSlotTimeCheck();
        $('#modal_main').modal('show');
    });

    $('.btn-update').on('click', function () {
        post_url = 'stdUpdate';
        who_click = 'UpdateStudent';
        $('#student_name').prop("readonly", true);
        $('#student_gender').prop("disabled", true);
        $('#modal_title_text').text(' Update Student Info');
        $('#btn-modal_submit').text(' Update!');
        $('#modal_main').modal('show');
    });

    // Student Activate
    $('#tblInactiveStudent').on('click', '.studentActivate', function () {
        var student_id = $(this).closest('tr').children('td#student_id').text();
        var student_login = $(this).closest('tr').children('td#student_login').text();
        $('#activateID').val(student_id);
        var r = confirm("Activate " + student_login + "'s Account?");
        if (r == true) {
            $('#studentActivateFrm').submit();
        }
    });

    // Student Details
    $('#tblActiveStudent').on('click', '.btn-show_detail', function () {
        var student_id = $(this).closest('tr').find('span#student_id_span').text();
        //alert(student_id);
        var url = "details/" + student_id;

        var myWindow = window.open(url, "pageDetail", "width=800, height=600, scrollbars=yes");
    });

    $("#lesson_venue, #lesson_type, #lesson_day").on('change', function () {
        $('#btn-modal_submit').prop('disabled', true);
        lessonSlotTimeCheck();

    });

    // Student De-Activate
    $('#tblActiveStudent').on('click', '.btn-deactivate', function () {

        activation_url = 'stdDeactivate';
        var student_id = $(this).closest('tr').children('td#student_id').text();
        var student_name = $(this).closest('tr').children('td#student_name').text();

        $('#activationName').val(student_name);
        $('#activationID').val(student_id);

        var r = confirm("Deactivate " + student_name + "?");
        if (r == true) {
            $('#studentActivationFrm').submit();
        }
    });

    // Student Activate
    $('#tblInactiveStudent').on('click', '.StdActivate', function () {

        activation_url = 'stdActivate';
        var student_id = $(this).closest('tr').children('td#student_id').text();
        var student_name = $(this).closest('tr').children('td#student_name').text();

        $('#activationName').val(student_name);
        $('#activationID').val(student_id);
        var r = confirm("Activate " + student_name + "?");
        if (r == true) {
            $('#studentActivationFrm').submit();
        }
    });

    // =====================================================
    // Student Update - retrieve latest info from db
    // =====================================================
    $('#tblActiveStudent').on('click', '.btn-update', function () {

        var student_id = $(this).closest('tr').children('td#student_id').text();
        var url = "ajax_student_details/" + student_id;

        $.getJSON(url, function (data) {
            console.log(data);
        }).done(function (data) {
            if (data.guardian_gender == null || data.guardian_gender == '') {
                $(' #guardian_gender option').filter(function () {
                    return $(this).html() == "NA";
                }).prop("selected", true);
            }
            $("#student_sid").val(data.sid);
            $("#student_name").val(data.student_name);
            $("#student_id").val(data.student_identity);
            $("#student_dob").val(data.student_dob);
            $("#student_contact").val(data.student_contact);
            $('#student_gender').val(data.student_gender).prop("selected", true);
            $("#student_email").val(data.student_email);
            $("#guardian_name").val(data.guardian_name);
            $("#guardian_contact").val(data.guardian_contact);
            $("#address_1").val(data.address_line1);
            $("#address_2").val(data.address_line2);
            $("#postcode").val(data.postcode);
            $("#city").val(data.city);
            $("#state").val(data.state);
            $("#country").val(data.country);
            $('#lesson_day').val(data.slot_day).prop("selected", true);
            $('#lesson_venue').val(data.venue_id).prop("selected", true);
            $('#lesson_type').val(data.level_id).prop("selected", true);
            schedule_id = data.schedule_id;

            lessonSlotTimeCheck();

        }).fail(function (data) {            console.log("Fail, Please contact administrator");
        });
    });

    $("#student_id").on('input', function () {

        var UrlCheckIC = "checkID/" + $("#student_id").val();
        $.getJSON(UrlCheckIC, function (data) {
            console.log(data);
        }).done(function (data) {
            if (data.record) {
                $('#lesson_type').val('').prop('selected', true);
                $('#statusMsg').append('<div class="alert alert-danger" id="help-block">' + data.message + '</div>');
            } else if (!data.record) {
                $('#help-block').remove();
            }
        });

    });

    // Submission
    $("#studentInfoFrm").validate({
        submitHandler: function (form, event) {

            $('#help-block').remove();
            $('#btn-modal_submit').prop('disabled', true);
            $('#btn-modal_submit').text('In Progress');
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
                    $('#btn-modal_submit').text('DONE');
                    console.log(data);
                    if (data.error) {
                        $('#help-block').remove();
                        $('#statusMsg').append('<div class="alert alert-danger" id="help-block">' + data.message + '</div>');
                        $('#btn-modal_submit').prop('disabled', false);
                        $('#btn-modal_submit').text('Retry');
                    } else {// Success !
                        document.getElementById("studentInfoFrm").reset();
                        $('#help-block').remove();
                        $('#statusMsg').append('<div class="alert alert-success" id="help-block">' + data.message + '</div>');
                        setTimeout(function () {
                            $('#modal_main').modal('hide');
                        }, 100);
                        setTimeout(function () {
                            location.reload();
                        }, 100);

                    }
                }).fail(function (data) {
                $('#btn-modal_submit').prop('disabled', false);
                $('#btn-modal_submit').text('Retry');
                console.log(data);
            });
            event.preventDefault();

        }
    });

    $("#studentActivationFrm").validate({
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
                    }, 500);
                } else {
                    alert("Fail to deactivate.");
                }
            }).fail(function (data) {
                console.log(data);
            });
            event.preventDefault();

        }
    });

    $('#btnNewStudentLog').on('click', function () {
        post_url = 'student_log_new';
        who_click = 'classOverdue';
        $('#btnSubmit').prop('disabled', false);
        $('#modal_title').text('New Student Log');
        $('#modal_student_log').modal('show');
    });

    // detect input change, get the name from DB
    $("#stdSearch").on("keyup", function () {
        if ($("#stdSearch").val() == "")
            $('.search_result').remove();
        else {
            var UrlGetName = "searchName/" + $("#stdSearch").val();
            $.getJSON(UrlGetName, function (data) {
                console.log(data);
            }).done(function (data) {
                // dfdsf
                $('.search_result').remove();
                //$('#btnSubmitReplace').prop('disabled', true);
                $.each(data, function (i, item) {
                    var appendTxt = '';
                    appendTxt = '<div class="alert alert-info search_result">  <a href="#" class="copySearchValue">' + item.std_name + ' (' + item.std_identity + ')</a>';
                    appendTxt += '<input type="hidden" class="temp_id" value="' + item.id + '"></input>';
                    appendTxt += '<input type="hidden" class="temp_StdName" value="' + item.std_name + '"></input>';
                    appendTxt += '</div>';
                    $('#stdSearchResult').append(appendTxt);
                });

            });
        }
    });

});

function lessonSlotTimeCheck() {
    var lessonType = $("#lesson_type").val();
    var venue_id = $("#lesson_venue").val();
    var getDay = $("#lesson_day").val();
    var url = "ajax_slot_capacity/" + getDay + "/" + lessonType + "/" + venue_id;

    $("#schedule_id").prop('disabled', true);
    $.getJSON(url, function (data) {
        console.log(data);
    }).done(function (data) {
        $("#schedule_id").prop('disabled', false);
        $("#schedule_id").find('option').remove();

        if ((data.length == 0)) {
            $("#schedule_id").append('<option value="">' + 'No slot available' + '</option>');
            $('#btn-modal_submit').prop('disabled', true);
        } else {
            $.each(data, function (index, item) {
                $("#schedule_id").append('<option value="' + item.schedule_id + '">' + item.slot_time_str + '</option>');
            });
            /* Due to the asyn properties of the json
             * THere might have some delay to wait for the result return
             * and it result the wrong course_id be selected
             * to prevent this we place the selection code inside this function
             */
            if (schedule_id != "") {
                $('select#schedule_id').val(schedule_id).prop("selected", true);
            }
            $('#btn-modal_submit').prop('disabled', false);
        }
    }).fail(function (data) {
        console.log(data);
    });
}
