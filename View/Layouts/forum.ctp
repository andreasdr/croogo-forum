<?php
    echo $this->Html->css('Admin.titon.min');
    echo $this->Html->css('Admin.font-awesome.min');
    echo $this->Html->css('Admin.style');
    echo $this->Html->css('Forum.style');
    echo $this->Html->script('//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js');
    echo $this->Html->script('Admin.titon.min');
    echo $this->Html->script('Forum.forum');

    $locales = $config['Decoda']['locales'];
?>
</head>
<body class="controller-<?php echo $this->request->controller; ?>">
    <div class="skeleton">
        <header class="head">
            <?php echo $this->element('navigation'); ?>
        </header>

        <div class="body action-<?php echo $this->action; ?>">
            <?php
            $this->Breadcrumb->prepend(__d('forum', 'Forum'), array('plugin' => 'forum', 'controller' => 'forum', 'action' => 'index'));
            $this->Breadcrumb->prepend($settings['name'], '/');

            echo $this->element('Admin.breadcrumbs');
            echo $this->Session->flash();
            echo $this->fetch('content'); ?>
        </div>

        <footer class="foot">
            <div class="copyright">
                <?php printf(__d('forum', 'Powered by the %s v%s'), $this->Html->link('Forum Plugin', 'http://milesj.me/code/cakephp/forum'), mb_strtoupper($config['Forum']['version'])); ?><br/>
                <?php printf(__d('forum', 'Created by %s'), $this->Html->link('Miles Johnson', 'http://milesj.me')); ?>
            </div>

            <?php if (!CakePlugin::loaded('DebugKit')) {
                echo $this->element('sql_dump');
            } ?>
        </footer>
    </div>
</body>
</html>
