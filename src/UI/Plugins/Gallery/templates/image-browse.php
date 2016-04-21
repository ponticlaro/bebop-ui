<?php use Ponticlaro\Bebop\UI\Plugins\Media\Media; ?>

<div bebop-ui--gallery-item-type="image" bebop-ui--gallery-item-view="browse">
	
	<div bebop-ui--gallery-item-widget>
		<?php (new Media('ID', '{{id}}'))->render(); ?>
	</div>
    <div bebop-ui--gallery-item-meta>
		{{#title}}
        	<div bebop-ui--gallery-item-title>{{title}}</div>
        {{/title}}
    </div>
    
</div>