<?php
$bDisplay = Yii::app()->user->hasFlash('display_login') ? true : false;
$currentUser = $user->username;
$last = $user->last_logout_time ? $user->last_logout_time : $user->last_login_time;
$email = $user->email ? $user->email : '(no email provided)';
?>
<div class="container profile">
    <div class="page-header">
        <h2><?php echo $currentUser;?>'s profile</h2>
	</div>
	<div class="profile-basic">
		<form id="profile_basic_form" class="fg_form" data-title="Basic Info">
			<table>
			<tr>
				<td class="right"><label>Username:</label></td>
				<td class="info">
					<?php echo $currentUser;?>
					<a class="toggle-password" href="javascript:void(0)">&nbsp;change password?</a>
				</td>
			</tr>
			<tr>
				<td class="right"><label>Create Time:</label></td>
				<td class="info"><?php echo date("F j, Y, g:i a", $user->create_time);?></td>
			</tr>
			<tr>
				<td class="right"><label>Last Login Time:</label></td>
				<td class="info"><?php echo date("F j, Y, g:i a", $last);?></td>
			</tr>
			<tr>
				<td class="right"><label for="email">Email:</label></td>
				<td class="info">
					<span class="user-email"><?php echo $email;?></span>
					<a class="toggle-email" href="javascript:void(0)">&nbsp;change email?</a>
				</td>
			</tr>
			</table>
		</form>
	</div>
	<div class="profile-password">
		<?php $form_password = $this->beginWidget('CActiveForm', array(
			'id' => 'profile_password_form',
			'action' => 'javascript:void(0)',
			'htmlOptions' => array(
				'class' => 'fg_form',
				'data-title' => 'Change Password',
			),
		));?>
			<?php echo $form_password->errorSummary($user); ?>
			<table>
			<tr>
			<td class="right"><?php echo $form_password->labelEx($user, 'password', array('label' => 'Current Password'));?></td>
			<td><?php echo $form_password->passwordField($user, 'password', array('class' => 'user-input', 'value' => ''));?></td>
			</tr>
			<tr>
			<td class="right"><?php echo $form_password->labelEx($user, 'new_password');?></td>
			<td><?php echo $form_password->passwordField($user, 'new_password', array('class' => 'user-input'));?></td>
			</tr>
			<tr>
			<td class="right"><?php echo $form_password->labelEx($user, 'conf_password');?></td>
			<td><?php echo $form_password->passwordField($user, 'conf_password', array('class' => 'user-input'));?></td>
			</tr>
			<tr>
			<td></td>
			<td>
				<?php echo CHtml::submitButton('Update', array(
					'id' => 'btn_profile_password', 
					'name' => 'submit',
				));?>
				<?php echo CHtml::resetButton();?>
				<input type="button" class="close-password" value="Cancel" />
			</td>
			</tr>
			</table>
			<div id="email_error"></div>
		<?php $this->endWidget(); ?>
	<!--
		<form id="profile_password_form" class="fg_form" data-title="Change Password">
			<table>
			<tr>
				<td class="right"><label for="password_current">Current Password</label></td>
				<td><input class="user-input" type="password" name="password_current" value="" /></td>
			</tr>
			<tr>
				<td class="right"><label for="password_new">New Password</label></td>
				<td><input class="user-input" type="password" name="password_new" value="" /></td>
			</tr>
			<tr>
				<td class="right"><label for="password_confirm">Confirm Password</label></td>
				<td><input class="user-input" type="password" name="password_confirm" value="" /></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" name="submit" value="Update" id="btn_profile_password" />
					<input type="reset" value="Reset" />
					<input type="button" class="close-password" value="Cancel" />
				</td>
			</tr>
			</table>
			<div id="password_error"></div>
		</form>
		-->
	</div>
	<div class="profile-email">
		<?php $form_email = $this->beginWidget('CActiveForm', array(
			'id' => 'profile_email_form',
			'action' => 'javascript:void(0)',
			'htmlOptions' => array(
				'class' => 'fg_form',
				'data-title' => 'Change Email',
			),
		));?>
			<?php echo $form_email->errorSummary($user); ?>
			<table>
			<tr>
			<td class="right"><?php echo $form_email->labelEx($user, 'password', array('label' => 'Current Password'));?></td>
			<td><?php echo $form_email->passwordField($user, 'password', array('class' => 'user-input', 'value' => ''));?></td>
			</tr>
			<tr>
			<td class="right"><?php echo $form_email->labelEx($user, 'new_email');?></td>
			<td><?php echo $form_email->textField($user, 'new_email', array('class' => 'user-input'));?></td>
			</tr>
			<tr>
			<td class="right"><?php echo $form_email->labelEx($user, 'conf_email');?></td>
			<td><?php echo $form_email->textField($user, 'conf_email', array('class' => 'user-input'));?></td>
			</tr>
			<tr>
			<td></td>
			<td>
				<?php echo CHtml::submitButton('Update', array('id' => 'btn_profile_email', 'name' => 'submit'));?>
				<?php echo CHtml::resetButton();?>
				<input type="button" class="close-email" value="Cancel" />
			</td>
			</tr>
			</table>
			<div id="email_error"></div>
		<?php $this->endWidget(); ?>
			<!--
		<form id="profile_email_form" class="fg_form" data-title="Change Email">
			<table>
			<tr>
				<td class="right"><label for="password_current">Current Password</label></td>
				<td><input class="user-input" type="password" name="password_current" value="" /></td>
			</tr>
			<tr>
				<td class="right"><label for="email_new">New Email</label></td>
				<td><input class="user-input" type="text" name="email_new" value="" /></td>
			</tr>
			<tr>
				<td class="right"><label for="email_confirm">Confirm Email</label></td>
				<td><input class="user-input" type="text" name="email_confirm" value="" /></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" name="submit" value="Update" id="btn_profile_email" />
					<input type="reset" value="Reset" />
					<input type="button" class="close-email" value="Cancel" />
				</td>
			</tr>
			</table>
			<div id="email_error"></div>
		</form>
		-->
	</div>

