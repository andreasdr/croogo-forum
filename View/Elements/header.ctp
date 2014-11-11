<?php 

echo $this->Html->css('Forum.forum_style');
echo $this->Html->script('Forum.forum');

?>
<div class="skeleton">
	<div class="head">
		<?php echo $this->element('navigation'); ?>
	</div>
<?php

$this->Breadcrumb->prepend (
	__d ( 'forum', 'Forum' ),
	array (
		'plugin' => 'forum',
		'controller' => 'forum',
		'action' => 'index'
	)
);
$this->Breadcrumb->prepend ($settings ['name'], '/');

echo $this->element ('breadcrumbs');

echo $this->Session->flash ();

?>