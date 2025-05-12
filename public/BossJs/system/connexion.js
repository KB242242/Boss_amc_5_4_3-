			$(document).ready(function () {
				$('#notification_msg').on('click', function () {
				$("#notification_msg").hide();
				});
				$('#loader').css('visibility', 'hidden');
				$('#myform').submit(function (event) {
					 $('#loader').css('visibility', 'visible');
				});
			})
