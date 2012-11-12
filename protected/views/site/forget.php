<?php $this->includeSharedTemplate('htmlhead'); ?>
</head>
<body>
	<div class="skip"><a href="#nav" accesskey="S" tabindex="1">Skip to Navigation</a></div>
	<div class="skip"><a href="#content" tabindex="2">Skip to Content</a></div>
	<div id="wrapper">
		<div id="w1_main">
			<div id="w2_content">
				<div class="reset-password fg_form">
					<form id="reset_password_form" data-title="Reset Your Password">
						<table>
						<tr>
							<td class="right"><label for="user_name">Your username</label></td>
							<td><input class="user-input" type="text" name="user_name" value="" /></td>
						</tr>
						<tr>
							<td class="right"><label for="user_email">Your email address</label></td>
							<td><input class="user-input" type="text" name="user_email" value="" /></td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="submit" name="submit" value="Submit" id="btn_reset_password" />
								<input type="reset" value="Reset" />
							</td>
						</tr>
						</table>
						<div id="reset_error"></div>
					</form>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript">
$(document).ready(function() {
	// enable formly
	$('#reset_password_form').formly({'theme':'Dark'}, function(e) {
        $('.callback').html(e);
    });
	// submit reset
	$('#btn_reset_password').click(function() {
		$.ajax({
			type : 'POST',
			url : '/reset',
			data : $('#reset_password_form').serialize(),
			success : function(data) {
				if (data.stat == 'success') {
					zebra_center('An email contains a new password has been sent to ' + data.email, 5000);
				} else {
					zebra_corner('Reset email failed. Please try again later.', 2000);
					var html = "";
					if (data.error) {
						for (var i in data.error) {
							html += data.error[i];
						}
					}
					$('#reset_error').html(html);
				}
			},
			error : function() {
				zebra_corner('an error occured. Please try again later.', 2000);
			},
			dataType : 'json'
		});
		return false;
	});
});
</script>
</body>
</html>

