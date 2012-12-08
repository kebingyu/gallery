<div class="left pnl_reg">
	<!-- Register Form -->
	<?php $form_reg = $this->beginWidget('CActiveForm', array(
		'id' => 'reg_form',
		'action' => 'javascript:void(0)',
		'enableClientValidation' => false,
		'focus' => array($model_reg, 'username'),
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'validateOnChange' => true,
			'validateOnType' => false,
		),
	));?>

	<?php echo $form_reg->errorSummary($model_reg); ?>
	<h1>Not a member yet? Sign Up!</h1>
	<div class="">
		<?php echo $form_reg->labelEx($model_reg, 'username', array('class' => 'grey reg')); ?>
		<?php echo $form_reg->textField($model_reg, 'username', array('class' => 'field')); ?>
		<?php echo $form_reg->error($model_reg, 'username'); ?>
	</div>
	<div class="">
		<?php echo $form_reg->labelEx($model_reg, 'password', array('class' => 'grey reg')); ?>
		<?php echo $form_reg->passwordField($model_reg, 'password', array('class' => 'field pnl_pwd')); ?>
		<?php echo $form_reg->error($model_reg, 'password'); ?>
	</div>
	<div class="">
		<?php echo $form_reg->labelEx($model_reg, 'conf_password', array('class' => 'grey reg')); ?>
		<?php echo $form_reg->passwordField($model_reg, 'conf_password', array('class' => 'field pnl_pwd')); ?>
		<?php echo $form_reg->error($model_reg, 'conf_password'); ?>
	</div>
	<div class="">
		<?php echo $form_reg->labelEx($model_reg, 'pcode', array('class' => 'grey reg')); ?>
		<?php echo $form_reg->textField($model_reg, 'pcode', array('class' => 'field')); ?>
		<?php echo $form_reg->error($model_reg, 'pcode'); ?>
	</div>
	<?php echo CHtml::submitButton('Register', array('id' => 'btn_register', 'name' => 'submit')); ?>
	<?php $this->endWidget(); ?>
	<!-- Register Form end -->
</div>
<div class="left">
	<div id="pnl_jserror"></div>
	<div id="pnl_error"></div>
</div>
