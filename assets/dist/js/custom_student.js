/**
 *
 */
var post_url;
var activation_url;
var who_click;
var course_id = "";
// Modal for new student registration
$(document).ready(function() {
	$(":input").inputmask();

	$('#stdInfoModal').on('shown.bs.modal', function() {
		if (who_click == 'NewStudent')
			$('#stdName').focus();
		else if (who_click == 'UpdateStudent')
			$('#stdContact').focus();
	});

	// To make sure it is clean for the next show
	$('#stdInfoModal').on('hidden.bs.modal', function() {
		course_id = "";

		$('#help-block').remove();
		document.getElementById("studentInfoFrm").reset();
	});

	$('.btnStudentNew').on('click', function() {
		post_url = 'stdNew';
		who_click = 'NewStudent';
		$('#stdName').prop("readonly", false);
		$('#stdGender').prop("disabled", false);
		$('#lessonVenue').prop("disabled", false);
		$('#stdInfoModalTitle').text(' New Student');
		$('#btnSubmitStdInfo').text('Create');
		lessonSlotTimeCheck();
		$('#stdInfoModal').modal('show');
	});

	$('.btnStudentUpdate').on('click', function() {
		post_url = 'stdUpdate';
		who_click = 'UpdateStudent';
		$('#stdName').prop("readonly", true);
		$('#stdGender').prop("disabled", true);
		//$('#lessonVenue').prop("disabled", true);
		$('#stdInfoModalTitle').text(' Update Student Info');
		$('#btnSubmitStdInfo').text(' Update!');
		$('#stdInfoModal').modal('show');
	});

	// Student Activate
	$('#tblInactiveStudent').on('click', '.studentActivate', function() {
		var student_id = $(this).closest('tr').children('td#student_id').text();
		var student_login = $(this).closest('tr').children('td#student_login').text();
		$('#activateID').val(student_id);
		var r = confirm("Activate " + student_login + "'s Account?");
		if (r == true) {
			$('#studentActivateFrm').submit();
		}
	});

	// Student Details
	$('#tblActiveStudent').on('click', '.showDetails', function() {
		var student_id = $(this).closest('tr').find('span#student_id_span').text();
		//alert(student_id);
		var url = "details/" + student_id;

		var myWindow = window.open(url, "pageDetail", "width=800, height=600, scrollbars=yes");
	});

	$("#lessonVenue, #lessonType, #lessonDay").on('change', function() {
		$('#btnSubmitStdInfo').prop('disabled', true);
		lessonSlotTimeCheck();

	});

	// Student De-Activate
	$('#tblActiveStudent').on('click', '.studentDeactivate', function() {

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
	$('#tblInactiveStudent').on('click', '.StdActivate', function() {

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

	// Student Update - retrieve lastest info from db
	$('#tblActiveStudent').on('click', '.btnStudentUpdate', function() {

		var student_id = $(this).closest('tr').children('td#student_id').text();
		var url = "getStudentInfo/" + student_id;

		$.getJSON(url, function(data) {
			console.log(data);
		}).done(function(data) {
			if (data.guardian_gender == null || data.guardian_gender == '') {
				$(' #stdGuardianGender option').filter(function() {
					return $(this).html() == "NA";
				}).prop("selected", true);
			}
			$("#studentID").val(data.id);
			$("#stdName").val(data.std_name);
			$("#stdID").val(data.std_identity);
			$("#stdContact").val(data.std_contact);
			$('#stdGender').val(data.std_gender).prop("selected", true);
			$("#stdEmail").val(data.std_email);
			$("#stdGuardian").val(data.guardian_name);
			$("#stdGuardianContact").val(data.guardian_contact);
			$("#stdAddr1").val(data.addr_building);
			$("#stdAddr2").val(data.addr_street);
			$("#Postcode").val(data.addr_postkod);
			$("#City").val(data.addr_city);
			$("#State").val(data.addr_state);
			$("#Country").val(data.addr_country);
			$('#lessonDay').val(data.slot_day).prop("selected", true);
			$('#lessonVenue').val(data.venue_id).prop("selected", true);
			$('#lessonType').val(data.level_id).prop("selected", true);
			lessonSlotTimeCheck();

			course_id = data.course_id;

		}).fail(function(data) {
			alert("fail");
		});
	});

	$("#stdID").on('input', function() {

		var UrlCheckIC = "checkID/" + $("#stdID").val();
		$.getJSON(UrlCheckIC, function(data) {
			console.log(data);
		}).done(function(data) {
			if (data.record) {
				$('#lessonType').val('').prop('selected', true);
				$('#statusMsg').append('<div class="alert alert-danger" id="help-block">' + data.message + '</div>');
			} else if (!data.record) {
				$('#help-block').remove();
			}
		});

	});

	// Submission
	$("#studentInfoFrm").validate({
		submitHandler : function(form, event) {

			$('#help-block').remove();
			$('#btnSubmitStdInfo').prop('disabled', true);
			var formData = $(form).serialize();

			$.ajax({
				type : 'POST',
				url : post_url,
				data : formData,
				dataType : 'json',
				encode : true
			})
			// using the done promise callback
			.done(function(data) {
				$('#btnSubmitStdInfo').prop('disabled', false);
				console.log(data);
				if (data.error) {
					$('#help-block').remove();
					$('#statusMsg').append('<div class="alert alert-danger" id="help-block">' + data.message + '</div>');
				} else {// Success !
					document.getElementById("studentInfoFrm").reset();
					$('#help-block').remove();
					$('#statusMsg').append('<div class="alert alert-success" id="help-block">' + data.message + '</div>');
					setTimeout(function() {
						$('#stdInfoModal').modal('hide');
					}, 800);
					setTimeout(function() {
						location.reload();
					}, 1500);

				}
			}).fail(function(data) {
				$('#btnSubmitStdInfo').prop('disabled', false);
				console.log(data);
			});
			event.preventDefault();

		}
	});

	$("#studentActivationFrm").validate({
		submitHandler : function(form, event) {
			var formData = $(form).serialize();

			$.ajax({
				type : 'POST',
				url : activation_url,
				data : formData,
				dataType : 'json',
				encode : true
			}).done(function(data) {

				console.log(data);
				if (!data.error) {
					setTimeout(function() {
						location.reload();
					}, 500);
				} else {
					alert("Fail to deactivate.");
				}
			}).fail(function(data) {
				console.log(data);
			});
			event.preventDefault();

		}
	});

	$('#btnNewStudentLog').on('click', function() {
		post_url = 'student_log_new';
		who_click = 'classOverdue';
		$('#btnSubmit').prop('disabled', false);
		$('#modal_title').text('New Student Log');
		$('#modal_student_log').modal('show');
	});

	// detect input change, get the name from DB
	$("#stdSearch").on("keyup", function() {
		if ($("#stdSearch").val() == "")
			$('.search_result').remove();
		else {
			var UrlGetName = "searchName/" + $("#stdSearch").val();
			$.getJSON(UrlGetName, function(data) {
				console.log(data);
			}).done(function(data) {
				// dfdsf
				$('.search_result').remove();
				//$('#btnSubmitReplace').prop('disabled', true);
				$.each(data, function(i, item) {
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
	var lessonType = $("#lessonType").val();
	var venue_id = $("#lessonVenue").val();
	var getDay = $("#lessonDay").val();
	var url = "getSlotTimeWithCap/" + getDay + "/" + lessonType + "/" + venue_id;
	$("#lessonTime").prop('disabled', true);
	$.getJSON(url, function(data) {
		console.log(data);
	}).done(function(data) {
		$("#lessonTime").prop('disabled', false);
		$("#lessonTime").find('option').remove();

		if ((data.length == 0)) {
			$("#lessonTime").append('<option value="">' + 'No slot available' + '</option>');
			$('#btnSubmitStdInfo').prop('disabled', true);
		} else {
			$('#btnSubmitStdInfo').prop('disabled', false);
			$.each(data, function(index, item) {
				$("#lessonTime").append('<option value="' + item.id + '">' + item.slot_time_str + '</option>');
			});
			/* Due to the asyn properties of the json
			 * THere might have some delay to wait for the result return
			 * and it result the wrong course_id be selected
			 * to prevent this we place the selection code inside this function
			 */
			if (course_id != "") {
				$('select#lessonTime').val(course_id).prop("selected", true);
			}
		}
	}).fail(function(data) {
		console.log(data);
	});
}

function copyID(this_ele) {

	var id = $(this_ele).closest('div').children('input.temp_id').val();
	var std_name = $(this_ele).closest('div').children('input.temp_StdName').val();

	$('#id').val(id);
	$('#stdName').val(std_name);

	$('.search_result').remove();

}