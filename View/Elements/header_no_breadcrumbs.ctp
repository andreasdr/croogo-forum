<?php 

echo $this->Html->css('Forum.forum_style');
echo $this->Html->script('Forum.forum');

?>
<div class="skeleton">
	<div class="head">
		<?php echo $this->element('navigation'); ?>
	</div>
<?php

echo $this->Session->flash ();

?>