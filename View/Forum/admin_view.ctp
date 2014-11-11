<?php
$this->viewVars['title_for_layout'] = sprintf('%s: %s', __d('croogo', 'Forums'), h($forum['Forum']['title']));

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('croogo', 'Forums'), array('action' => 'index'));

?>
<h2 class="hidden-desktop"><?php echo __d('croogo', 'Forum'); ?></h2>

<div class="row-fluid">
	<div class="span12 actions">
		<ul class="nav-buttons">
		<li><?php echo $this->Html->link(__d('croogo', 'Edit Forum'), array('action' => 'edit', $forum['Forum']['id']), array('button' => 'default')); ?> </li>
		<li><?php echo $this->Form->postLink(__d('croogo', 'Delete Forum'), array('action' => 'delete', $forum['Forum']['id']), array('button' => 'danger', 'escape' => true), __d('croogo', 'Are you sure you want to delete # %s?', $forum['Forum']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__d('croogo', 'List Forums'), array('action' => 'index'), array('button' => 'default')); ?> </li>
		</ul>
	</div>
</div>

<div class="forums view">
	<dl class="inline">
		<dt><?php echo __d('croogo', 'Id'); ?></dt>
		<dd>
			<?php echo h($forum['Forum']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __d('croogo', 'Parent'); ?></dt>
		<dd>
			<?php echo $this->Html->link($forum['Parent']['title'], array('controller' => 'forum', 'action' => 'view', $forum['Parent']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __d('croogo', 'Title'); ?></dt>
		<dd>
			<?php echo h($forum['Forum']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __d('croogo', 'Slug'); ?></dt>
		<dd>
			<?php echo h($forum['Forum']['slug']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __d('croogo', 'Description'); ?></dt>
		<dd>
			<?php echo h($forum['Forum']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __d('croogo', 'Icon'); ?></dt>
		<dd>
			<?php echo h($forum['Forum']['icon']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __d('croogo', 'Status'); ?></dt>
		<dd>
			<?php echo $this->Html->status($forum['Forum']['status']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
