<?php
/**
 * @copyright   2006-2013, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/admin/blob/master/license.md
 * @link        http://milesj.me/code/cakephp/admin
 */

App::uses('ForumAppController', 'Forum.Controller');
App::uses('Forum', 'Forum.Model');

/**
 * @property Topic $Topic
 */
class SearchController extends ForumAppController {

    /**
     * Models.
     *
     * @type array
     */
    public $uses = array('Forum.Topic', 'Forum.Post');

    /**
     * Pagination.
     *
     * @type array
     */
    public $paginate = array(
    	'Post' => array(
    		'order' => array('Post.created' => 'DESC'),
    		'contain' => array('Forum', 'Topic', 'User')
		),
        'Topic' => array(
            'order' => array('LastPost.created' => 'DESC'),
            'contain' => array('Forum', 'User', 'Poll', 'LastPost', 'LastUser')
        )
    );

    /**
     * Search the topics.
     *
     * @param string $type
     */
    public function index($type = '') {
        $searching = false;
        if ($this->request->params['named']) {
            foreach ($this->request->params['named'] as $field => $value) {
                $this->request->data['Search'][$field] = urldecode($value);
            }
        }

        if ($type === 'new_posts') {
            $this->request->data['Search']['orderBy'] = 'LastPost.created';
            $this->paginate['Topic']['conditions']['LastPost.created >='] = $this->Session->read('Forum.lastVisit');
        }

        if ($this->request->data) {
            $searching = true;

            if (!empty($this->request->data['Search']['keywords'])) {
            	/* 
            	 * This was the attempt with MySQL fulltext search, its crap for now since it cant find e.g. words in words
            	 * 	like it would not match "fach" in a word like "wurstfachhandel" :(
            	 * $this->paginate['Topic']['conditions'][] = "MATCH (Topic.title) AGAINST ($keywordsEscaped IN BOOLEAN MODE)";
            	 * $this->paginate['Post']['conditions'][] = "MATCH (Post.content) AGAINST ($keywordsEscaped IN BOOLEAN MODE)";
            	 * $keywordsEscaped = $this->Topic->getDataSource()->value('*' . Sanitize::clean($this->request->data['Search']['keywords']) . '*');
            	 * 
            	 * So i'll go with LIKE, this can be a problem if really having a lot of data
            	 */
            	$this->paginate['Topic']['conditions']['Topic.title LIKE'] = '%' . Sanitize::clean($this->request->data['Search']['keywords']) . '%';
            	$this->paginate['Post']['conditions']['Post.content LIKE'] = '%' . Sanitize::clean($this->request->data['Search']['keywords']) . '%';
            }

            if (!empty($this->request->data['Search']['forum_id'])) {
                $this->paginate['Topic']['conditions']['Topic.forum_id'] = $this->request->data['Search']['forum_id'];
                $this->paginate['Post']['conditions']['Post.forum_id'] = $this->request->data['Search']['forum_id'];
            }

            if (!empty($this->request->data['Search']['byUser'])) {
                $this->paginate['Topic']['conditions']['User.' . $this->config['User']['fieldMap']['username'] . ' LIKE'] = '%' . Sanitize::clean($this->request->data['Search']['byUser']) . '%';
                $this->paginate['Post']['conditions']['User.' . $this->config['User']['fieldMap']['username'] . ' LIKE'] = '%' . Sanitize::clean($this->request->data['Search']['byUser']) . '%';
            }

            $this->paginate['Topic']['limit'] = $this->settings['topicsPerPage'];
            $this->paginate['Post']['limit'] = $this->settings['topicsPerPage'];

            try {
            	$this->set('topics',
            		$this->paginate(
            			'Topic',
            			array(),
            			array(
            				'Topic.title',
            				'Topic.forum_id',
            				'User.username',
            				'Topic.created',
            				'Topic.post_count',
            				'Topic.view_count',
            				'LastPost.created'
            			)
            		)
            	);
            } catch (NotFoundException $nfe) {
            	$this->set('topics', array());
            }
            if ($type === 'new_posts') {
            	$this->set('posts', array());
            	$this->set('paginateModel', 'Topic');
            } else {
            	try {
            		$this->set(
            			'posts',
            			$this->paginate(
            				'Post',
            				array(),
	            			array(
	            				'Topic.title',
	            				'Topic.forum_id',
	            				'User.username',
	            				'Post.created',
	            			)
            			)
            		);
            	} catch (NotFoundException $nfe) { $this->set('posts', array()); }
            	$this->set('paginateModel', $this->params['paging']['Topic']['pageCount'] > $this->params['paging']['Post']['pageCount']?'Topic':'Post');
            }            
        }

        $this->set('menuTab', 'search');
        $this->set('searching', $searching);
        $this->set('type', $type);
        $this->set('forums', $this->Topic->Forum->getHierarchy());
    }

    /**
     * Proxy action to build named parameters.
     */
    public function proxy() {
        $named = array();

        foreach ($this->request->data['Search'] as $field => $value) {
            if ($value !== '') {
                $named[$field] = urlencode($value);
            }
        }

        $this->redirect(array_merge(array('controller' => 'search', 'action' => 'index'), $named));
    }

    /**
     * Before filter.
     */
    public function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow();
    }

}
