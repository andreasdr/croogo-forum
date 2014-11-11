<?php
if (!empty($post['Forum']['Parent']['slug'])) {
    $this->Breadcrumb->add($post['Forum']['Parent']['title'], array('controller' => 'stations', 'action' => 'view', $post['Forum']['Parent']['slug']));
}

$this->Breadcrumb->add($post['Forum']['title'], array('controller' => 'stations', 'action' => 'view', $post['Forum']['slug']));
$this->Breadcrumb->add($post['Topic']['title'], array('controller' => 'topics', 'action' => 'view', $post['Topic']['slug']));
$this->Breadcrumb->add(__d('forum', 'Report Post'), array('action' => 'report', $post['Post']['id'])); ?>

<div class="forum-container">
	<?php echo $this->element('header'); ?>

	<div class="title">
    	<h2><?php echo __d('forum', 'Report Post'); ?></h2>
	    <p>
    	    <?php printf(__d('forum', 'Are you sure you want to report the post (below) in the topic %s? If so, please add a comment as to why you are reporting it, 255 max characters.'),
            $this->Html->link($post['Topic']['title'], array('controller' => 'forum', 'action' => 'jump', $post['Topic']['id'], $post['Post']['id']))); ?>
    	</p>
	</div>

    <?php
    echo $this->Form->create('Report');
    echo $this->Form->input('type', array('type' => 'select', 'options' => array(0 => 'Other', 1 => 'Violence', 2 => 'Offensive', 3 => 'Hateful', 4 => 'Harmful', 5 => 'Spam', 6 => 'Copyright', 7 => 'Sexual', 8 => 'Harassment')));
    echo $this->Form->input('comment', array('type' => 'textarea', 'label' => __d('forum', 'Comment')));
    echo $this->Form->submit(__d('forum', 'Report'), array('class' => 'button large error'));
    echo $this->Form->end(); ?>

    <?php echo $this->element('footer'); ?>
</div>
