<?php $this->renderPartial('../shared/htmlhead'); ?>
<body>
	<div class="skip"><a href="#nav" accesskey="S" tabindex="1">Skip to Navigation</a></div>
	<div class="skip"><a href="#content" tabindex="2">Skip to Content</a></div>
	<div id="wrapper">
		<?php  
		if (Yii::app()->user->isGuest) {
			$this->widget('SlidingLogin');
		} else {
			$this->widget('TopNav');
		}
		?>
		<div id="w1_main">
			<div id="w2_content">
				<?php echo $content; ?>
			</div>
			<?php $this->renderPartial('../shared/footer'); ?>
		</div>
	</div>
</body>
</html>

