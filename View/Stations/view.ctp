<?php
$this->OpenGraph->description($forum['Forum']['description']);

if (!empty($forum['Parent']['slug'])) {
    $this->Breadcrumb->add($forum['Parent']['title'], array('controller' => 'stations', 'action' => 'view', $forum['Parent']['slug']));
}

$this->Breadcrumb->add($forum['Forum']['title'], array('controller' => 'stations', 'action' => 'view', $forum['Forum']['slug'])); ?>

<div class="forum-container">
	<?php echo $this->element('header'); ?>
	
	<div class="title">
	    <?php if ($forum['Forum']['parent_id']) {
	        echo $this->element('tiles/forum_controls', array('forum' => $forum));
	    } ?>
	
	    <h2><?php echo h($forum['Forum']['title']); ?></h2>
	
	    <?php if ($forum['Forum']['description']) { ?>
	        <p><?php echo h($forum['Forum']['description']); ?></p>
	    <?php } ?>
	</div>

    <?php if ($forum['Children']) { ?>

        <div class="panel">
            <div class="panel-head">
                <h5><?php echo __d('forum', 'Sub-Forums'); ?></h5>
            </div>

            <div class="panel-body">
                <table class="table table--hover">
                    <thead>
                        <tr>
                        	<th width="1%">&nbsp;</th>
                            <th width="69%"><?php echo __d('forum', 'Forum'); ?></th>
                            <th width="5%" ><?php echo __d('forum', 'Topics'); ?></th>
                            <th width="5%" ><?php echo __d('forum', 'Posts'); ?></th>
                            <th width="20%" ><?php echo __d('forum', 'Activity'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($forum['Children'] as $counter => $subForum) {
                            echo $this->element('tiles/forum_row', array(
                                'forum' => $subForum,
                                'counter' => $counter
                            ));
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php }

    // Cant post in top level
    if ($forum['Forum']['parent_id']) {
        echo $this->element('pagination', array('class' => 'top')); ?>

        <div class="panel" id="topics">
            <div class="panel-body">
                <table class="table table--hover table--sortable">
                    <thead>
                        <tr>
                        	<th width="1%">&nbsp;</th>
                            <th width="69%"><?php echo $this->Paginator->sort('Topic.title', __d('forum', 'Topic')); ?></th>
                            <th width="5%" class="align-center"><?php echo $this->Paginator->sort('Topic.post_count', __d('forum', 'Posts')); ?></th>
                            <th width="5%" class="align-center"><?php echo $this->Paginator->sort('Topic.view_count', __d('forum', 'Views')); ?></th>
                            <th width="20%" class="align-right"><?php echo $this->Paginator->sort('LastPost.created', __d('forum', 'Activity')); ?></th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php if ($announcements) {
                        foreach ($announcements as $counter => $topic) {
                            echo $this->element('tiles/topic_row_short', array(
                                'counter' => $counter,
                                'topic' => $topic
                            ));
                        } ?>

                        <tr class="divider">
                            <td colspan="7"><?php echo __d('forum', 'Topics'); ?></td>
                        </tr>

                    <?php }

                    if ($topics) {
                        foreach ($topics as $counter => $topic) {
                            echo $this->element('tiles/topic_row_short', array(
                                'counter' => $counter,
                                'topic' => $topic
                            ));
                        }
                    } else { ?>

                        <tr>
                            <td colspan="7" class="no-results"><?php echo __d('forum', 'There are no topics within this forum'); ?></td>
                        </tr>

                    <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>

        <?php echo $this->element('pagination', array('class' => 'bottom')); ?>

        <div class="statistics">
            <?php $moderators = array();
            if ($forum['Moderator']) {
                foreach ($forum['Moderator'] as $mod) {
                    $moderators[] = $this->Html->link($mod['User'][$userFields['username']], $this->Forum->profileUrl($mod['User']));
                }
            } ?>

            <table class="table">
                <tbody>
                    <?php if ($moderators) { ?>
                        <tr>
                            <td class="align-right"><?php echo __d('forum', 'Moderators'); ?>: </td>
                            <td><?php echo implode(', ', $moderators); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <?php echo $this->element('tiles/forum_controls', array('forum' => $forum));
    } ?>

    <?php echo $this->element('footer'); ?>
</div>
