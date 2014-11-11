<?php
$this->viewVars['title_for_layout'] = __d('croogo', 'Forums');
$this->extend('/Common/admin_index');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('croogo', 'Forums'), array('action' => 'index'));

?>

<div class="forums index">
	<table class="table table-striped">
	<tr>
		<th><?php echo $this->Paginator->sort('id'); ?></th>
		<th><?php echo $this->Paginator->sort('parent_id'); ?></th>
		<th><?php echo $this->Paginator->sort('title'); ?></th>
		<th><?php echo $this->Paginator->sort('slug'); ?></th>
		<th><?php echo $this->Paginator->sort('description'); ?></th>
		<th><?php echo $this->Paginator->sort('icon'); ?></th>
		<th><?php echo $this->Paginator->sort('status'); ?></th>
		<th><?php echo $this->Paginator->sort('topic_count'); ?></th>
		<th><?php echo $this->Paginator->sort('post_count'); ?></th>
		<th><?php echo $this->Paginator->sort('created'); ?></th>
		<th><?php echo $this->Paginator->sort('modified'); ?></th>
		<th class="actions"><?php echo __d('croogo', 'Actions'); ?></th>
	</tr>
	<?php foreach ($forums as $forum): ?>
	<tr>
		<td><?php echo h($forum['Forum']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($forum['Parent']['title'], array('controller' => 'forum', 'action' => 'view', $forum['Parent']['id'])); ?>
		</td>
		<td><?php echo h($forum['Forum']['title']); ?>&nbsp;</td>
		<td><?php echo h($forum['Forum']['slug']); ?>&nbsp;</td>
		<td><?php echo h($forum['Forum']['description']); ?>&nbsp;</td>
		<td><?php echo h($forum['Forum']['icon']); ?>&nbsp;</td>
		<td><?php echo $this->Html->status($forum['Forum']['status']); ?>&nbsp;</td>
		<td><?php echo h($forum['Forum']['topic_count']); ?>&nbsp;</td>
		<td><?php echo h($forum['Forum']['post_count']); ?>&nbsp;</td>
		<td><?php echo h($forum['Forum']['created']); ?>&nbsp;</td>
		<td><?php echo h($forum['Forum']['modified']); ?>&nbsp;</td>
		<td class="item-actions">
			<?php echo $this->Croogo->adminRowAction('', array('action' => 'view', $forum['Forum']['id']), array('icon' => 'eye-open')); ?>
			<?php echo $this->Croogo->adminRowAction('', array('action' => 'edit', $forum['Forum']['id']), array('icon' => 'pencil')); ?>
			<?php echo $this->Croogo->adminRowAction('', array('action' => 'delete', $forum['Forum']['id']), array('icon' => 'trash', 'escape' => true), __d('croogo', 'Are you sure you want to delete # %s?', $forum['Forum']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
</div>
