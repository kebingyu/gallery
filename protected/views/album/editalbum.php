<?php
$aInfo = $this->get('album_info');
$sAlbumName = $aInfo['name'];
$sAlbumDesc = $aInfo['description'];
$nAlbumID = $aInfo['id'];
?>
<div id="album-name">
	<h2 class="name"><?php echo $sAlbumName ?></h2>
	<a href="/album/showalbum/<?php echo $nAlbumID ?>?cate=private" title="Back to view the album"><div class="icon-large icon-home"></div></a>
</div>
<div id="album-info">
	<form id="update_album_form" class="fg_form" data-title="Update album info">
		<div class="album_input">
			<!-- yes i am week -->
			<table>
			<tr>
				<td class="right"><label for="album_name">Album Name</label></td>
				<td><input class="input_text" type="text" name="album_name" size="30" title="Change your album's name" value="<?php echo $sAlbumName ?>" /></td>
			</tr>
			<tr>
				<td class="right"><label for="album_desc">Album Description</label></td>
				<td><textarea class="input_text double" name="album_desc" size="30" title="Write something about this album" ><?php echo $sAlbumDesc ?></textarea></td>
			</tr>
			<tr>
				<td class="right"><input type="checkbox" name="is_public" value="on" checked="checked" title="Public or personal album?" />Make it public!</td>
				<td><input type="submit" name="submit" value="Save" id="btn_update_album" title="Save" class="btn-form" /><input type="hidden" name="album_id" value="<?php echo $nAlbumID ?>" /></td>
			</tr>
			</table>
		</div>
		<div id="cg_error"></div>
	</form>
</div>
<div id="album-wrapper" class="clearfix"></div>
<script>
function updateAlbum() {
    var str = $('#update_album_form').serialize();
    $.ajax({
        type : 'POST',
        url : '/album/update',
        data : str,
        success : function(data) {
            if (data.stat == 'success') {
                window.location.href = '/album/showgallery/private';
                zebra_corner('album updated successfully');
            } else {
                zebra_corner('an error occured');
                var html = "";
                if (data.error) {
                    for (var i in data.error) {
                        html += data.error[i] + '<br/>';
                    }
                }
                $('#cg_error').html(html);
            }
		},
		error : function() {
        	zebra_corner('an error occured');
		},
        dataType : 'json'
    });
    return false;
}

function updateImage(elem) {
	var new_desc = $('input#image_desc').val() === '' ? 'default_image_name' : $('input#image_desc').val();
	new_desc = new_desc.replace(/\"/g, "'");
	$.ajax({
		type : 'POST',
		url : '/services/image/update/',
		data : $('#update_image_form').serialize(),
		success : function(query) {
			switch (query.stat) {
				case 'fail':
					zebra_corner('image name update failed');
					break;
				case 'error':
					zebra_corner('an error occured');
					break;
				case 'success':
					elem.html(new_desc);
					zebra_corner('image name updated successfully');
					break;
				default:
			}
		},
		error: function() {
			zebra_corner('an error occured');
		},
		dataType : 'json'
	});
}
$(document).ready(function() {
	// enable formly
	$('#update_album_form').formly({'theme':'Dark'}, function(e) {
        $('.callback').html(e);
    });
	// create album form
    $('#btn_update_album').click(function() {
        updateAlbum();
        return false;
    });

    $('#update_album_form').keypress(function (e) {
        if (e.which == 13) {
            updateAlbum();
            return false;
        }
    });

	var gName = "<?php echo $sAlbumName ?>";
	var text_append = '';

	// retrieve image/album info
	$.ajax({
		type : 'GET',
		url : '/album/getimage/private/<?php echo $nAlbumID ?>',
		success : function(data){
			for (var p = 0, q = data.image.length; p < q; ++p) {
				text_append += '<div class="panel-wrapper">';
				text_append += '<div class="panel-icon">';
				text_append += '<div class="icon-trash icon-large" title="Delete" rel="' + data.image[p].image_name + '" ></div>';
				text_append += '</div>';
				text_append += '<div class="panel-medium">';
				text_append += '<img src="/services/image/mediums/' + data.image[p].image_name + '" class="" />';
				text_append += '</div>';
				text_append += '<div class="panel-edit">';
				text_append += '<div class="icon-pencil icon-large" title="Edit" rel="' + data.image[p].image_name + '"></div>';
				text_append += '<div class="description">' + data.image[p].image_desc + '</div>';
				text_append += '</div>';
				text_append += '</div>';
			}
			$('#album-wrapper').append(text_append);
		},
		error: function() {
			zebra_corner('an error occured');
		},
		dataType : 'json'
	}); // end of ajax get

	// register pencil icon hover
	$('body').on('hover', '.panel-edit', function() {
		$(this).children('.icon-pencil').fadeToggle();
	})

	// register image name update
	.on('click', '.icon-pencil', function() {
		var elem = $(this).siblings('.description');
		var origin_desc = elem.html().replace(/\"/g, "'");
		var text_form = '<form id="update_image_form" title="Update image info" rel="' + $(this).attr('rel') + '"><br /><label for="image_desc">Photo Name</label><input type="text" id="image_desc" name="image_desc" value="' + origin_desc + '" /><input type="hidden" name="image_name" value="' + $(this).attr('rel') + '" /></form>';
		zd = $.Zebra_Dialog(text_form, {
			'type' : 'question',
			'title' : 'Update photo info',
			'keyboard' : true,
			'buttons' : [
				{
					caption : 'Save', 
					callback : function() {
						updateImage(elem);
					}
				}, 
				{
					caption : 'Cancel',
					callback : function() {
					}
				}
			],
			'onClose' : function(caption) {
				if (caption === 'Save') {
				}
			}
		});
	})
	
	.on('keypress', '#update_image_form', function(e) {
		if (e.which == 13) {
			e.preventDefault();
			var myrel = $(this).attr('rel');
			var elem = $('.icon-pencil').filter('[rel="' + myrel + '"]').siblings('.description');
			updateImage(elem);
			zd.close();
		}
	})
		
	// enable image delete
	.on('click', '.icon-trash', function() {
		var img_name = $(this).attr('rel');
		var div_wrapper = $(this).closest('div.panel-wrapper');
		$.Zebra_Dialog('Are you sure you want to perminantly DELETE this photo? ALL the comments will be DELETED as well!', {
			'type': 'question',
			'title': 'Delete Confirmation',
			'buttons':  ['Yes', 'No'],
			'onClose': function(caption) {
				if (caption === 'Yes') {
					// delete image from database and disk
					$.ajax({
						type : 'POST',
						url : '/services/upload/delete/',
						data : {
							imageName : img_name,
						},
						success : function(query) {
							switch (query.stat) {
								case 'fail':
									zebra_corner('delete image failed');
									break;
								case 'error':
									zebra_corner('an error occured');
									break;
								case 'success':
									zebra_corner('image deleted successfully');
									// delete containing div from current page
									div_wrapper.fadeOut(1000, function(){$(this).remove();});
									break;
								default:
							}
						},
						error: function() {
							zebra_corner('an error occured');
						},
						dataType : 'json'
					});
				}
			}
		});
	});	

});
</script>
