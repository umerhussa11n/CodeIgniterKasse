<form action="<?php echo base_url('access/comment_save'); ?>" method="post" id="access_comment_add">

<!--<ul class="list-group" id="comment_list">-->
<?php if($comments){ ?>
 <div class="panel panel-default">
<div class="panel-body body-panel">
<?php } ?>
<ul class="chat" id="comment_list">
  <?php if($comments){ ?>

  <?php foreach($comments as $comment){ ?>

	<li class="left clearfix">
		<div class="chat-body clearfix">
			<div class="header">
				<strong class="primary-font"><?php echo $comment->user_name; ?></strong> <small class="pull-right text-muted">
					<span class="glyphicon glyphicon-time"></span><?php echo date("d M Y H:i A",strtotime($comment->create_date)); ?></small>
			</div>
			<?php if($comment->comment){ ?>
			<p>
				<?php echo $comment->comment; ?>
			</p>
			<?php } ?>
			<?php if($comment->image){ ?>
			<p>
				<a href="<?php echo base_url('uploads/comments/'.$comment->image); ?>" class="popup">
				<img src="<?php echo base_url('uploads/comments/thumbs/'.$comment->image); ?>" style="width: 100px;border: 1px solid #ccc; padding: 3px;margin-top: 5px;" />
				</a>
			</p>
			<?php } ?>
		</div>
	</li>
  <?php } ?>

  <?php } ?>
  </ul>
  <?php if($comments){ ?>
</div></div>
  <?php } ?>

<label>Kommentar</label><br />
<textarea class="form-control" name="comment" id="comment" rows="3"></textarea>
<div class="order_comment_image_container" style="width:400px; float: left;">
<div id="order_comment_image_upload" class="fileuploader" style="float: left">+ Add Image</div>
<div id="order_comment_image_upload_response"></div>
</div>
<input type="submit" class="btn btn-success pull-right" style="margin-top: 10px;" value="Tilføj kommentar" />

<input type="hidden" name="id" value="<?php echo $id; ?>" />
<div class="clearfix"></div>
</form>
<style>
.chat
{
    list-style: none;
    margin: 0;
    padding: 0;
}

.chat li
{
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px dotted #B3A9A9;
}

.chat li.left .chat-body
{
    /* margin-left: 60px; */
}

.chat li.right .chat-body
{
    margin-right: 60px;
}


.chat li .chat-body p
{
    margin: 0;
    color: #777777;
}

.panel .slidedown .glyphicon, .chat .glyphicon
{
    margin-right: 5px;
}

.body-panel
{
    overflow-y: scroll;
    max-height: 300px;
}

::-webkit-scrollbar-track
{

    background-color: #F5F5F5;
}

::-webkit-scrollbar
{
    width: 12px;
    background-color: #F5F5F5;
}

::-webkit-scrollbar-thumb
{

    background-color: #555;
}

.ajax-upload-dragdrop{
	border: 0px;
}
.ajax-file-upload-container{
	float: left;
}
.ajax-upload-dragdrop{
	border: 0px !important;
	padding: 0px !important;
	margin-top: 10px;
}
.ajax-file-upload{
	height: 30px !important;
}
</style>



<script>
$('.popup').magnificPopup({type:'image'});

$("#access_comment_add").on("submit",function(e){
  e.preventDefault();
  $.ajax({
    url: "<?php echo base_url('access/comment_save'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result){
      $("#comment_list").append(result);
    },
    complete: function(){
      $("#comment").val("");
	  $("#order_comment_image_upload_response").html("");
	  $('.popup').magnificPopup({type:'image'});
    }
  });
});


$("#order_comment_image_upload").uploadFile({
	url: "<?php echo base_url('access/comment_image_temp_upload'); ?>",
	fileName: "order_comment_image",
	multiple: false,
	maxFileSize: 15 * 1024 * 1024,
	showStatusAfterSuccess: false,
	allowedTypes: "jpg,jpeg,png,gif",
	onSuccess: function (files, data, xhr) {
		var data = JSON.parse(data);

		if (data.error) {
			alert(data.error);
		} else if (data) {
			var file = data.upload_data.file_name;
			var content = "<strong>File Attached</strong> <a data-file='"+file+"' class='order_comment_image_remove' href='#'>[remove]</a><input type='hidden' name='image' value='"+file+"' />";
			$("#order_comment_image_upload_response").html(content);
		}
	},
	uploadStr: "+ Add Image"

});

$(document).on("click",".order_comment_image_remove",function(e){
	e.preventDefault();
	var file = $(this).data('file');
	  $.ajax({
		url: "<?php echo base_url('access/comment_image_temp_remove'); ?>",
		type: "post",
		data: {file: file},
		success: function(result){

		},
		complete: function(){
		  $("#order_comment_image_upload_response").html("");
		}
	  });

});

</script>
