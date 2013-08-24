<section class="title">
	<!-- We'll use $this->method to switch between portfolio.create & portfolio.edit -->
	<h4><?php echo lang('portfolio:'.$this->method); ?></h4>
</section>

<section class="item">
	<div class="content">
		<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>
		<div class="form_inputs">
			<ul class="fields">
				<li>
					<label for="title">Title</label>
					<div class="input">
						<?php echo form_input("title", set_value("title", $title)); ?>
					</div>
				</li>
				<li>
					<label for="title">Image</label>
					<div class="input">
						<img src="<?php echo base_url().'uploads/default/portfolio/categories/'.$image; ?>" />
						<input type="file" name="userfile" size="20" />
					</div>
				</li>
			</ul>
		</div>

	<div class="buttons">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
	</div>

	<?php echo form_close(); ?>
</div>
</section>



<script>
		//this is the application.js file from the example code//
		jQuery(function () {
			
			
				uploadButton = $('<button/>')
		            //.addClass('btn btn-default')
		            .prop('disabled', true)
		            .attr('class', 'btn blue')
		            .text('Processing...')
		            .on('click', function () {
		                var $this = $(this),
		                    data = $this.data();
		                $this
		                    .off('click')
		                    .text('Abort')
		                    .on('click', function () {
		                        $this.remove();
		                        data.abort();
		                    });
		                data.submit().always(function () {
		                	
		                    //$this.parents().find('.upload-preview-controls').empty().text('Success!');
		                });
		            });
		    //'use strict';
		
		        $('#fileupload').fileupload({
		        url: '<?php echo base_url().'index.php/admin/'.$this->module.'/categories/do_upload'; ?>',
		        dataType: 'json',
		        autoUpload: false,
		        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
		        maxFileSize: 5000000, // 5 MB
		        // Enable image resizing, except for Android and Opera,
		        // which actually support image resizing, but fail to
		        // send Blob objects via XHR requests:
		        disableImageResize: /Android(?!.*Chrome)|Opera/
		            .test(window.navigator.userAgent),
		        previewMaxWidth: 100,
		        previewMaxHeight: 100,
		        previewCrop: true
		    }).on('fileuploadadd', function (e, data) {
		        data.context = $('<div/>').attr('class', 'upload-item-preview').appendTo('#files');
		        $.each(data.files, function (index, file) {
		            var node = $('<p/>');
		            var html = '<div class="upload-item-preview controls">' +
				        '<input id="title" type="text" name="title" placeholder="Enter a description of this image" cols="40" rows="5"/>' +
				        '<div class="filename">Filename: ' + file.name + '</div>' +
				        '</div>';
		            if (!index) {
		            	 node
		                    .append(html)
		                    .append(uploadButton.clone(true).data(data));
		            }
		            node.appendTo(data.context);
		            
		        });
		    }).on('fileuploadprocessalways', function (e, data) {
		        var index = data.index,
		            file = data.files[index],
		            node = $(data.context.children()[index]);
		        if (file.preview) {
		            node
		                .prepend(file.preview);
		        }
		        if (file.error) {
		            node
		                .append('<br>')
		                .append(file.error);
		        }
		        if (index + 1 === data.files.length) {
		            data.context.find('button')
		                .text('Upload')
		                .prop('disabled', !!data.files.error);
		        }
		    }).on('fileuploadprogressall', function (e, data) {
		        var progress = parseInt(data.loaded / data.total * 100, 10);
		        $('#progress .bar').css(
		            'width',
		            progress + '%'
		        );
		    }).on('fileuploadsubmit', function(e, data){
		    	var title = $('#title');
		    	var parent = $('#parent');
		    	var csrf_hash_name = $('#csrf_hash_name');
		    	data.formData = {title: title.val(),
		    					 parent: parent.val(),
		    					 csrf_hash_name: csrf_hash_name.val()};
		    })
		    
		    .on('fileuploaddone', function (e, data) {
				var html = '<div class="upload-item-done">' +
						   '<img src="'+data.result[0].thumbnail_url+'" />' +
						   '<div class="message">' +
						   '<div class="title">'+data.result[0].title+'</div>' +
						   '<div class="alert success uploaded"><strong>'+data.result[0].name+'</strong> uploaded</div>' +
						   '</div> </div>';
		        $('#files').append(html);

		    }).on('fileuploadfail', function (e, data) {
		        $.each(data.result.files, function (index, file) {
		            var error = $('<span/>').text(file.error);
		            $(data.context.children()[index])
		                .append('<br>')
		                .append(error);
		        });
		    }).prop('disabled', !$.support.fileInput)
		        .parent().addClass($.support.fileInput ? undefined : 'disabled');
		    
			
		
		
		    // Open download dialogs via iframes,
		    // to prevent aborting current uploads:
		    jQuery('#fileupload .files a:not([target^=_blank])').on('click', function (e) {
		        e.preventDefault();
		        $('<iframe style="display:none;"></iframe>')
		            .prop('src', this.href)
		            .appendTo('body');
		    });
		
		});
					
		</script>