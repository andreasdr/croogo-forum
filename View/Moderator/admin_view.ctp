<?php
$this->viewVars['title_for_layout'] = sprintf('%s: %s', __d('croogo', 'Moderators'), h($moderator['User']['name']));

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('croogo', 'Moderators'), array('action' => 'index'))
	->addCrumb($moderator['User']['name'], '/' . $this->request->url);

?>
<h2 class="hidden-desktop"><?php echo __d('croogo', 'Moderator'); ?></h2>

<div class="row-fluid">
	<div class="span12 actions">
		<ul class="nav-buttons">
		<li><?php echo $this->Html->link(__d('croogo', 'Edit Moderator'), array('action' => 'edit', $moderator['Moderator']['id']), array('button' => 'default')); ?> </li>
		<li><?php echo $this->Form->postLink(__d('croogo', 'Delete Moderator'), array('action' => 'delete', $moderator['Moderator']['id']), array('button' => 'danger', 'escape' => true), __d('croogo', 'Are you sure you want to delete # %s?', $moderator['Moderator']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__d('croogo', 'List Moderators'), array('action' => 'index'), array('button' => 'default')); ?> </li>
		<li><?php echo $this->Html->link(__d('croogo', 'New Moderator'), array('action' => 'add'), array('button' => 'success')); ?> </li>
		</ul>
	</div>
</div>

<div class="moderators view">
	<dl class="inline">
		<dt><?php echo __d('croogo', 'Id'); ?></dt>
		<dd>
			<?php echo h($moderator['Moderator']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __d('croogo', 'Forum'); ?></dt>
		<dd>
			<?php echo $this->Html->link($moderator['Forum']['title'], array('controller' => 'forums', 'action' => 'view', $moderator['Forum']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __d('croogo', 'User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($moderator['User']['name'], array('controller' => 'users', 'action' => 'view', $moderator['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __d('croogo', 'Created'); ?></dt>
		<dd>
			<?php echo h($moderator['Moderator']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __d('croogo', 'Modified'); ?></dt>
		<dd>
			<?php echo h($moderator['Moderator']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
