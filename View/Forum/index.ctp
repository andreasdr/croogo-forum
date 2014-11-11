<div class="forum-container">
	<?php echo $this->element('header_no_breadcrumbs'); ?>

    <?php
    if ($forums) {
        foreach ($forums as $forum) { ?>

    <div class="panel">
        <div class="panel-head">
            <h3><?php echo h($forum['Forum']['title']); ?></h3>
        </div>

        <div class="panel-body">
            <table class="table table--hover">
                <thead>
                    <tr>
                    	<th width="1%"></th>
                        <th width="69%"><?php echo __d('forum', 'Forum'); ?></th>
                        <th width="5%" class="align-center"><?php echo __d('forum', 'Topics'); ?></th>
                        <th width="5%" class="align-center"><?php echo __d('forum', 'Posts'); ?></th>
                        <th width="30%" class="align-right"><?php echo __d('forum', 'Activity'); ?></th>
                    </tr>
                </thead>
                <tbody>

                <?php if ($forum['Children']) {
                    foreach ($forum['Children'] as $counter => $child) {
                        echo $this->element('tiles/forum_row', array(
                            'forum' => $child,
                            'counter' => $counter
                        ));
                    }
                } else { ?>

                    <tr>
                        <td colspan="5" class="no-results"><?php echo __d('forum', 'There are no categories within this forum'); ?></td>
                    </tr>

                <?php } ?>

                </tbody>
            </table>
        </div>
    </div>

    <?php } } ?>

    <div class="statistics">
        <div class="total-stats">
            <b><?php echo __d('forum', 'Statistics'); ?>:</b> <?php printf(__d('forum', '%d topics, %d posts, and %d users'), $totalTopics, $totalPosts, $totalUsers); ?>
        </div>

        <?php if ($newestUser) { ?>
            <div class="newest-user">
                <b><?php echo __d('forum', 'Newest User'); ?>:</b> <?php echo $this->Html->link($newestUser['User'][$userFields['username']], $this->Forum->profileUrl($newestUser['User'])); ?>
            </div>
        <?php }

        if ($whosOnline) {
            $onlineUsers = array();

            foreach ($whosOnline as $online) {
                $onlineUsers[] = $this->Html->link($online['User'][$userFields['username']], $this->Forum->profileUrl($online['User']));
            } ?>

            <div class="whos-online">
                <b><?php echo __d('forum', 'Whos Online'); ?>:</b> <?php echo implode(', ', $onlineUsers); ?>
            </div>
        <?php } ?>
    </div>

    <?php echo $this->element('footer'); ?>
</div>
