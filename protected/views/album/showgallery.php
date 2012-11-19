<div id="album-name">
	<?php echo '<h2 class="name">'.$subtitle.'</h2>';?>
</div>
<div id="album-wrapper" class="clearfix"></div>

<script>
$(document).ready(function() {
	var pageCount = 0;
	var sCate = '<?php echo $cate ?>';
	// retrieve image/album info
	$.ajax({
		type : 'GET',
		url : '/album/getimage/' + sCate, 
		success : function(data) {
			if (data.count.length == 0 && data.list.length == 0) {
				zebra_center('The gallery is empty');
			}
			var total = 0;
			var list = data.list;
			var text_append = '';
			/* not empty album begin */
			for (var i = 0, j = data.count.length; i < j; ++i) {
				var q = parseInt(data.count[i].image_count);
				text_append += '<div class="panel-wrapper">';
				// show delete and lock/unlock icon when private
				if (sCate === 'private') {
					text_append += '<div class="column1 panel-icon">';
					text_append += '<div class="icon-trash icon-large" title="Delete" rel="' + data.count[i].album_id + '" empty="0"></div>';
					text_append += '<div class="icon-pub-wrapper" data-id="' + data.count[i].album_id + '" data-name="' + data.count[i].album_name + '" data-desc="' + data.count[i].album_desc + '">';
					text_append += data.count[i].is_public == 1 ? 
						'<div class="icon-pub icon-large icon-unlock" title="Public album"></div><div class="icon-pub icon-large icon-lock hidden" title="Private album"></div>' 
						: '<div class="icon-pub icon-large icon-unlock hidden" title="Public album"></div><div class="icon-pub icon-large icon-lock" title="Private album"></div>';
					text_append += '</div>';
					text_append += '</div>';
				} else {
				// show author when public
					text_append += '<div class="column1 panel-icon">';
					text_append += '<div class="icon-book-open icon-large" title="Upload by ' + data.count[i].author + '" empty="0"></div>';
					text_append += '</div>';
				}
				// image thumbnail
				text_append += '<div class="column2 panel-medium">';
				text_append += '<a href="/album/showalbum/' + data.count[i].album_id + '?cate=' + sCate + '" title="View this album">';
				text_append += '<img src="/services/image/mediums/' + data.image[total + q - 1].image_name + '" class="" /></a>';
				text_append += '</div>';
				// image info
				text_append += '<div class="column3 panel-edit" rel="' + data.count[i].album_id + '">';
				// show edit icon when private
				if (sCate === 'private') {
					text_append += '<div class="icon-pencil icon-large" title="Edit" rel="' + data.count[i].album_id + '"></div>';
				}
				// album name
				text_append += '<div class="name">' + data.count[i].album_name + '</div>';
				// album description
				text_append += '<div class="description">' + data.count[i].album_desc + '</div>';
				text_append += '</div>';
				text_append += '</div>';
				total += q;
				// get the empty album list
				for (var m = 0; m < list.length; ++m) {
					if (data.count[i].album_id == list[m].album_id) {
						list.splice(m, 1);
					}
				}
			}
			/* not empty album end */

			/* empty album begin: empty public albums are not shown */
			var new_i = data.count.length;
			if (sCate === 'private') {
				for (var i = new_i, j = new_i + list.length; i < j; ++i) {
					text_append += '<div class="panel-wrapper">';
					text_append += '<div class="column1 panel-icon">';
					text_append += '<div class="icon-trash icon-large" title="Delete" rel="' + list[i - new_i].album_id + '" empty="1"></div>';
					text_append += '<div class="icon-pub-wrapper" data-id="' + list[i - new_i].album_id + '" data-name="' + list[i - new_i].album_name + '" data-desc="' + list[i - new_i].album_desc + '">';
					text_append += list[i - new_i].is_public == 1 ? 
						'<div class="icon-pub icon-large icon-unlock" title="Public album"></div><div class="icon-pub icon-large icon-lock hidden" title="Private album"></div>' 
						: '<div class="icon-pub icon-large icon-unlock hidden" title="Public album"></div><div class="icon-pub icon-large icon-lock " title="Private album"></div>';
					text_append += '</div>';
					text_append += '</div>';
					text_append += '<div class="column2 panel-medium">';
					text_append += '<a href="#" title="This album is empty">';
					text_append += '<img src="/images/empty-album.jpg" class="" /></a>';
					text_append += '</div>';
					text_append += '<div class="column3 panel-edit" rel="' + list[i - new_i].album_id + '">';
					text_append += '<div class="icon-pencil icon-large" title="Edit" rel="' + list[i - new_i].album_id + '"></div>';
					text_append += '<div class="name">' + list[i - new_i].album_name + '</div>';
					text_append += '<div class="description">' + list[i - new_i].album_desc + '</div>';
					text_append += '</div>';
					text_append += '</div>';
				}
				/* empty album end */
			}
			$('#album-wrapper').append(text_append);

			// enable album delete
            $('.icon-trash').click(function() {
                var album_id = $(this).attr('rel');
                var is_empty = $(this).attr('empty');
                var div_wrapper = $(this).closest('div.panel-wrapper');
                var image_name = new Array();
                for (var i = 0, j = data.image.length; i < j; ++i) {
                    if (data.image[i].album_id == album_id) {
                        image_name.push(data.image[i].image_name);
                    }
                }
				$.Zebra_Dialog('Are you sure you want to perminantly DELETE this album? ALL the photos and comments will be DELETED as well!', {
					'type': 'question',
					'title': 'Delete Confirmation',
					'buttons':  ['Yes', 'No'],
					'onClose': function(caption) {
						if (caption === 'Yes') {
							// delete album record from database
							$.ajax({
								type : 'POST',
								url : '/album/delete/',
								data : {
									albumID : album_id,
									albumEmpty : is_empty
								},
								success : function(query) {
									switch (query.stat) {
										case 'fail':
											zebra_corner('delete album failed');
											break;
										case 'error':
											zebra_corner('an error occured');
											break;
										case 'success':
											// then delete images belong to this album from upload folder
											for (var p = 0, q = image_name.length; p < q; ++p) {
												$.ajax({
													type : 'POST',
													url : '/services/upload/delete/',
													data : {
														imageName : image_name[p]
													},
													success : function(query) {
														if (query.stat !== 'success') {
															zebra_corner('an error occured');
															p = image_name.length;
														}
													},
													error : function(){
														zebra_corner('an error occured');
													},
													dataType : 'json'
												});
											}
											zebra_corner('album deleted successfully');
											// delete containing div from current page
											div_wrapper.fadeOut(1000, function() {$(this).remove();});
											break;
										default:
									}
								},
								error : function(){
									zebra_corner('an error occured');
								},
								dataType : 'json'
							});
						}
					}
				});
			});
		},
		error : function() {
			zebra('an error occured');
		},
		dataType : 'json'
	});

	// enable album public/private toggle
	$('body').on('click', '.icon-pub-wrapper', function() {
		var elem = $(this);
		$.ajax({
			type : 'POST',
			url : '/album/update',
			data : {
				album_id : elem.attr('data-id'),
				album_name : elem.attr('data-name'),
				album_desc : elem.attr('data-desc'),
				is_public : elem.children('icon-unlock').hasClass('hidden')
			},
			success : function(data) {
				if (data.stat == 'success') {
					elem.children().toggle();
					zebra_corner('album updated successfully');
				} else {
					zebra_corner('an error occured');
				}
			},
			error : function() {
				zebra_corner('an error occured');
			},
			dataType : 'json'
		});
	});

	// register pencil icon hover and click
	$('body').on('hover', '.panel-edit', function() {                         
        $(this).children('.icon-pencil').fadeToggle();
	}).on('click', '.icon-pencil', function() {
		window.location.href = '/album/editalbum/' + $(this).attr('rel');
	});                                                            

});
</script>
