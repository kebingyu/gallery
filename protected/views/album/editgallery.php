<div id="album-name">
	<h2 class="name">You can edit your albums here</h2>
	<a href="/album/showgallery/private"><span class="">[back to view personal gallery]</span></a>
</div>
<div id="album-wrapper" class="clearfix">
</div>
<script>
$(document).ready(function() {
	// retrieve image/album info
	$.ajax({
		type : 'GET',
		url : '/album/getimage/private', 
		success : function(data) {
			var total = 0;
			var list = data.list;
			if (data.count.length == 0 && data.list.length == 0) {
				zebra_corner('your gallery is empty');
			}
			for (var i = 0, j = data.count.length; i < j; ++i) {
				var q = parseInt(data.count[i].image_count);
				var text_append = '<div class="panel-wrapper"><span class="panel-medium"><img src="/services/image/mediums/' + data.image[total+q-1].image_name + '" class="" /></span><span class="panel-edit" rel="' + data.count[i].album_id + '"><div class="name"><span>Name:&nbsp;</span><span class="editable">' + data.count[i].album_name + '</span></div><div class="description"><span>Description:&nbsp;</span><span class="editable">' + data.count[i].album_desc + '</span></div><div class="span"><div class="btn-pub-wrapper">';
				if (data.count[i].is_public == 1) {
					text_append += '<button class="btn btn-warning btn-pub" rel="' + data.count[i].album_id + '"><span class="icon-ban-circle icon-white"></span><span>Set as personal</span></button>';
					text_append += '<button class="btn btn-success btn-pub hidden" rel="' + data.count[i].album_id + '"><span class="icon-plus icon-white"></span><span>Set as public</span></button>';
				} else {
					text_append += '<button class="btn btn-warning btn-pub hidden" rel="' + data.count[i].album_id + '"><span class="icon-ban-circle icon-white"></span><span>Set as personal</span></button>';
					text_append += '<button class="btn btn-success btn-pub" rel="' + data.count[i].album_id + '"><span class="icon-plus icon-white"></span><span>Set as public</span></button>';
				}
				text_append += '</div><button class="btn btn-danger delete" rel="' + data.count[i].album_id + '" empty="0"><span class="icon-trash icon-white"></span><span>Delete</span></button>';
				text_append += '</div></span></div>';
				$('#album-wrapper').append(text_append);
				total += q;
				for (var m = 0; m < list.length; ++m) {
					if (data.count[i].album_name == list[m].album_name) {
						list.splice(m, 1);
					}
				}
			}
			// append empty album if any
			var new_i = data.count.length;
			for (var i = new_i, j = new_i + list.length; i < j; ++i) {
				text_append = '<div class="panel-wrapper"><span class="panel-medium"><img src="/images/empty-album.jpg" class="" /></span><span class="panel-edit" rel="' + list[i - new_i].album_id + '"><div class="name"><span>Name:&nbsp;</span><span class="editable">' + list[i - new_i].album_name + '</span></div><div class="description"><span>Description:&nbsp;</span><span class="editable">' + list[i - new_i].album_desc + '</span></div><div class="span"><div class="btn-pub-wrapper">';
				if (data.list[i - new_i].is_public == 1) {
					text_append += '<button class="btn btn-warning btn-pub" rel="' + list[i - new_i].album_id + '"><span class="icon-ban-circle icon-white"></span><span>Set as personal</span></button>';
					text_append += '<button class="btn btn-success btn-pub hidden" rel="' + list[i - new_i].album_id + '"><span class="icon-plus icon-white"></span><span>Set as public</span></button>';
				} else {
					text_append += '<button class="btn btn-warning btn-pub hidden" rel="' + list[i - new_i].album_id + '"><span class="icon-ban-circle icon-white"></span><span>Set as personal</span></button>';
					text_append += '<button class="btn btn-success btn-pub" rel="' + list[i - new_i].album_id + '"><span class="icon-plus icon-white"></span><span>Set as public</span></button>';
				}
				text_append += '</div><button class="btn btn-danger delete" rel="' + list[i - new_i].album_id + '" empty="1"><span class="icon-trash icon-white"></span><span>Delete</span></button>';
				text_append += '</div></span></div>';
				$('#album-wrapper').append(text_append);
			}
			// inline edit
			$('.editable').inlineEdit({
				save: function(e, dataEdit) {
					var id = $(this).closest('span.panel-edit').attr('rel');
					var col = $(this).closest('div').attr('class');
					$.ajax({
						type : 'POST',
						url : '/album/update/',
						data : {
							albumID : id,
							albumCol : col,
							albumValue : dataEdit.value
						}, 
						success : function(query) {
							switch (query.stat) {
                                case 'fail':
                                    zebra_corner('album info update failed');
                                    break;
                                case 'error':
                                    zebra_corner('an error occured');
                                    break;
                                case 'success':
                                    zebra_corner('album name updated successfully');
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
			});

			// enable album delete
			$('button.delete').click(function() {
				var album_id = $(this).attr('rel');
				var is_empty = $(this).attr('empty');
				var div_wrapper = $(this).closest('div.panel-wrapper');
				var image_name = new Array();
				for (var i = 0, j = data.image.length; i < j; ++i) {
					if (data.image[i].album_id == album_id) {
						image_name.push(data.image[i].image_name);
					} 
				}
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
								div_wrapper.fadeOut(1000, function(){$(this).remove();});
								break;
							default:
                		}
					},
					error : function(){
						zebra_corner('an error occured');
					},
					dataType : 'json'
				});
			});
		}, // end of ajax get success
		error : function() {
			zebra_corner('an error occured');
		},
		dataType : 'json'
	});

	// register album toggle public/private event
	$('body').on('click', 'button.btn-pub', function() {
		var btn = $(this);
		var is_public = btn.hasClass('btn-success') ? 1 : 0;
		var text_pub = is_public ? 'public' : 'personal';
		$.ajax({
			type : 'POST',
			url : '/album/update',
			data : {
				albumID : $(this).attr('rel'),
				albumCol : 'is_public',
				albumValue : is_public
			},
			success : function(query) {
				switch (query.stat) {
					case 'fail':
						zebra_corner('album accessibility change failed');
						break;
					case 'error':
						zebra_corner('an error occured');
						break;
					case 'success':
						btn.toggleClass('hidden').siblings('button.btn-pub').toggleClass('hidden');
						zebra_corner('This album becomes ' + text_pub);
						break;
					default:
				}
			},
			error : function() {
				zebra_corner('an error occured');
			},
			dataType : 'json'
		});
	});

});
</script>
