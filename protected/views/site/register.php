<div class="register_user fg_form system_form_container">
	<!-- Register Form -->
	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'reg_form',
		'action' => 'javascript:void(0)',
		'htmlOptions' => array(
			'data-title' => 'Register a new user',
		),
	));?>
	<table>
	<tr>
		<td class="right"><?php echo $form->labelEx($model, 'username', array('class' => 'grey reg')); ?></td>
		<td><?php echo $form->textField($model, 'username', array('class' => 'field')); ?></td>
	</tr>
	<tr>
		<td class="right"><?php echo $form->labelEx($model, 'password', array('class' => 'grey reg')); ?></td>
		<td><?php echo $form->passwordField($model, 'password', array('class' => 'field pnl_pwd')); ?></td>
	</tr>
	<tr>
		<td class="right"><?php echo $form->labelEx($model, 'conf_password', array('class' => 'grey reg')); ?></td>
		<td><?php echo $form->passwordField($model, 'conf_password', array('class' => 'field pnl_pwd')); ?></td>
	</tr>
	<tr>
		<td class="right"><?php echo $form->labelEx($model, 'pcode', array('class' => 'grey reg')); ?></td>
		<td><?php echo $form->textField($model, 'pcode', array('class' => 'field')); ?></td>
	</tr>
	<tr>
		<td></td>
		<td><?php echo CHtml::submitButton('Register', array('id' => 'btn_register', 'name' => 'submit')); ?></td>
	</tr>
	</table>
	<?php $this->endWidget(); ?>
	<!-- Register Form end -->
	<div id="error_message_container"></div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$('#reg_form').formly({'theme':'Dark'}, function(e) {
        $('.callback').html(e);
    });
});
</script>

