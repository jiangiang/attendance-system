/**
 * Javascript for course venue
 */
var post_url;
var activation_url;
var who_click;
// Modal for new student registration
$(document).ready(function() {
	$(":input").inputmask();

	$('#venueInfoModal').on('shown.bs.modal', function() {
		if (who_click == 'NewVenue')
			$('#venueName').focus();
		else if (who_click == 'UpdateVenue')
			$('#building').focus();
	});

	// To make sure it is clean for the next show
	$('#venueInfoModal').on('hidden.bs.modal', function() {
		$('#help-block').remove();
		document.getElementById("venueInfoFrm").reset();
	});

	$('#btnNew').on('click', function() {
		post_url = 'venueCreate';
		who_click = 'NewCat';
		$("#venueName").prop('disabled', false);
		$('#modalTitle').text(' New Venue');
		$('#btnSubmit').text('Create');
		$('#venueInfoModal').modal('show');
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
	$('#tblActiveCategory').on('click', '.btnUpdate', function() {

		var id = $(this).closest('tr').children('td#venue_id').text();
		var url = "getVenueInfo/" + id;

		$.getJSON(url, function(data) {
			console.log(data);
		}).done(function(data) {
			post_url = 'venueUpdate';
			who_click = 'UpdateCat';
			
			$("#venueID").val(data.venue_id);
			$("#venueName").val(data.venue_name);
			$("#building").val(data.venue_building);
			$("#street").val(data.venue_street);
			$("#postkod").val(data.venue_postkod);
			$("#city").val(data.city);
			$("#state").val(data.state);
			$("#country").val(data.country);
			
			$('#venueName').prop("readonly", true);
			$('#modalTitle').text(' Update Venue');
			$('#btnSubmit').text('Update');
			$('#venueInfoModal').modal('show');

		}).fail(function(data) {
			//alert("fail");
			console.log(data);
		});
	});
	

	// Submission
	$("#venueInfoFrm").validate({
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
					document.getElementById("venueInfoFrm").reset();
					$('#help-block').remove();
					$('#statusMsg').append('<div class="alert alert-success" id="help-block">' + data.message + '</div>');
					setTimeout(function() {
						$('#venueInfoModal').modal('hide');
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
