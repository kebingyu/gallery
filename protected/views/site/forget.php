<div class="reset_password fg_form system_form_container">
	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'reset_password_form',
		'action' => 'javascript:void(0)',
		'htmlOptions' => array(
			'data-title' => 'Reset your password: new password will be sent to the given email',
		),
	));?>
	<table>
	<tr>
		<td class="right"><?php echo $form->labelEx($model, 'username', array('label' => 'Your username *')); ?></td>
		<td><?php echo $form->textField($model, 'username', array('class' => 'user-input')); ?></td>
	</tr>
	<tr>
		<td class="right"><?php echo $form->labelEx($model, 'email', array('label' => 'Your email address *')); ?></td>
		<td><?php echo $form->textField($model, 'email', array('class' => 'user-input')); ?></td>
	</tr>
	<tr>
		<td></td>
		<td><?php echo CHtml::submitButton('Submit', array('id' => 'btn_reset_password', 'name' => 'submit')); ?></td>
	</tr>
	</table>
	<div id="error_message_container"></div>
	<?php $this->endWidget(); ?>
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
			url : '/profile/reset',
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
					$('#error_message_container').html(html);
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
