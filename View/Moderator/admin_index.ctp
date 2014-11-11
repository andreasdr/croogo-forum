<?php
$this->viewVars['title_for_layout'] = __d('croogo', 'Moderators');
$this->extend('/Common/admin_index');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('croogo', 'Moderators'), array('action' => 'index'));

?>

<div class="Moderators index">
	<table class="table table-striped">
	<tr>
		<th><?php echo $this->Paginator->sort('id'); ?></th>
		<th><?php echo $this->Paginator->sort('forum_id'); ?></th>
		<th><?php echo $this->Paginator->sort('user_id'); ?></th>
		<th><?php echo $this->Paginator->sort('created'); ?></th>
		<th><?php echo $this->Paginator->sort('modified'); ?></th>
		<th class="actions"><?php echo __d('croogo', 'Actions'); ?></th>
	</tr>
	<?php foreach ($moderators as $moderator): ?>
	<tr>
		<td><?php echo h($moderator['Moderator']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($moderator['Forum']['title'], array('controller' => 'forum', 'action' => 'view', $moderator['Forum']['id'])); ?>
		</td>
		<td>
			<?php echo $moderator['User']['name']; ?>
		</td>
		<td><?php echo h($moderator['Moderator']['created']); ?>&nbsp;</td>
		<td><?php echo h($moderator['Moderator']['modified']); ?>&nbsp;</td>
		<td class="item-actions">
			<?php echo $this->Croogo->adminRowAction('', array('action' => 'view', $moderator['Moderator']['id']), array('icon' => 'eye-open')); ?>
			<?php echo $this->Croogo->adminRowAction('', array('action' => 'edit', $moderator['Moderator']['id']), array('icon' => 'pencil')); ?>
			<?php echo $this->Croogo->adminRowAction('', array('action' => 'delete', $moderator['Moderator']['id']), array('icon' => 'trash', 'escape' => true), __d('croogo', 'Are you sure you want to delete # %s?', $moderator['Moderator']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
</div>
