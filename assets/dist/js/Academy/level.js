/**
 *
 */
var post_url;
var activation_url;
var who_click;
// Modal for new student registration
$(document).ready(function() {

	$('#catInfoModal').on('shown.bs.modal', function() {
		if (who_click == 'NewCat')
			$('#catName').focus();
		else if (who_click == 'UpdateCat')
			$('#catInfo').focus();
	});

	// To make sure it is clean for the next show
	$('#catInfoModal').on('hidden.bs.modal', function() {
		$('#help-block').remove();
		document.getElementById("categoryInfoFrm").reset();
	});

	$('#btnCatNew').on('click', function() {
		post_url = 'categoryCreate';
		who_click = 'NewCat';
		$('#catName').prop("readonly", false);
		$("#catName").prop('disabled', false);
		$('#catInfoModalTitle').text(' New Category');
		$('#btnSubmit').text('Create');
		$('#catInfoModal').modal('show');
	});

	// Course De-Activate
	$('#tblActiveCategory').on('click', '.categoryDeactivate', function() {

		activation_url = '../categoryDeactivate';
		var course_id = $(this).closest('tr').children('td#course_id').text();

		$('#activationID').val(course_id);

		var r = confirm("Deactivate this course ?");
		if (r == true) {
			$('#courseActivationFrm').submit();
		}
	});

	// Course Update - retrieve lastest info from db
	$('#tblActiveCategory').on('click', '.btnCategoryUpdate', function() {

		var category_id = $(this).closest('tr').children('td#category_id').text();
		var url = "ajax_get_category_details/" + category_id;

		$.getJSON(url, function(data) {
			console.log(data);
		}).done(function(data) {
			$("#catID").val(data.level_id);
			$("#catName").val(data.level_name);
			$("#catInfo").val(data.level_info);
			$("#CategoryDuration").val(data.duration_minute);
			$("#CategoryMaxCapacity").val(data.max_capacity);
			$("#private").val(data.IsPrivate).prop('selected', true);
			post_url = 'categoryUpdate';
			who_click = 'UpdateCat';
			$('#catName').prop("readonly", true);
			$('#catInfoModalTitle').text(' Update Category');
			$('#btnSubmit').text('Update');
			$('#catInfoModal').modal('show');

		}).fail(function(data) {
			//alert("fail");
			console.log(data);
		});
	});
	

	// Submission
	$("#categoryInfoFrm").validate({
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
				$('#btnSubmit').prop('disabled', false);
				console.log(data);
				if (data.error) {
					$('#help-block').remove();
					$('#statusMsg').append('<div class="alert alert-danger" id="help-block">' + data.message + '</div>');
				} else {// Success !
					document.getElementById("categoryInfoFrm").reset();
					$('#help-block').remove();
					$('#statusMsg').append('<div class="alert alert-success" id="help-block">' + data.message + '</div>');
					setTimeout(function() {
						$('#catInfoModal').modal('hide');
					}, 200);
					setTimeout(function() {
						location.reload();
					}, 250);

				}
			}).fail(function(data) {
				$('#btnSubmit').prop('disabled', false);
				console.log(data);
			});
			event.preventDefault();

		}
	});

	$("#categoryActivationFrm").validate({
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
