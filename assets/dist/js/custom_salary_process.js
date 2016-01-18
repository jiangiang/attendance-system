/**
 *
 */
var post_url;
var activation_url;
var who_click;

$(document).ready(function() {

	$('#salaryPayoutModal').on('shown.bs.modal', function() {
		$('#bonusSalary').focus();
	});

	// To make sure it is clean for the next show
	$('#salaryPayoutModal').on('hidden.bs.modal', function() {
		$('#help-block').remove();
		document.getElementById("salaryPayoutForm").reset();
	});

	$('#btnProcessSalary').on('click', function() {
		post_url = 'tuitionFeeReceive';
		var r = confirm("This could not be undone !");
		if (r == true) {
			$('#billActivationFrm').submit();
		}
	});

	$('#btnSubmitSalary').on('click', function() {
		if ($('#bonusSalary').val() > 0) {
			if ($('#remark').val() == "") {
				alert("Please enter remark for the extra bonus");
				return;
			}
		}
		var r = confirm("Click yes to process salary !");
		if (r == true) {
			$('#salaryPayoutForm').submit();
		}
	});

	$('#adjustmentSalary').on('input', function() {
		var bonus = parseFloat($('#adjustmentSalary').val());
		var totalSalary = bonus + parseFloat($('#defaultSalary').val());
		$('#totalSalary').val(totalSalary);
	});

	// Submission
	$("#salaryPayoutForm").validate({
		rules : {
			'bonusSalary' : 'number'
		},
		submitHandler : function(form, event) {

			$('#help-block').remove();
			$('#btnSubmitSalary').prop('disabled', true);
			$("#defaultSalary").prop('readonly', false);
			var formData = $(form).serialize();

			$.ajax({
				type : 'POST',
				url : 'employee_salary_update',
				data : formData,
				dataType : 'json',
				encode : true
			})
			// using the done promise callback
			.done(function(data) {
				console.log(data);
				if (data.error) {
					$('#btnSubmitTuitionFeeReceivable').prop('disabled', false);
					$('#help-block').remove();
					$('#statusMsg').append('<div class="alert alert-danger" id="help-block">' + data.message + '</div>');
				} else {// Success !
					$('#help-block').remove();
					$('#statusMsg').append('<div class="alert alert-success" id="help-block">' + data.message + '</div>');
					setTimeout(function() {
						$('#tuitionFeeReceivableModal').modal('hide');
					}, 100);
					setTimeout(function() {
						location.reload();
					}, 100);

				}
			}).fail(function(data) {
				$('#btnSubmitSalary').prop('disabled', false);
				alert("Please Contact Administrator - CODE 100");
			});
			event.preventDefault();

		}
	});

	// get the salary info from db
	$('#tblEmployeeSalaryPayout').on('click', '.btnSalaryPayout', function() {

		var id = $(this).closest('tr').children('td#employee_id').text();
		var month = $('#month').val();
		var year = $('#year').val();

		var url = "employee_details/" + id + "/" + year + "/" + month;
		$.getJSON(url, function(data) {
			console.log(data);
		}).done(function(data) {
			$("#uid").val(data.id);
			$("#employeeName").val(data.name);
			$("#employeeID").val(data.identity);
			$("#defaultSalary").val(data.salary);
			$('#totalSalary').val(data.salary);
			$('#sessionCount').val(data.session_count);

			$('#modalTitle').text(' Salary Payout ');
			$('#btnSubmit').text('Update');
			$('#salaryPayoutModal').modal('show');

		}).fail(function(data) {
			alert("Please Contact Administrator - CODE 101");
		});
	});
});
