/**
 * Javascript to be included in custom_attendance
 */
var post_url;
var activation_url;
var who_click;

$(document).ready(function() {
	
	$('#sessionDate').inputmask("9999-99-99");
	
	$('#classReplaceModal').on('shown.bs.modal', function() {
		$('#stdSearch').focus();
	});

	// To make sure it is clean for the next show
	$('#classReplaceModal').on('hidden.bs.modal', function() {
		$('#help-block').remove();
		document.getElementById("classReplaceFrm").reset();
	});

	$('#btnAddAttendance').on('click', function() {
		post_url = 'std_attendanceOverdueUpdate';
		who_click = 'addAttendance';
		$('#btnSubmitAttendance').prop('disabled', false);
		$('#modal_title').text('Staff Attendance');
		$('#modalStaffAttendance').modal('show');
	});

	// detect input change, get the name from DB
	$("#staffSearch").on("input", function() {
		if ($("#staffSearch").val() == "")
			$('.search_result').remove();
		else {
			var UrlGetName = "search_nameStaff/" + $("#staffSearch").val();
			$.getJSON(UrlGetName, function(data) {
				console.log(data);

			}).done(function(data) {
				// dfdsf
				$('.search_result').remove();
				$.each(data, function(i, item) {
					var appendTxt = '';
					appendTxt = '<div class="alert alert-info search_result">  <a href="#" class="copySearchValue">' + item.name + ' (' + item.identity + ')</a>';
					appendTxt += '<input type="hidden" class="temp_id" value="' + item.id + '"></input>';
					appendTxt += '<input type="hidden" class="temp_staffID" value="' + item.identity + '"></input>';
					appendTxt += '<input type="hidden" class="temp_staffName" value="' + item.name + '"></input>';
					appendTxt += '<input type="hidden" class="temp_suggest_salary" value="' + item.suggest_salary + '"></input>';
					appendTxt += '</div>';
					$('#staffSearchResult').append(appendTxt);
				});

			});
		}
	});

	
	$("#staffAttendanceFrm").submit(function(event) {
		$('#btnSubmitAttendance').prop('disabled', true);
		var formData = $('#staffAttendanceFrm').serialize();
		if ($('#sessionDate').val() == "") {
			alert("select a date");
			event.preventDefault();
			$('#btnSubmitAttendance').prop('disabled', false);
			return;
		}
		$.ajax({
			type : 'POST',
			url : 'staff_AttendanceNew',
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
				}, 100);
			}
		}).fail(function(data) {
			$('#btnSubmitAttendance').prop('disabled', false);
			// console.log(data);
		});
		event.preventDefault();

	});

	$('#staffAttendanceFrm').on('click', '.copySearchValue', function() {
		copyID($(this));
	});

	$('#sessionDate').datepicker({
		format : "yyyy-mm-dd",
		autoclose : true,
		todayHighlight : true
	});
	
	$("#attendanceActivationFrm").validate({
		submitHandler : function(form, event) {
			var formData = $(form).serialize();

			$.ajax({
				type : 'POST',
				url : "staff_AttendanceVoid",
				data : formData,
				dataType : 'json',
				encode : true
			}).done(function(data) {

				console.log(data);
				if (!data.error) {
					setTimeout(function() {
						location.reload();
					}, 100);
				} else {
					alert("Fail to void attendance.");
				}
			}).fail(function(data) {
				console.log(data);
			});
			event.preventDefault();

		}
	});
	
	// Staff Attendance De-Activate
	$('#tblAttendance').on('click', '.attendanceVoid', function() {
		
		var att_id = $(this).closest('tr').children('td#att_id').text();

		$('#activationID').val(att_id);

		var r = confirm("Comfirm ?");
		if (r == true) {
			$('#attendanceActivationFrm').submit();
		}
	});
});

function copyID(this_ele) {

	var id = $(this_ele).closest('div').children('input.temp_id').val();
	var staff_id = $(this_ele).closest('div').children('input.temp_staffID').val();
	var staff_name = $(this_ele).closest('div').children('input.temp_staffName').val();
	var suggest_salary = $(this_ele).closest('div').children('input.temp_suggest_salary').val();

	$('#staff_uid').val(id);
	$('#staffName').val(staff_name);
	$('#staffID').val(staff_id);
	$('#suggest_salary').val(suggest_salary);

	$('.search_result').remove();

}
