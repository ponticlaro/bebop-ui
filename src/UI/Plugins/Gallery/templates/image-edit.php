<?php use Ponticlaro\Bebop\UI\Plugins\Media\Media; ?>

<div bebop-ui--gallery-item-type="image" bebop-ui--gallery-item-view="edit">

	<div class="bebop-ui--gallery-media-type-label">
		{{type}}
	</div>
	<input type="hidden" name="type" value="{{type}}">
	<br>
	
	<?php (new Media('Image ID', '{{image_id}}'))->render(); ?>

	<label for="">Title</label><br>
	<input type="text" name="title" value="{{title}}">
	<br><br>

	<label for="">Caption</label><br>
	<textarea name="caption">{{caption}}</textarea>
	<br><br>

	<label for="">Photo Credit</label><br>
	<input type="text" name="photo_credit" value="{{photo_credit}}">

</div>