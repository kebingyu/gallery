<?php
$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
switch (strval($code)) {
case '404':
default:
	$message = 'We are unable to find this page. Please try again.';
	break;
}
?>

<div class="error">
<h2><?php echo $message; ?></h2>
</div>

<?php //echo CHtml::encode($message); ?>
