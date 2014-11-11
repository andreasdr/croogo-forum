<?php
/**
 * @copyright   2006-2013, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/admin/blob/master/license.md
 * @link        http://milesj.me/code/cakephp/admin
 */

App::uses('ForumAppController', 'Forum.Controller');

/**
 * @property Topic $Topic
 * @property ForumUser $ForumUser
 */
class ForumController extends ForumAppController {

    /**
     * Models.
     *
     * @type array
     */
    public $uses = array('Forum.Topic', 'Forum.ForumUser');

    /**
     * Components.
     *
     * @type array
     */
    public $components = array('RequestHandler', 'Paginator');

    /**
     * Helpers.
     *
     * @type array
     */
    public $helpers = array('Rss');

    /**
     * Forum index.
     */
    public function index() {
        if ($this->RequestHandler->isRss()) {
            $this->set('items', $this->Topic->getLatest());
            return;
        }

        $this->set('menuTab', 'forums');
        $this->set('forums',         $this->Topic->Forum->getIndex());
        $this->set('totalPosts',     $this->Topic->Post->getTotal());
        $this->set('totalTopics',    $this->Topic->getTotal());
        $this->set('totalUsers',     $this->ForumUser->getTotal());
        $this->set('newestUser',     $this->ForumUser->getNewestUser());
        $this->set('whosOnline',     $this->ForumUser->whosOnline());
    }

    /**
     * Help.
     */
    public function help() {
        $this->set('menuTab', 'help');
    }

    /**
     * Rules.
     */
    public function rules() {
        $this->set('menuTab', 'rules');
    }

    /**
     * Jump to a specific topic and post.
     *
     * @param int $topic_id
     * @param int $post_id
     */
    public function jump($topic_id, $post_id = null) {
        $this->ForumToolbar->goToPage($topic_id, $post_id);
    }

    /**
     * Before filter.
     */
    public function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow();
    }

	/**************************************************************************
	 * Admin                                                                  *
	 **************************************************************************/

    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index() {
    	$this->Topic->Forum->recursive = 1;
    	$this->set('forums', $this->paginate('Forum'));
    }

    /**
     * admin_view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_view($id = null) {
    	if (!$this->Topic->Forum->exists($id)) {
    		throw new NotFoundException(__d('croogo', 'Invalid forum'));
    	}
    	$this->Topic->Forum->recursive = 1;
    	$options = array('conditions' => array('Forum.' . $this->Topic->Forum->primaryKey => $id));
    	$this->set('forum', $this->Topic->Forum->find('first', $options));
    }
    
    /**
     * admin_add method
     *
     * @return void
     */
    public function admin_add() {
    	if ($this->request->is('post')) {
    		$this->Topic->Forum->create();
    		if ($this->Topic->Forum->save($this->request->data)) {
    			$this->Session->setFlash(__d('croogo', 'The forum has been saved'), 'default', array('class' => 'success'));
    			$this->redirect(array('action' => 'index'));
    		} else {
    			$this->Session->setFlash(__d('croogo', 'The forum could not be saved. Please, try again.'), 'default', array('class' => 'error'));
    		}
    	}
    	$parents = $this->Topic->Forum->Parent->find('list');
    	$lastTopics = $this->Topic->Forum->LastTopic->find('list');
    	$lastPosts = $this->Topic->Forum->LastPost->find('list');
    	$lastUsers = $this->Topic->Forum->LastUser->find('list');
    	$this->set(compact('parents', 'lastTopics', 'lastPosts', 'lastUsers'));
    }
    
    /**
     * admin_edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_edit($id = null) {
    	if (!$this->Topic->Forum->exists($id)) {
    		throw new NotFoundException(__d('croogo', 'Invalid forum'));
    	}
    	$this->Topic->Forum->recursive = 1;
    	if ($this->request->is('post') || $this->request->is('put')) {
    		if ($this->Topic->Forum->save($this->request->data)) {
    			$this->Session->setFlash(__d('croogo', 'The forum has been saved'), 'default', array('class' => 'success'));
    			$this->redirect(array('action' => 'index'));
    		} else {
    			$this->Session->setFlash(__d('croogo', 'The forum could not be saved. Please, try again.'), 'default', array('class' => 'error'));
    		}
    	} else {
    		$options = array('conditions' => array('Forum.' . $this->Topic->Forum->primaryKey => $id));
    		$this->request->data = $this->Topic->Forum->find('first', $options);
    	}
    	$parents = $this->Topic->Forum->Parent->find('list');
    	$lastTopics = $this->Topic->Forum->LastTopic->find('list');
    	$lastPosts = $this->Topic->Forum->LastPost->find('list');
    	$lastUsers = $this->Topic->Forum->LastUser->find('list');
    	$this->set(compact('parents', 'lastTopics', 'lastPosts', 'lastUsers'));
    }
    
    /**
     * admin_delete method
     *
     * @throws NotFoundException
     * @throws MethodNotAllowedException
     * @param string $id
     * @return void
     */
    public function admin_delete($id = null) {
    	$this->Topic->Forum->recursive = 1;
    	$this->Topic->Forum->id = $id;
    	if (!$this->Topic->Forum->exists()) {
    		throw new NotFoundException(__d('croogo', 'Invalid forum'));
    	}
    	$this->request->onlyAllow('post', 'delete');
    	if ($this->Topic->Forum->delete()) {
    		$this->Session->setFlash(__d('croogo', 'Forum deleted'), 'default', array('class' => 'success'));
    		$this->redirect(array('action' => 'index'));
    	}
    	$this->Session->setFlash(__d('croogo', 'Forum was not deleted'), 'default', array('class' => 'error'));
    	$this->redirect(array('action' => 'index'));
    }
 
}