</div>

<script type="text/javascript">
$(document).ready(function() {
	// display login
	if (<?php echo $bDisplay ? 'true' : 'false'; ?>) {
		zebra_corner('Welcome back, <?php echo $currentUser; ?>!<br />You last logged in on: <?php echo date("F j, Y, g:i a", $last); ?>', 5000);
	}
	// enable formly
	$('#profile_basic_form').formly({'theme':'Dark'}, function(e) {
        $('.callback').html(e);
    });
	$('#profile_password_form').formly({'theme':'Dark'}, function(e) {
        $('.callback').html(e);
    });
	$('#profile_email_form').formly({'theme':'Dark'}, function(e) {
        $('.callback').html(e);
    });

	// toggle form display
	$('.toggle-password').click(function() {
		$('.profile-password').fadeIn();
	});
	$('.close-password').click(function() {
		$('.profile-password').fadeOut();
	});
	$('.toggle-email').click(function() {
		$('.profile-email').fadeIn();
	});
	$('.close-email').click(function() {
		$('.profile-email').fadeOut();
	});

	// update email
	$('#btn_profile_email').click(function() {
		$.ajax({
			type : 'POST',
			url : '/profile/update?cate=email',
			data : $('#profile_email_form').serialize(),
			success : function(data) {
				if (data.stat == 'success') {
					$('.user-email').html(data.email);
					$('.profile-email').fadeOut(function() {
						$('#profile_email_form input.user-input').val('');
					});
					zebra_center('email updated successfully', 2000);
				} else {
					zebra_corner('an error occured');
					var html = "";
					if (data.error) {
						for (var i in data.error) {
							html += data.error[i] + '<br/>';
						}
					}
					$('#email_error').html(html);
				}
			},
			error : function() {
				zebra_corner('an error occured');
			},
			dataType : 'json'
		});
		return false;
	});

	// update password
	$('#btn_profile_password').click(function() {
		$.ajax({
			type : 'POST',
			url : '/profile/update?cate=password',
			data : $('#profile_password_form').serialize(),
			success : function(data) {
				if (data.stat == 'success') {
					$('.profile-password').fadeOut(function() {
						$('#profile_password_form input.user-input').val('');
					});
					zebra_center('password updated successfully', 2000);
				} else {
					zebra_corner('an error occured');
					var html = "";
					if (data.error) {
						for (var i in data.error) {
							html += data.error[i] + '<br/>';
						}
					}
					$('#password_error').html(html);
				}
			},
			error : function() {
				zebra_corner('an error occured');
			},
			dataType : 'json'
		});
		return false;
	});
});
</script>
