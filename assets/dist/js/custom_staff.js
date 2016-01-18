/**
 *
 */
var post_url;
var activation_url;
var who_click;
// Modal for new student registration
$(document).ready(function() {
	$(":input").inputmask();

	$('#staffInfoModal').on('shown.bs.modal', function() {
		if (who_click == 'NewStaff')
			$('#staffName').focus();
		else if (who_click == 'UpdateStaff')
			$('#staffContact').focus();
	});

	// To make sure it is clean for the next show
	$('#staffInfoModal').on('hidden.bs.modal', function() {
		$('#help-block').remove();
		document.getElementById("staffInfoFrm").reset();
	});

	$('#btnstaffNew').on('click', function() {
		post_url = 'staffNew';
		who_click = 'NewStaff';
		$('#staffName').prop("readonly", false);
		$('#staffGender').prop("disabled", false);
		$('#loginName').prop('readonly', false);
		$('#staffInfoModalTitle').text(' New Staff');
		$('#btnSubmitstaffInfo').text('Create');
		$('#staffInfoModal').modal('show');
	});

	$('.btnStaffUpdate').on('click', function() {
		post_url = 'staffUpdate';
		who_click = 'UpdateStaff';
		$('#staffName').prop("readonly", true);
		$('#staffGender').prop("disabled", true);
		$('#staffInfoModalTitle').text(' Update Staff Info');
		$('#btnSubmitstaffInfo').text(' Update!');
		$('#staffInfoModal').modal('show');
	});

	// Staff De-Activate
	$('#tblActiveStaff').on('click', '.staffDeactivate', function() {

		activation_url = 'staffDeactivate';
		var student_id = $(this).closest('tr').children('td#staff_id').text();
		var student_name = $(this).closest('tr').children('td#staff_name').text();

		$('#activationName').val(student_name);
		$('#activationID').val(student_id);

		var r = confirm("Deactivate " + student_name + "?");
		if (r == true) {
			$('#staffActivationFrm').submit();
		}
	});
	
	// Staff Activate
	$('#tblInactiveStaff').on('click', '.staffActivate', function() {

		activation_url = 'staffActivate';
		var student_id = $(this).closest('tr').children('td#staff_id').text();
		var student_name = $(this).closest('tr').children('td#staff_name').text();

		$('#activationName').val(student_name);
		$('#activationID').val(student_id);
		var r = confirm("Activate " + student_name + "?");
		if (r == true) {
			$('#staffActivationFrm').submit();
		}
	});

	// Staff Update - retrieve lastest info from db
	$('#tblActiveStaff').on('click', '.btnStaffUpdate', function() {

		var staff_id = $(this).closest('tr').children('td#staff_id').text();
		var url = "getStaffInfo/" + staff_id;

		$.getJSON(url, function(data) {
			console.log(data);
		}).done(function(data) {
			// lessonVenueReload();
			$("#staffID").val(data.id);
			$("#staffName").val(data.name);
			$("#staffShortName").val(data.short_name);
			$("#staffIdentity").val(data.identity);
			$("#staffContact").val(data.contact);
			$('#staffGender').val(data.gender).prop("selected", true);
			$("#staffEmail").val(data.email);
			$("#staffAddr1").val(data.addr_building);
			$("#staffAddr2").val(data.addr_street);
			$("#Postcode").val(data.addr_postkod);
			$("#City").val(data.addr_city);
			$("#State").val(data.addr_state);
			$("#Country").val(data.addr_country);

			$('#staffType').val(data.type_id).prop("selected", true);

			if (data.login_name == null) {
				$('#loginName').prop('readonly', false);
			} else {
				$('#loginName').prop('readonly', true);
				$('#loginName').val(data.login_name);
			}

		}).fail(function(data) {
			alert("fail");
		});
	});

	$("#staffIdentity").on('input', function() {

		var UrlCheckIC = "checkID/" + $("#staffIdentity").val();
		$.getJSON(UrlCheckIC, function(data) {
			console.log(data);
		}).done(function(data) {
			if (data.record) {
				$('#help-block').remove();
				$('#btnSubmitstaffInfo').prop('disabled', true);
				$('#statusMsg').append('<div class="alert alert-danger" id="help-block">' + data.message + '</div>');
			} else if (!data.record) {
				$('#btnSubmitstaffInfo').prop('disabled', false);
				$('#help-block').remove();
			}
		});

	});

	// Submission
	$("#staffInfoFrm").validate({
		rules : {
			loginPassword2 : {
				equalTo : "#loginPassword"
			}
		},
		submitHandler : function(form, event) {

			$('#help-block').remove();
			$('#btnSubmitstaffInfo').prop('disabled', true);
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
				$('#btnSubmitstaffInfo').prop('disabled', false);
				console.log(data);
				if (data.error) {
					$('#help-block').remove();
					$('#statusMsg').append('<div class="alert alert-danger" id="help-block">' + data.message + '</div>');
				} else {// Success !
					document.getElementById("staffInfoFrm").reset();
					$('#help-block').remove();
					$('#statusMsg').append('<div class="alert alert-success" id="help-block">' + data.message + '</div>');
					setTimeout(function() {
						$('#staffInfoModal').modal('hide');
					}, 800);
					setTimeout(function() {
						location.reload();
					}, 1500);

				}
			}).fail(function(data) {
				$('#btnSubmitstaffInfo').prop('disabled', false);
				console.log(data);
			});
			event.preventDefault();

		}
	});

	$("#staffActivationFrm").validate({
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
					}, 100);
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
