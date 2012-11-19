<div class="container">
    <div class="page-header">
        <h1>Upload your photos in two steps</h1>
    </div>
    <!-- The file upload form used as target for the file upload widget -->
    <form id="fileupload" action="/upload/upload" method="POST" enctype="multipart/form-data">
		<div class="album-select">
			<p><strong>Step 1:</strong> Select from the existing albums or <a href="#tab_create">creat a new album</a> to upload photos. You can select multiple albums.</p> 
			<select class="chzn-select" data-placeholder="Choose an album" name="saveto_album_name">
				<?php
				foreach ($arrAlbumList as $objAlbum) {
					echo '<option value="'.$objAlbum->name.'" data-id="'.$objAlbum->id
						.'" data-public="'.$objAlbum->is_public.'">'.$objAlbum->name."</option>\n";
				}
				?>
			</select>
			<input type="hidden" class="select-album-pub" name="saveto_album_is_public" value="">
			<input type="hidden" class="select-album-id" name="saveto_album_id" value="">
		</div>
		<p><strong>Step 2:</strong> Click 'Add files' to select multiple photos or just drag &amp; drop photos from your computer onto this page to start uploading photos. When you finish uploading, you can go to your <a href="/album/showgallery?cate=private">personal gallery</a> or check out photos uploaed by others in the <a href="/album/showgallery?cate=public/">public gallery.</a></p>
		<ul class="mytabs">
			<li class="active"><a href="#tab_upload">Upload Image</a></li>
			<li><a href="#tab_create">Create New Album</a></li>
		</ul>
		<div id="tab_upload">
			<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
			<div class="row fileupload-buttonbar">
				<div class="span7">
					<!-- The fileinput-button span is used to style the file input field as button -->
					<span class="btn btn-success fileinput-button">
						<i class="icon-plus icon-white"></i>
						<span>Add files</span>
						<input type="file" name="files[]" multiple>
					</span>
					<button type="submit" class="btn btn-primary start">
						<i class="icon-upload icon-white"></i>
						<span>Start upload</span>
					</button>
					<button type="reset" class="btn btn-warning cancel">
						<i class="icon-ban-circle icon-white"></i>
						<span>Cancel upload</span>
					</button>
					<button type="button" class="btn btn-danger delete">
						<i class="icon-trash icon-white"></i>
						<span>Delete</span>
					</button>
					<input type="checkbox" class="toggle">
				</div>
				<!-- The global progress information -->
				<div class="span5 fileupload-progress fade">
					<!-- The global progress bar -->
					<div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
						<div class="bar" style="width:0%;"></div>
					</div>
					<!-- The extended global progress information -->
					<div class="progress-extended">&nbsp;</div>
				</div>
			</div>
			<!-- The loading indicator is shown during file processing -->
			<div class="fileupload-loading"></div>
			<br>
			<!-- The table listing the files available for upload/download -->
			<table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
		</div>
    </form>
	<!-- Create new album tab -->
	<div id="tab_create">
		<div>
			<?php $form_password = $this->beginWidget('CActiveForm', array(
				'id' => 'create_album_form',
				'action' => 'javascript:void(0)',
				'htmlOptions' => array(
					'class' => 'fg_form',
					'data-title' => 'Create a new album',
				),
			));?>
				<div class="album_input">
					<table>
					<tr>
					<td class="right"><?php echo $form_album->labelEx($album, 'name');?></td>
					<td><?php echo $form_album->textField($album, 'name', array(
						'class' => 'input_text', 
						'size' => '30',
						'title' => 'Give your album a name',
					));?></td>
					</tr>
					<tr>
					<td class="right"><?php echo $form_album->labelEx($album, 'description');?></td>
					<td><?php echo $form_album->textArea($album, 'description', array(
						'class' => 'input_text double',
						'size' => '30',
						'title' => 'Write something about this album',
					));?></td>
					</tr>
					<tr>
					<td class="right"><?php echo $form_album->checkBox($album, 'is_public', array(
						'name' => 'make-pub',
						'value' => 'on',
						'checked' => 'checked',
						'title' => 'Public or personal album?',
					));?></td>
					<td><?php echo CHtml::submitButton('Create', array(
							'id' => 'btn_create_album', 
							'name' => 'submit',
						));?>
						<?php echo CHtml::resetButton();?>
					</td>
					</tr>
					</table>
				</div>
				<div id="cg_error"></div>
			<?php $this->endWidget(); ?>
		</div>
	</div>
    <br />
    <div class="well">
        <h3>Notes</h3>
        <ul>
            <li>The maximum file size for uploads is <strong>5 MB</strong>.</li>
            <li>Only image files (<strong>JPG, GIF, PNG</strong>) are allowed.</li>
            <li>You can <strong>drag &amp; drop</strong> files from your desktop on this webpage with Google Chrome, Mozilla Firefox and Apple Safari.</li>
        </ul>
    </div>
