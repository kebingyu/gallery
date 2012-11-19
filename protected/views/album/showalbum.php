<?php
$nAlbumID = $this->get('album_id');
$sAlbumName = $this->get('album_name');
$sCate = $this->get('cate');
@session_start();
$currentUser = $_SESSION['username'];
?>
<div id="album-name">
	<h2 class="name"><?php echo $sAlbumName ?></h2>
	<?php
	if ($sCate === 'private') {
		echo '<a href="/album/editalbum/' . $nAlbumID . '" title="Edit this album"><div class="icon-large icon-pencil"></div></a>';
		echo '<a href="/album/showgallery/private" title="Back to my gallery"><div class="icon-large icon-home"></div></a>';
	} else {
		echo '<a href="/album/showgallery/public" title="Back to public gallery"><div class="icon-large icon-home"></div></a>';
	}
	?>
</div>
<div id="menu">
	<?php
	$oModel = new AlbumModel();
	$aList = $oModel->getAlbumList($sCate);
	$temp = '';
	foreach ($aList as $value) {
		$text = '';
		if ($value['album_id'] == $nAlbumID) {
			$text = "<a href=\"javascript:void(0)\" rel=\"{$value['album_id']}\" class=\"active\">{$value['album_name']}";
		} else {
			$text = "<a href=\"javascript:void(0)\" rel=\"{$value['album_id']}\">{$value['album_name']}";
		}
		$text .= $sCate === 'private' ? "</a>\n" : " by {$value['author']}</a>\n";
		echo $text;
        $temp .= "<option value=\"{$value['album_name']}\" rel=\"{$value['album_id']}\">{$value['album_name']}</option>";
	}
	?>
</div>
<div id="galleria"></div>
<script>
function createCommentBit(text, author, time, body) {
	text += '<div class="comment-bit"><table>';
	text += '<tr><td class="icon-large icon-parents"></td>';
	text += '<td class="comment-author">' + author + '</td>';
	text += '<td class="create-time">' + time + '</td></tr>';
	text += '<tr><td></td>';
	text += '<td colspan="2" class="comment-bit-body">' + body + '</td></tr>';
	text += '</table></div>';
	return text;
}

function getImage(cate, id, album_name, is_full) {
	album_name = typeof album_name === 'undefined' ? 'Default_album_name' : album_name;
	is_full = typeof is_full === 'undefined' ? false : is_full;
	var imgJson = new Array();
	var elem = $('#galleria');
	$.ajax({
		type : 'GET',
		url : '/album/getimage/' + cate + '/' + id, 
		success : function(data) {
			for (var p = 0, q = data.image.length; p < q; ++p) {
				imgJson.push({
					thumb : '/services/image/thumbnails/' + data.image[p].image_name,
					image : '/services/image/files/' + data.image[p].image_name,
					title : data.image[p].image_desc,
					md5 : data.image[p].image_name
				});	
			}
			if (imgJson.length) {
				if (elem.data('galleria')) {
					elem.data('galleria').load(imgJson);
					if (is_full) {

					}
					$('#album-name').children('h2').html(data.image[0].album_name);
				} else {
					// load galleria for the first time
					Galleria.run('#galleria', {
						dataSource : imgJson,
						//keepSource : true,
						width : 1000,
						height : 700,
						extend : function() {
							// add album select list
							var galleria = this;
							var text_append = '';
							text_append += '<select class="album-select" title="Choose a album" name="">';
							text_append += '<?php echo $temp; ?>';
							text_append += '</select>';
							galleria.addElement('select');
							galleria.$('select').append(text_append);
							galleria.appendChild('stage','select');
							// update comment panel if visible
							this.bind('loadfinish', function(e) {
								if ($('.galleria-comment-panel').is(':visible')) {
									var imgObj = this.getData(this.getIndex());
									$('.comment-head').html('Comment for ' + imgObj.title);
									$('.comment-head').attr('data-name', imgObj.title);
									$('#btn_post_comment').attr('data-name', imgObj.md5);
									$('.comment-body').html('');
									// load existing comments
									$.ajax({
										type : 'GET',
										url : '/services/comment/get?name=' + imgObj.md5, 
										success : function(query) {
											var text_append = '';
											for (var i = 0, j = query.length; i < j; ++i) {
												text_append = createCommentBit(text_append, query[i].author, query[i].create_time, query[i].body);
											}
											$('.comment-body').append(text_append);
										},
										error : function() {
											zebra_corner('An error occured');
										},
										dataType : 'json'
									});
								}
							});
						}
					});
				}
			} else {
				if (elem.data('galleria')) {
					imgJson.push({
						thumb : '/images/empty-album.jpg',
						image : '/images/empty-album.jpg',
						title : album_name,
					});	
					elem.data('galleria').load(imgJson);
					$('#album-name').children('h2').html(album_name);
				}
				zebra_corner('This album is empty');
			}
		}, 
		error : function() {
			zebra_corner('an error occured');
		},
		dataType : 'json'
	});
}

$(document).ready(function() {
	// retrieve image/album info for the active album
	getImage('<?php echo $sCate ?>', <?php echo $nAlbumID ?>);
	// register menu click event
	$('body').on('click', '#menu a', function(e) {
		e.preventDefault();
		// toggle active class
		$(this).addClass('active').siblings('.active').removeClass('active');
		getImage('<?php echo $sCate ?>', $(this).attr('rel'), $(this).html()); 	
	});
	// register galleria event
	Galleria.ready(function(e) {
		var max_char = 150;
		// bind album-list click event when galleria is ready
		$('.galleria-albumlist').click(function(e) {
			e.preventDefault();
			$('.galleria-select').slideDown('fast');
		});
		// count comment char
		$('.comment-textarea').keyup(function(e) {
			if (e.which == 13) {
				return false;
			} else {
				cnt = $(this).val().length;
				if (cnt == max_char) {
					e.preventDefault();
					zebra_center('Maximium character reached');
				} else if (cnt > max_char) {
					$(this).val($(this).val().substring(0, max_char));
				} else {
					$('.comment-byte-count').html(max_char - cnt);
				}
			}
		}).keypress(function(e) {
			if (e.which == 13) {
				return false;
			}
		});
		// post a new comment
		$('#btn_post_comment').click(function(e) {
			e.preventDefault();
			$.ajax({
				type : 'POST',
				url : '/services/comment/add', 
				data : {
					body : $('.comment-textarea').val(),
					image_name : $(this).attr('data-name')
				},
				success : function(query) {
					switch (query.stat) {
						case 'fail':
							zebra_corner('Fail to post comment');
							break;
						case 'error':
							zebra_corner('An error occured');
							break;
						case 'success':
							// insert comment
							var tmp = createCommentBit('', '<?php echo $currentUser; ?>', query.create_time, query.body);
							$('.comment-body').prepend(tmp);
							// reset 
							$('.comment-textarea').val('');
							$('.comment-byte-count').html(max_char);
							zebra_corner('Comment posted successfully');
							break;
						default:
					}
				},
				error : function() {
					zebra_corner('An error occured');
				},
				dataType : 'json'
			});
		});
	});

	// bind album list select event
	$('body').on('change', '.album-select', function () {
		var elem = $(this).find('option:selected');
		getImage('<?php echo $sCate ?>', elem.attr('rel'), elem.text(), true); 	
	});
});
</script>
