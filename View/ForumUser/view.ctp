<div class="forum-container user">
	<?php echo $this->element('header_no_breadcrumbs'); ?>

	<div class="title">
    	<h2><?php echo __d('forum', 'Account'); ?></h2>
	</div>
	
	<div class="panel">
		<div class="panel-body">
			<div class="photo">
				<?php if ($this->request->data['User'][$userFields['avatar']] != false):?>
				<img src="<?php echo $this->request->data['User'][$userFields['avatar']]; ?>" alt="" /><br /><br />
				<?php else: ?>
				<img id="photo" src="<?php echo $this->Html->assetUrl('Forum.nophoto.png', array('pathPrefix' => Configure::read('App.imageBaseUrl'))); ?>" alt="" /><br /><br />
				<?php endif; ?>
			</div>
			<div class="text">
				Username: <?php echo $this->request->data['User'][$userFields['username']] ?><br />
				<?php if (!empty($this->request->data['User'][$userFields['location']])): ?>
				Location: <?php echo $this->request->data['User'][$userFields['location']] ?><br />
				<?php endif;?>
				Website: <?php echo $this->request->data['User'][$userFields['website']] ?><br />
				<?php if (!empty($this->request->data['User'][$userFields['totalPosts']])): ?>
				Tag: <?php echo $this->Forum->getUserCountTag($this->request->data['User'][$userFields['totalPosts']]); ?>
				<?php endif; ?>
				<br />
			</div>
			<div class="clearboth"></div>
		</div>
		<?php if (isset($this->request->query['redirect_url'])):?>
		<div class="button">
			<button onclick="location.href='<?php echo $this->request->query['redirect_url'] ?>'">Back</button>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php echo $this->element('footer'); ?>