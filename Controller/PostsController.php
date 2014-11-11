<?php
/**
 * @copyright   2006-2013, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/admin/blob/master/license.md
 * @link        http://milesj.me/code/cakephp/admin
 */

App::uses('ForumAppController', 'Forum.Controller');
App::uses('PostRating', 'Forum.Model');

/**
 * @property Post $Post
 * @property PostRating $PostRating
 * @property AjaxHandlerComponent $AjaxHandler
 */
class PostsController extends ForumAppController {

    /**
     * Models.
     *
     * @type array
     */
    public $uses = array('Forum.Post', 'Forum.Report', 'Forum.PostRating');

    /**
     * Components.
     *
     * @type array
     */
    public $components = array('Forum.AjaxHandler');

    /**
     * Redirect.
     */
    public function index() {
        $this->ForumToolbar->goToPage();
    }

    /**
     * Add post / reply to topic.
     *
     * @param string $slug
     * @param int $quote_id
     */
    public function add($slug, $quote_id = null) {
        $topic = $this->Post->Topic->getBySlug($slug);
        $user_id = $this->Auth->user('id');

        $this->ForumToolbar->verifyAccess(array(
            'exists' => $topic,
            'status' => $topic['Topic']['status'],
            'access' => $topic['Forum']['accessReply']
        ));

        if ($this->request->data) {
            $this->request->data['Post']['forum_id'] = $topic['Topic']['forum_id'];
            $this->request->data['Post']['topic_id'] = $topic['Topic']['id'];
            $this->request->data['Post']['user_id'] = $user_id;
            $this->request->data['Post']['userIP'] = $this->request->clientIp();
            if ($post_id = $this->Post->addPost($this->request->data['Post'])) {
                $this->ForumToolbar->updatePosts($post_id);

                // auto rate own post
                if ($this->settings['postAutoRate'] == true) {
                	$this->PostRating->ratePost($user_id, $post_id, $topic['Topic']['id'], PostRating::UP);
                }

                //
                $this->ForumToolbar->goToPage($topic['Topic']['id'], $post_id);
            }
        } else if ($quote_id) {
            if ($quote = $this->Post->getQuote($quote_id)) {
                $this->request->data['Post']['content'] = sprintf('[quote="%s" date="%s"]%s[/quote]',
                    $quote['User'][$this->config['User']['fieldMap']['username']],
                    $quote['Post']['created'],
                    $quote['Post']['content']
                ) . PHP_EOL;
            }
        }

        $this->set('topic', $topic);
        $this->set('review', $this->Post->getTopicReview($topic['Topic']['id']));
    }

    /**
     * Edit a post.
     *
     * @param int $id
     */
    public function edit($id) {
        $post = $this->Post->getById($id);

        $this->ForumToolbar->verifyAccess(array(
            'exists' => $post,
            'moderate' => $post['Topic']['forum_id'],
            'ownership' => $post['Post']['user_id']
        ));

        if ($this->request->data) {
            $this->Post->id = $id;

            if ($this->Post->save($this->request->data, true, array('content'))) {
                $this->ForumToolbar->goToPage($post['Post']['topic_id'], $id);
            }
        } else {
            $this->request->data = $post;
        }

        $this->set('post', $post);
    }

    /**
     * Delete a post.
     *
     * @param int $id
     */
    public function delete($id) {
        $post = $this->Post->getById($id);

        $this->ForumToolbar->verifyAccess(array(
            'exists' => $post,
            'moderate' => $post['Topic']['forum_id'],
            'ownership' => $post['Post']['user_id']
        ));

        $this->Post->delete($id, true);
        $this->redirect(array('controller' => 'topics', 'action' => 'view', $post['Topic']['slug']));
    }

    /**
     * Report a post.
     *
     * @param int $id
     */
    public function report($id) {
        $post = $this->Post->getById($id);
        $user_id = $this->Auth->user('id');

        $this->ForumToolbar->verifyAccess(array(
            'exists' => $post
        ));

        if ($this->request->is('post')) {
            $data = $this->request->data['Report'];
            if ($this->Report->createReportPost($data['type'], $post['Post']['id'], $data['comment'],  $user_id, $post['Post']['user_id']) === false) {
            	$this->Session->setFlash(__d('forum', 'We could not save your report. Please try again or support us.'));
            } else {
            	$this->Session->setFlash(__d('forum', 'You have successfully reported this post! A moderator will review this post and take the necessary action.'));
            	unset($this->request->data['Report']);
            }
        }
        $this->request->data['Report']['post'] = $post['Post']['content'];

        $this->set('post', $post);
    }

    /**
     * Preview the Decoda markup.
     */
    public function preview() {
        $input = isset($this->request->data['input']) ? $this->request->data['input'] : '';

        $this->set('input', $input);
        $this->layout = false;
    }

    /**
     * Rate a post up or down.
     *
     * @param int $id
     * @param int $type
     */
    public function rate($id, $type) {
        $user_id = $this->Auth->user('id');
        $post = $this->Post->getById($id);
        $success = true;

        if ($type != PostRating::UP && $type != PostRating::DOWN || !$post) {
            $success = false;

        } else if ($this->PostRating->hasRated($user_id, $id)) {
            $success = false;

        } else if (!$this->PostRating->ratePost($user_id, $id, $post['Post']['topic_id'], $type)) {
            $success = false;
        }
        $this->AjaxHandler->respond('json', array(
            'success' => $success
        ));
    }

    /**
     * Do a redirection to a specific post
     */
    public function jumpToPost() {
    	$postId = $this->request->params['pass'][0];
    	// fetch post
    	$post = $this->Post->find('first', array(
			'conditions' => array(
				'Post.id' => $postId,
			),
			'recursive' => 1,
			'limit' => 1
    	));
    
    	// just redirect to community/index if not found
    	if ($post === null) {
    		$this->redirect(
				array(
					'controller' => 'community',
					'action' => 'index',
				),
				302,
				true
    		);
    	}
    
    	// get all posts but flat in this topic to find out page
    	// 	there is just no other way right now
    	$topicPosts = $this->Post->find('all', array(
			'conditions' => array(
				'Post.topic_id' => $post['Topic']['id'],
			),
			'order' => array('Post.id ASC'),
			'recursive' => 0,
    	));
    	$topicIdx = 0;
    	$topicPage = 1;
    	$forumSettings = Configure::read('Forum.settings');
    	foreach($topicPosts as $topicPost) {
    		if ($topicPost['Post']['id'] == $post['Post']['id']) break;
    		$topicIdx++;
    		if ($topicIdx == $forumSettings['postsPerPage']) { $topicIdx = 0; $topicPage++; }
    	}
    
    	// do the redirection
    	$this->redirect(
			array(
				'plugin' => 'forum',
				'controller' => 'topics',
				'action' => 'view',
				'1' => $post['Topic']['slug'],
				'page' => $topicPage,
				'#' => 'post-' . $post['Post']['id']
			),
			302,
			true
    	);
    }

    /**
     * Before filter.
     */
    public function beforeFilter() {
        parent::beforeFilter();

        if ($this->request->is('ajax')) {
            $this->Security->validatePost = false;
            $this->Security->csrfCheck = false;
        }

        $this->Auth->allow('index', 'preview');
        $this->AjaxHandler->handle('rate');

        $this->set('menuTab', 'forums');
    }

}
