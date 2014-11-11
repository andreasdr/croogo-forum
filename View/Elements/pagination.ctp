<nav class="pagination pagination--grouped round <?php echo $class; ?>">
    <ul>
        <?php
        $options = array(
            'first' => __d('admin', 'First'),
            'last' => __d('admin', 'Last'),
            'currentTag' => 'a',
            'currentClass' => 'is-active',
            'separator' => '',
            'ellipsis' => '<li><span>...</span></li>',
            'tag' => 'li'
        );
        if (isset($model) == true) $options['model'] = $model;
        echo $this->Paginator->numbers($options); ?>
    </ul>

    <div class="counter">
        <?php
        $options = array(
        	'format' => __d('admin', 'Showing %s-%s of %s', array('<span>{:start}</span>', '<span>{:end}</span>', '<span>{:count}</span>'))
        );
		if (isset($model) == true) $options['model'] = $model;
        echo $this->Paginator->counter($options); ?>
    </div>

    <script type="text/javascript">
        $(function() {
            // Add button class to pagination links since CakePHP doesn't support it
            $('.pagination a').addClass('button');
        });
    </script>
</nav>