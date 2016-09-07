/**
 *
 */
var post_url = 'tuitionFeeReceive';
var activation_url;
var who_click;

$(document).ready(function() {
	$(":input").inputmask();

	$('#payment_date').datepicker({
		format : "yyyy-mm-dd",
		autoclose : true,
		todayHighlight : true
	}).datepicker("setDate", new Date());;

	$('#tuitionFeeReceivableModal').on('shown.bs.modal', function() {
		$('#stdSearch').focus();
		if (who_click == 'update') {
			$("#stdReceipt").focus();
		}
	});

	// To make sure it is clean for the next show
	$('#tuitionFeeReceivableModal').on('hidden.bs.modal', function() {
		$('#help-block').remove();
		$('#btnSubmitTuitionFeeReceivable').prop('disabled', false);
		document.getElementById("tuitionFeeReceivableForm").reset();
	});

	$('#btnTuitionFeeReceivable').on('click', function() {
		post_url = 'tuitionFeeReceive';
		$('#tuitionFeeReceivableModal').modal('show');
	});

	// detect input change, get the name from DB
	$("#stdSearch").on("input", function() {
		if ($("#stdSearch").val() == "")
			$('.search_result').remove();
		else {
			var UrlGetName = "search_name/" + $("#stdSearch").val();
			$.getJSON(UrlGetName, function(data) {
				console.log(data);
			}).done(function(data) {
				$('.search_result').remove();
				$.each(data, function(i, item) {
					var appendTxt = '';
					appendTxt = '<div class="alert alert-info search_result">  <a href="#" class="copySearchValue">' + item.student_name + ' (' + item.student_identity + ')</a>';
					appendTxt += '<input type="hidden" class="temp_id" value="' + item.sid + '"/>';
					appendTxt += '<input type="hidden" class="temp_StdID" value="' + item.student_identity + '"/>';
					appendTxt += '<input type="hidden" class="temp_StdName" value="' + item.student_name + '"/>';
					appendTxt += '</div>';
					$('#stdSearchResult').append(appendTxt);
				});

			}).fail(function(data){
				console.log(data);
			});
		}
	});

	$('#tuitionFeeReceivableForm').on('click', '.copySearchValue', function() {
		copyID($(this));
	});

	$("#billPackage").on("change", function() {
		var tmpText = $('#billPackage option:selected').text();
		tmpText = tmpText.split("-");

		$('#billAmount').val(tmpText[tmpText.length - 1]);
	});

	// Submission
	$("#tuitionFeeReceivableForm").validate({
		submitHandler : function(form, event) {

			$('#help-block').remove();
			$('#btnSubmitTuitionFeeReceivable').prop('disabled', true);
			var formData = $(form).serialize();

			$.ajax({
				type : 'POST',
				url : post_url,
				data : formData,
				dataType : 'json',
				encode : true
			})
			.done(function(data) {
				$('#btnSubmitStdInfo').prop('disabled', false);
				console.log(data);
				if (data.error) {
					$('#btnSubmitTuitionFeeReceivable').prop('disabled', false);
					$('#help-block').remove();
					$('#statusMsg').append('<div class="alert alert-danger" id="help-block">' + data.message + '</div>');
				} else {
					$('#help-block').remove();
					$('#statusMsg').append('<div class="alert alert-success" id="help-block">' + data.message + '</div>');
					setTimeout(function() {
						$('#tuitionFeeReceivableModal').modal('hide');
					}, 500);
					setTimeout(function() {
						location.reload();
					}, 500);

				}
			}).fail(function(data) {
				$('#btnSubmitTuitionFeeReceivable').prop('disabled', false);
				console.log(data);
			});
			event.preventDefault();

		}
	});

	// Bill Void
	$('#tblTuitionFeeDashboard').on('click', '.voidReceipt', function() {

		activation_url = 'voidBill';
		var bill_id = $(this).closest('tr').children('td#bill_id').text();

		$('#activationID').val(bill_id);

		var r = confirm("Deactivate bill " + bill_id + "?");
		if (r == true) {
			$('#billActivationForm').submit();
		}
	});

	// bill package
	$('#billPackage').on('change', function() {
		var UrlGetName = "package_check/" + $('#billPackage').val();
		$('#btnSubmitTuitionFeeReceivable').prop('disabled', true);
		$.getJSON(UrlGetName, function(data) {
			console.log(data);
		}).done(function(data) {
			// dfdsf
			$('#btnSubmitTuitionFeeReceivable').prop('disabled', false);
			$('.search_result').remove();
			var allow_custom_date = data.allow_custom_date;
			var term = data.term;
			if (allow_custom_date == 'Y') {
				// if custom_date = Y, get the thing and append
				$.getJSON("custom_date_slots/" + term, function(data) {
					console.log(data);
				}).done(function(data) {
					// if
					$("#custom_date").css("display", "block");
					$('.custom_date_div').remove();
					$('#custom_date').append(data.str);
				});
			} else {
				$("#custom_date").css("display", "none");
				$('.custom_date_div').remove();
			}
		}).fail(function(data){
			$('#btnSubmitTuitionFeeReceivable').prop('disabled', false);
			alert('Fail to get bill package');
		});
	});

	$("#billActivationForm").validate({
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

		},
		success : function(error) {
			error.remove();
		}
	});

	// Bill Update - retrieve lastest info from db
	$('#tblTuitionFeeDashboard').on('click', '.btnUpdate', function() {

		var id = $(this).closest('tr').children('td#bill_id').text();
		var url = "billDetails/" + id;

		$.getJSON(url, function(data) {
			console.log(data);
		}).done(function(data) {
			$('#tuitionFeeReceivableModal').modal('show');
			post_url = 'tuitionFeeUpdate';
			who_click = 'update';

			$("#billID").val(data.bill_id);
			$("#stdReceipt").val(data.receipt_no);
			$("#stdName").val(data.student_name);
			$("#stdID").val(data.student_identity);
			$("#billPackage").val(data.package_id).prop('selected', true);

			$('#stdSearch').prop("readonly", true);
			$('#billPackage').prop("disabled", true);
			$('#modalTitle').text(' Update Bill');
			$('#btnSubmit').text('Update');
			$('#tuitionFeeReceivableModal').modal('show');

		}).fail(function(data) {
			//alert("fail");
			console.log(data);
		});
	});

	$('body').on('focus', ".slot_date", function() {
		var this_element = $(this);
		$(this).datepicker({
			format : "yyyy-mm-dd",
			autoclose : true,
			todayHighlight : true
		}).on('changeDate', function() {
			var UrlGetName = "get_slotTime/" + $(this).val();

			$.getJSON(UrlGetName, function(data) {
				console.log(data);
			}).done(function(data) {
				// dfdsf
				this_element.closest("div").next("div").find("option").remove();
				//$('option', $(this)).remove();
				var appendTxt = '';
				appendTxt = '<option selected disabled> </option>';
				$.each(data, function(i, item) {
					appendTxt += '<option value="' + item.slot_time + '" >' + item.slot_time_12 + '</option>';
				});

				this_element.closest("div").next("div").find("select").append(appendTxt);
				//console.log(this_element);
				//$('.slot_time').append(appendTxt);
			});
		});
	});

	$('#tuitionFeeReceivableModal').modal('show');

});

function copyID(this_ele) {
	var id = $(this_ele).closest('div').children('input.temp_id').val();
	var std_id = $(this_ele).closest('div').children('input.temp_StdID').val();
	var std_name = $(this_ele).closest('div').children('input.temp_StdName').val();

	$('#id').val(id);
	$('#stdName').val(std_name);
	$('#stdID').val(std_id);

	$('.search_result').remove();

	document.getElementById("stdReceipt").focus();
}

