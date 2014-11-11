<nav class="nav clear-after">
    <div class="nav-buttons">
        <?php
        if ($user) {
            echo $this->Html->link($user[$userFields['username']], $this->forum->getUserRoute('settings', $user), array('class' => 'button'));
            echo $this->Html->link(__d('forum', 'View New Posts'), array('controller' => 'search', 'action' => 'index', 'new_posts', 'admin' => false), array('class' => 'button'));
            echo $this->Html->link(__d('forum', 'Logout'), $userRoutes['logout'] + array('?' => array('redirect_url' => $this->here . (count($this->request->query) > 0?'?' . http_build_query($this->request->query):''))), array('class' => 'button error'));

        } else {
            echo $this->Html->link(__d('forum', 'Login'), $userRoutes['login'] + array('?' => array('redirect_url' => $this->here . (count($this->request->query) > 0?'?' . http_build_query($this->request->query):''))), array('class' => 'button'));

            if (!empty($userRoutes['signup'])) {
                echo $this->Html->link(__d('forum', 'Sign Up'), $userRoutes['signup'] + array('?' => array('redirect_url' => $this->here . (count($this->request->query) > 0?'?' . http_build_query($this->request->query):''))), array('class' => 'button'));
            }

            if (!empty($userRoutes['forgotPass'])) {
                echo $this->Html->link(__d('forum', 'Forgot Password'), $userRoutes['forgotPass'] + array('redirect_url' => $this->here . (count($this->request->query) > 0?'?' . http_build_query($this->request->query):'')), array('class' => 'button'));
            }
        } ?>
    </div>

    <ul class="nav-menu">
        <li<?php if ($menuTab === 'forums') echo ' class="is-active"'; ?>><?php echo $this->Html->link(__d('forum', 'Forums'), array('controller' => 'forum', 'action' => 'index')); ?></li>
        <li<?php if ($menuTab === 'search') echo ' class="is-active"'; ?>><?php echo $this->Html->link(__d('forum', 'Search'), array('controller' => 'search', 'action' => 'index')); ?></li>
        <li<?php if ($menuTab === 'rules') echo ' class="is-active"'; ?>><?php echo $this->Html->link(__d('forum', 'Rules'), array('controller' => 'forum', 'action' => 'rules')); ?></li>
        <li<?php if ($menuTab === 'help') echo ' class="is-active"'; ?>><?php echo $this->Html->link(__d('forum', 'Help'), array('controller' => 'forum', 'action' => 'help')); ?></li>
    </ul>
</nav>
