<?php
$columns = isset($columns) ? $columns : array(); ?>

<tr>
    <td colspan="2">
        <?php echo $this->Html->link($post['Topic']['title'], array('controller' => 'topics', 'action' => 'view', $post['Topic']['slug']), array('class' => 'topic-title')); ?>
    </td>
	<td class="col-parent">
		<?php echo $this->Html->link($post['Forum']['title'], array('controller' => 'stations', 'action' => 'view', $post['Forum']['slug'])); ?>
	</td>
    <td class="col-author">
        <?php echo $this->Html->link($post['User'][$userFields['username']], $this->Forum->profileUrl($post['User'])); ?>
    </td>
    <td class="col-created">
        <?php echo $this->Time->niceShort($post['Post']['created'], $this->Forum->timezone()); ?>
    </td>
    <td colspan="3">
	    <?php
			$postSummery = trim(strip_tags($this->Decoda->parse($post['Post']['content'])));
			if (mb_strlen($postSummery, 'utf-8') > 30) {
				$postSummery = mb_substr($postSummery, 0, 80, 'utf-8');
			}
			$postSummery.= '...';
	    	echo $postSummery;
	    ?>
	    &nbsp;
	    <?php echo $this->Html->link('Click to open', array('controller' => 'posts', 'action' => 'jumpToPost', $post['Post']['id'])); ?>
    </td>
</tr>