<!-- modal-gallery is the modal dialog used for the image gallery -->
<div id="modal-gallery" class="modal modal-gallery hide fade" data-filter=":odd">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h3 class="modal-title"></h3>
    </div>
    <div class="modal-body"><div class="modal-image"></div></div>
    <div class="modal-footer">
        <a class="btn modal-download" target="_blank">
            <i class="icon-download"></i>
            <span>Download</span>
        </a>
        <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000">
            <i class="icon-play icon-white"></i>
            <span>Slideshow</span>
        </a>
        <a class="btn btn-info modal-prev">
            <i class="icon-arrow-left icon-white"></i>
            <span>Previous</span>
        </a>
        <a class="btn btn-primary modal-next">
            <span>Next</span>
            <i class="icon-arrow-right icon-white"></i>
        </a>
    </div>
</div>
</div>
<!-- The template to display files available for upload -->
        <!--<td class="name"><span>{%=file.name%}</span></td>-->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td class="preview"><span class="fade"></span></td>
        <td class="name upload_name_cell"><input class="upload_name" name="upload_name[]" type="text" value="{%=file.name%}" /></td>
        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        {% if (file.error) { %}
            <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
            <td>
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
            </td>
            <td class="start">{% if (!o.options.autoUpload) { %}
                <button class="btn btn-primary">
                    <i class="icon-upload icon-white"></i>
                    <span>{%=locale.fileupload.start%}</span>
                </button>
            {% } %}</td>
        {% } else { %}
            <td colspan="2"></td>
        {% } %}
        <td class="cancel">{% if (!i) { %}
            <button class="btn btn-warning">
                <i class="icon-ban-circle icon-white"></i>
                <span>{%=locale.fileupload.cancel%}</span>
            </button>
        {% } %}</td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        {% if (file.error) { %}
            <td></td>
            <td class="name"><span>{%=file.desc%}</span></td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else { %}
            <td class="preview">{% if (file.thumbnail_url) { %}
                <a href="{%=file.url%}" title="{%=file.desc%}" rel="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
            {% } %}</td>
            <td class="name">
                <a href="{%=file.url%}" title="{%=file.desc%}" rel="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.desc%}</a>
            </td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td colspan="2"></td>
        {% } %}
        <td class="delete">
            <button class="btn btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}">
                <i class="icon-trash icon-white"></i>
                <span>{%=locale.fileupload.destroy%}</span>
            </button>
            <input type="checkbox" name="delete" value="1">
        </td>
    </tr>
{% } %}
</script>
<script src="/js/upload/plugin.js" type="text/javascript"></script>
<!-- Bootstrap JS and Bootstrap Image Gallery are not required, but included for the demo -->
<script src="/js/upload/bootstrap.min.js" type="text/javascript"></script>
<script src="/js/upload/bootstrap-image-gallery.min.js" type="text/javascript"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="/js/upload/jquery.iframe-transport.js" type="text/javascript"></script>
<!-- The basic File Upload plugin -->
<script src="/js/upload/jquery.fileupload.js" type="text/javascript"></script>
<!-- The File Upload file processing plugin -->
<script src="/js/upload/jquery.fileupload-fp.js" type="text/javascript"></script>
<!-- The File Upload user interface plugin -->
<script src="/js/upload/jquery.fileupload-ui.js" type="text/javascript"></script>
<!-- The localization script -->
<script src="/js/upload/locale.js" type="text/javascript"></script>
<!-- The main application script -->
<script src="/js/upload/main.js" type="text/javascript"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE8+ -->
<!--[if gte IE 8]><script src="js/upload/jquery.xdr-transport.js"></script><![endif]-->
<script>
function updateHiddenInfo() {
	$('.select-album-pub').attr('value', $('.chzn-select > option').filter(':selected').attr('data-public'));
	$('.select-album-id').attr('value', $('.chzn-select > option').filter(':selected').attr('data-id'));
}

function createAlbum() {
	$.ajax({
		type : 'POST',
		url : '/album/add', 
		data : $('#create_album_form').serialize(), 
		success : function(data) {
			if (data.success == 1) {
				zebra_corner('new album ' + data.name + ' created successfully', 4000);
				$('#cg_error').html('');
				$('#create_album_form').find(':input').each(function() {
					switch (this.type) {
						case 'textarea':
						case 'text':
							$(this).val('');
							break;
					}
				});
				$('.chzn-select').append('<option value="' + data.name + '" selected="selected" data-id="' + data.id + '" data-public="' + data.is_public + '">' + data.name + '</option>');
				$('.chzn-select').trigger("liszt:updated");
				updateHiddenInfo();
				window.location.href = '/upload/index#tab_upload';
			} else {
				var html = "";
				if (data.error) {
					for (var i in data.error) {
						html += data.error[i] + '<br/>';
					}
				} else {
					zebra_corner('an error occured');
				}
				$('#cg_error').html(html);
			}
    	}, 
		dataType : 'json'
	});
    return false;
}

$(document).ready(function() {
	/* Chosen jquery plugin active */
    $('.chzn-select').chosen();
	updateHiddenInfo();
	$('.chzn-select').chosen().change(function() {
		updateHiddenInfo();
	});

    /* Tabify jquery plugin active */
    $('.mytabs').tabify();

    // create album form    
    $('#btn_create_album').click(function() {
        createAlbum(); 
        return false;
    });                 
                        
    $('#create_album_form').keypress(function (e) {
        if (e.which == 13) {
            createAlbum();
            return false;
        }
    });

	//$('#create_album_form').formly();
	$('#create_album_form').formly({'theme':'Dark'}, function(e) { 
		$('.callback').html(e); 
	});

});     
        
</script>
