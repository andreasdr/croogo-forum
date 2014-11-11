<?php
$typeIds = array(0 => 'Other', 1 => 'Violence', 2 => 'Offensive', 3 => 'Hateful', 4 => 'Harmful', 5 => 'Spam', 6 => 'Copyright', 7 => 'Sexual', 8 => 'Harassment');
$this->viewVars['title_for_layout'] = __d('croogo', 'Reports');
$this->extend('/Common/admin_index');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('croogo', 'Reports'), array('action' => 'index'));

?>

<div class="reports index">
	<table class="table table-striped">
	<tr>
		<th><?php echo $this->Paginator->sort('id'); ?></th>
		<th><?php echo $this->Paginator->sort('type'); ?></th>
		<th><?php echo $this->Paginator->sort('topic_id'); ?></th>
		<th><?php echo $this->Paginator->sort('post_id'); ?></th>
		<th><?php echo $this->Paginator->sort('comment'); ?></th>
		<th><?php echo $this->Paginator->sort('reporter_id'); ?></th>
		<th><?php echo $this->Paginator->sort('owner_id'); ?></th>
		<th><?php echo $this->Paginator->sort('created'); ?></th>
		<th class="actions"><?php echo __d('croogo', 'Actions'); ?></th>
	</tr>
	<?php foreach ($reports as $report): ?>
	<tr>
		<td><?php echo h($report['Report']['id']); ?>&nbsp;</td>
		<td><?php echo h($typeIds[$report['Report']['type']]); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($report['Topic']['title'], array('admin' => false, 'controller' => 'topics', 'action' => 'view', $report['Topic']['slug']), array('target' => '_blank')); ?>
		</td>
		<td>
<?php
	if ($report['Post']['id'] != false) {
		echo $this->Html->link('Click to see post(' . $report['Post']['id'] . ')', array('admin' => false, 'controller' => 'posts', 'action' => 'jumpToPost', $report['Post']['id']), array('target' => '_blank'));
	}
?>
		</td>
		<td><?php echo h($report['Report']['comment']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($report['Reporter']['name'], array('admin' => false, 'controller' => 'forum_user', 'action' => 'view', $report['Reporter']['id'], '?' => array('redirect_url' => '/forum')), array('target' => '_blank')); ?>
		</td>
		<td>
			<?php echo $this->Html->link($report['Owner']['name'], array('admin' => false, 'controller' => 'forum_user', 'action' => 'view', $report['Owner']['id'], '?' => array('redirect_url' => '/forum')), array('target' => '_blank')); ?>
		</td>
		<td><?php echo h($report['Report']['created']); ?>&nbsp;</td>
		<td class="item-actions">
			<?php echo $this->Croogo->adminRowAction('', array('action' => 'delete', $report['Report']['id']), array('icon' => 'trash', 'escape' => true), __d('croogo', 'Are you sure you want to delete # %s?', $report['Report']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
</div>
