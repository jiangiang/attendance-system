/**
 *
 */
var post_url;
var activation_url;
var who_click;
var course_id = "";
// Modal for new student registration
$(document).ready(function() {

	$('#studentLogForm').on('click', '.copySearchValue', function() {
		copyID($(this));
	});

	$('#btnNewStudentLog').on('click', function() {
		post_url = 'student_log_new';
		$('#btnSubmit').prop('disabled', false);
		$('#modal_title').text('New Student Log');
		$('#modal_student_log').modal('show');
	});

	// detect input change, get the name from DB
	$("#studentSearch").on("keyup", function() {
		if ($("#studentSearch").val() == "")
			$('.search_result').remove();
		else {
			var UrlGetName = "searchName/" + $("#studentSearch").val();
			$.getJSON(UrlGetName, function(data) {
				console.log(data);
			}).done(function(data) {
				// dfdsf
				$('.search_result').remove();
				//$('#btnSubmitReplace').prop('disabled', true);
				$.each(data, function(i, item) {
					var appendTxt = '';
					appendTxt = '<div class="alert alert-info search_result">  <a href="#" class="copySearchValue">' + item.student_name + ' (' + item.student_identity + ')</a>';
					appendTxt += '<input type="hidden" class="temp_id" value="' + item.sid + '">';
					appendTxt += '<input type="hidden" class="temp_studentName" value="' + item.student_name + '">';
					appendTxt += '</div>';
					$('#studentSearchResult').append(appendTxt);
				});

			});
		}
	});
	
	// Submission -- Replacement
	$("#studentLogForm").validate({
		submitHandler : function(form, event) {

			$('#help-block').remove();
			$('#btnSubmit').prop('disabled', true);
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
					$('#btnSubmit').prop('disabled', false);
					$('#help-block').remove();
					$('#statusMsg').append('<div class="alert alert-danger" id="help-block">' + data.message + '</div>');
				} else {// Success !
					$('#help-block').remove();
					$('#statusMsg').append('<div class="alert alert-success" id="help-block">' + data.message + '</div>');
					setTimeout(function() {
						$('#classReplaceModal').modal('hide');
					}, 100);
					setTimeout(function() {
						location.reload();
					}, 50);

				}
			}).fail(function(data) {
				$('#btnSubmit').prop('disabled', false);
				console.log(data);
			});
			event.preventDefault();
		}
	});
	
	// Log De-Activate
	$('#tbl_student_log').on('click', '.deactivate_log', function() {
		activation_url = "student_log_void";
		var log_id = $(this).closest('tr').children('td.log_id').text();

		$('#activationID').val(log_id);

		var r = confirm("Deactivate Log" + log_id + "?");
		if (r == true) {
			$('#activationForm').submit();
		}
	});
	
	$("#activationForm").validate({
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
});

function copyID(this_ele) {

	var student_id = $(this_ele).closest('div').children('input.temp_id').val();
	var student_name = $(this_ele).closest('div').children('input.temp_studentName').val();

	$('#student_id').val(student_id);
	$('#studentName').val(student_name);
	$('.search_result').remove();

}