/**
 * Javascript to be included in custom_attendance
 */
var post_url;
var activation_url;
var who_click;
// Modal for new student registration
$(document).ready(function() {

	$('#classReplaceModal').on('shown.bs.modal', function() {
		$('#stdSearch').focus();
	});

	// To make sure it is clean for the next show
	$('#classReplaceModal').on('hidden.bs.modal', function() {
		$('#help-block').remove();
		document.getElementById("classReplaceFrm").reset();
	});

	$('#btnClassOverdue').on('click', function() {
		post_url = 'std_attendanceOverdueUpdate';
		who_click = 'classOverdue';
		$('#btnSubmitReplace').prop('disabled', false);
		$('#modal_title').text('OVERDUE Replacement');
		$('#classReplaceModal').modal('show');
	});

	$('#btnClassReplace').on('click', function() {
		post_url = 'std_attendanceReplaceUpdate';
		who_click = 'classReplace';
		$('#btnSubmitReplace').prop('disabled', false);
		$('#modal_title').text('Class Replacement');
		$('#classReplaceModal').modal('show');
	});

	// detect input change, get the name from DB
	$("#stdSearch").on("input", function() {
		$('#btnSubmitReplace').prop('disabled', true);
		if ($("#stdSearch").val() == "") {
			$('.search_result').remove();
		} else {
			var UrlGetName = "searchName/" + $("#stdSearch").val();
			$.getJSON(UrlGetName, function(data) {
				console.log(data);
			}).done(function(data) {
				// dfdsf
				$('.search_result').remove();
				$.each(data, function(i, item) {
					var appendTxt = '';
					appendTxt = '<div class="alert alert-info search_result">  <a href="#" class="copySearchValue">' + item.std_name + ' (' + item.std_identity + ')</a>';
					appendTxt += '<input type="hidden" class="temp_id" value="' + item.id + '"></input>';
					appendTxt += '<input type="hidden" class="temp_StdID" value="' + item.std_identity + '"></input>';
					appendTxt += '<input type="hidden" class="temp_StdName" value="' + item.std_name + '"></input>';
					appendTxt += '</div>';
					$('#stdSearchResult').append(appendTxt);
				});

			});
		}
		$('#btnSubmitReplace').prop('disabled', false);
	});

	// Submission -- Replacement
	$("#classReplaceFrm").validate({
		submitHandler : function(form, event) {

			$('#help-block').remove();
			$('#btnSubmitReplace').prop('disabled', true);
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
				console.log(data);
				if (data.error) {
					$('#btnSubmitReplace').prop('disabled', false);
					$('#help-block').remove();
					$('#statusMsg').append('<div class="alert alert-danger" id="help-block">' + data.message + '</div>');
				} else {// Success !
					$('#help-block').remove();
					$('#statusMsg').append('<div class="alert alert-success" id="help-block">' + data.message + '</div>');
					setTimeout(function() {
						$('#classReplaceModal').modal('hide');
					}, 800);
					setTimeout(function() {
						location.reload();
					}, 1500);

				}
			}).fail(function(data) {
				$('#btnSubmitReplace').prop('disabled', false);
				console.log(data);
			});
			event.preventDefault();
		}
	});

	$("#attendaceFrm").submit(function(event) {
		var formData = $('#attendaceFrm').serialize();
		$.ajax({
			type : 'POST',
			url : 'std_attendanceUpdate',
			data : formData,
			dataType : 'json',
			encode : true
		})
		// using the done promise callback
		.done(function(data) {
			console.log(data);
			if (data.error) {
				alert('update error!');
			} else {// Success !
				alert('update Success!');
				setTimeout(function() {
					location.reload();
				}, 500);

			}
		}).fail(function(data) {
			console.log(data);
			alert('update error - critical!');
		});
		event.preventDefault();

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
					appendTxt += '<input type="hidden" class="temp_lessonLeft" value="' + item.lesson_left + '"></input>';
					appendTxt += '<input type="hidden" class="temp_billID" value="' + item.bill_id + '"></input>';
					appendTxt += '<input type="hidden" class="temp_expiry" value="' + item.expiry_date + '"></input>';
					appendTxt += '<input type="hidden" class="temp_level" value="' + item.level_name + '"></input>';
					appendTxt += '<input type="hidden" class="temp_insturctor" value="' + item.instructor + '"></input>';
					appendTxt += '<input type="hidden" class="temp_StdID" value="' + item.std_identity + '"></input>';
					appendTxt += '<input type="hidden" class="temp_StdName" value="' + item.std_name + '"></input>';
					appendTxt += '<input type="hidden" class="temp_StdLog" value="' + item.log + '"></input>';
					appendTxt += '</div>';
					$('#stdSearchResult').append(appendTxt);
				});

			});
		}
	});

	$('#classReplaceFrm').on('click', '.copySearchValue', function() {
		copyID($(this));
	});
	
	
	$('#slot_date').datepicker({
		format : "yyyy-mm-dd",
		autoclose : true,
		todayHighlight : true
	}).on('changeDate', function() {
		$('#schedule_id').prop('disabled', true);
		var UrlGetName = "get_slotTime/" + $('#slot_date').val();
		$.getJSON(UrlGetName, function(data) {
			console.log(data);
		}).done(function(data) {
			// dfdsf
			$('#schedule_id option').remove();
			var appendTxt = '';
			appendTxt = '<option selected disabled> </option>';
			$.each(data, function(i, item) {
				appendTxt += '<option value="' + item.schedule_id + '" >' + item.slot_time_12 + '</option>';
			});
			$('#schedule_id').append(appendTxt);
			$('#schedule_id').prop('disabled', false);
		});
	});

	$('#schedule_id').on('change', function() {
		$('#slotFrm').submit();
	});
});

function copyID(this_ele) {

	var id = $(this_ele).closest('div').children('input.temp_id').val();
	var std_id = $(this_ele).closest('div').children('input.temp_StdID').val();
	var std_name = $(this_ele).closest('div').children('input.temp_StdName').val();
	var lesson_left = $(this_ele).closest('div').children('input.temp_lessonLeft').val();
	var expiry = $(this_ele).closest('div').children('input.temp_expiry').val();
	var level = $(this_ele).closest('div').children('input.temp_level').val();
	var instructor = $(this_ele).closest('div').children('input.temp_insturctor').val();
	var bill_id = $(this_ele).closest('div').children('input.temp_billID').val();
	var log = $(this_ele).closest('div').children('input.temp_StdLog').val();

	$('#id').val(id);
	$('#stdName').val(std_name);
	$('#stdID').val(std_id);
	$('#stdClassleft').val(lesson_left);
	$('#stdExpiry').val(expiry);
	$('#stdLevel').val(level);
	$('#stdInstructor').val(instructor);
	$('#bill_ID').val(bill_id);
	$('#stdLog').val(log);

	$('.search_result').remove();

}
