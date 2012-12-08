<!-- Top Navigation Bar Begin -->
<div id="top-navbar">
	<ul id="nav">
		<li class="home"><a href="/">Home</a></li>
		<li class="profile"><a href="/profile/index">Hello <?php echo Yii::app()->user->name; ?></a></li>
		<li class="gallery">Gallery
			<ul>
			<li><a href="/album/showgallery/private">My Gallery</a></li>
			<li><a href="/album/showgallery/public">Public Gallery</a></li>
			</ul>
		</li>
		<li class="upload"><a href="/services/upload">Upload</a></li>
		<li class="search">Search</li>
		<li><a href="/services/logout">Log out</a></li>
		<li><form id="search_form" method="GET" action="/services/search/"><input type="text" name="kw" /></form></li>
	</ul>
</div>
<script type="text/javascript">
var section = '<?php echo $this->getController()->section; ?>';
$('#nav > li').filter('[class="' + section + '"]').addClass('active');
</script>
<div class="ajax-loading">loading...</div>
<!-- Top Navigation Bar End -->
