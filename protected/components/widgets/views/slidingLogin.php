<!-- Sliding login begin -->
<div id="toppanel">
    <div id="panel">
        <div class="content clearfix">
            <div class="left welcome">
                <h1>Welcome to Zhou Family's Photo Gallery</h1>
				<p>Recommended browsers:</p>
				<ul> 
					<li>Google Chrome</li>
					<li>Mozilla Firefox</li>
					<li>Apple Safari</li>
					<li>Internet Explorer (version 8 and above)</li>
				</ul>
            </div>
            <div class="left pnl_login">
                <!-- Login Form -->
				<?php $form_login = $this->beginWidget('CActiveForm', array(
					'id' => 'login_form',
					'action' => 'javascript:void(0)',
				));?>

				<?php echo $form_login->errorSummary($model_login); ?>
				<h1>Current Member Login</h1>
				<div class="">
					<?php echo $form_login->labelEx($model_login, 'username', array('class' => 'grey')); ?>
					<?php echo $form_login->textField($model_login, 'username', array('class' => 'field')); ?>
				</div>
				<div class="">
					<?php echo $form_login->labelEx($model_login, 'password', array('class' => 'grey')); ?>
					<?php echo $form_login->passwordField($model_login, 'password', array('class' => 'field pnl_pwd')); ?>
				</div>
				<?php echo CHtml::submitButton('Login', array('id' => 'btn_login', 'name' => 'submit')); ?>
				<?php $this->endWidget(); ?>
                <div class="lost_pwd"><a href="/forget">Forgot your password?</a></div>
                <!-- Login Form end -->
            </div>  
            <div class="left pnl_reg">
				<!-- Register Form -->
				<?php $form_reg = $this->beginWidget('CActiveForm', array(
					'id' => 'reg_form',
					'action' => 'javascript:void(0)',
					'enableClientValidation' => true,
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
        </div>
	</div> <!-- /login -->

    <!-- The tab on top -->
    <div class="tab">
        <ul class="login">
            <li class="left">&nbsp;</li>
            <li>Hello Guest!</li>
            <li class="sep">|</li>
            <li id="toggle">
                <a id="open" class="open" href="javascript:void(0)">Log In | Register</a>
                <a id="close" style="display: none;" class="close" href="javascript:void(0)">Close Panel</a>
            </li>
            <li class="right">&nbsp;</li>
        </ul>
    </div> <!-- / top -->

</div> <!-- Sliding login end -->
