	<div class="foot">
		<div class="copyright">
			<?php printf(__d('forum', 'Powered by the %s v%s'), $this->Html->link('Forum Plugin', 'http://milesj.me/code/cakephp/forum'), mb_strtoupper($config['Forum']['version'])); ?><br/>
			<?php printf(__d('forum', 'Created by %s'), $this->Html->link('Miles Johnson', 'http://milesj.me')); ?>
		</div>
<?php
	if (! CakePlugin::loaded ( 'DebugKit' )) {
		echo $this->element ( 'sql_dump' );
	}
?>
	</div>
</div>