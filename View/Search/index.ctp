<?php
$this->Decoda->disableSpoiler();
$this->Breadcrumb->add(__d('forum', 'Search'), array('controller' => 'search', 'action' => 'index')); ?>

<div class="forum-container">
	<?php echo $this->element('header_no_breadcrumbs'); ?>

	<div class="title">
    	<h2><?php echo __d('forum', $type == 'new_posts'?'View new posts':'Search'); ?></h2>
	</div>

	<?php if ($type != 'new_posts'): ?>
	    <?php echo $this->Form->create('Search', array('class' => 'form--inline', 'url' => array('controller' => 'search', 'action' => 'proxy'))); ?>
	
	    <div class="form-filters" id="search">
	        <?php
	        echo $this->Form->input('keywords', array('div' => 'field', 'label' => __d('forum', 'With keywords')));
	        echo $this->Form->input('forum_id', array('div' => 'field', 'label' => __d('forum', 'in forum'), 'options' => $forums, 'empty' => true));
	        echo $this->Form->input('byUser', array('div' => 'field', 'label' => __d('forum', 'by user')));
	      	?>
	    </div>
	
	    <?php
	    echo $this->Form->submit(__d('forum', 'Search'), array('class' => 'button'));
	    echo $this->Form->end();
	    ?>
	<?php endif; ?>

    <?php if ($searching) {
		if (!$topics && !$posts) { ?>
			<span class="no-results"><? echo __d('forum', 'No results were found, please refine your search criteria'); ?></span>
		<?php } else {
        	echo $this->element('pagination', array('class' => 'top', 'model' => $paginateModel));

        	if ($topics) { ?>
		        <div class="panel">
		            <div class="panel-body">
		                <table class="table table--hover table--sortable">
		                    <thead>
		                        <tr>
		                            <th colspan="2"><?php echo $this->Paginator->sort('Topic.title', __d('forum', 'Topic')); ?></th>
		                            <th><?php echo $this->Paginator->sort('Topic.forum_id', __d('forum', 'Forum')); ?></th>
		                            <th><?php echo $this->Paginator->sort('User.' . $userFields['username'], __d('forum', 'Author')); ?></th>
		                            <th class="align-center"><?php echo $this->Paginator->sort('Topic.created', __d('forum', 'Created')); ?></th>
		                            <th class="align-center"><?php echo $this->Paginator->sort('Topic.post_count', __d('forum', 'Posts')); ?></th>
		                            <th class="align-center"><?php echo $this->Paginator->sort('Topic.view_count', __d('forum', 'Views')); ?></th>
		                            <th class="align-right"><?php echo $this->Paginator->sort('LastPost.created', __d('forum', 'Activity')); ?></th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    <?php 
		                        foreach ($topics as $counter => $topic) {
		                            echo $this->element('tiles/topic_row', array(
		                                'topic' => $topic,
		                                'counter' => $counter,
		                                'columns' => array('forum')
		                            ));
		                        }
		                    ?>
		                    </tbody>
		                </table>
		            </div>
		        </div>
		<?php } 
			if ($posts) { ?>
		        <div class="panel">
		            <div class="panel-body">
		                <table class="table table--hover table--sortable">
		                    <thead>
		                        <tr>
		                            <th colspan="2"><?php echo $this->Paginator->sort('Topic.title', __d('forum', 'Topic')); ?></th>
		                            <th><?php echo $this->Paginator->sort('Topic.forum_id', __d('forum', 'Forum')); ?></th>
		                            <th><?php echo $this->Paginator->sort('User.' . $userFields['username'], __d('forum', 'Author')); ?></th>
		                            <th class="align-center"><?php echo $this->Paginator->sort('Post.created', __d('forum', 'Created')); ?></th>
		                            <th colspan="3"><?php echo __d('forum', 'Content') ?></th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    <?php 
								foreach ($posts as $counter => $post) {
									echo $this->element('tiles/post_row', array(
										'post' => $post,
										'counter' => $counter,
										'columns' => array('forum')
									));
								}
		                    ?>
		                    </tbody>
		                </table>
		            </div>
		        </div>
	 		<?php } ?>
	 		<?php echo $this->element('pagination', array('class' => 'bottom', 'model' => $paginateModel)); ?>
	 	<?php } ?>
	<?php } ?>
	<?php echo $this->element('footer'); ?>
</div>