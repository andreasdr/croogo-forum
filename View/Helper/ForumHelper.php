<?php
/**
 * @copyright   2006-2013, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/admin/blob/master/license.md
 * @link        http://milesj.me/code/cakephp/admin
 */

App::uses('Forum', 'Forum.Model');
App::uses('Topic', 'Forum.Model');
App::uses('Report', 'Forum.Model');
App::uses('Moderator', 'Forum.Model');
App::uses('CakeTime', 'Utility');

class ForumHelper extends AppHelper {

	/**
	 * Helpers.
	 *
	 * @type array
	 */
	public $helpers = array('Html', 'Session', 'Utility.Decoda', 'Utility.Utility');
	
	/**
	 * Models.
	 *
	 * @type array
	*/
	public $uses = array('Forum.Moderator');

	/**
	 * @var Moderator
	 */
	private $Moderator;

	/**
	 * Public constructor
	 */
	public function __construct(View $View, $settings = array()) {
		$this->Moderator = new Moderator();
		parent::__construct($View, $settings);
	}

	/**
	 * Return a parse user route.
	 * 
	 * @param string $route
	 * @param array $user
	 * @return string
	 */
	public function getUserRoute($route, array $user) {
		$route = Configure::read('User.routes.' . $route);
	
		if (!$route) {
			return null;
		}
	
		$route = (array) $route;
	
		foreach ($route as &$value) {
			if ($value === '{id}') {
				$value = $user['id'];
	
			} else if ($value === '{slug}' && isset($user['slug'])) {
				$value = $user['slug'];
	
			} else if ($value === '{username}') {
				$value = $user[Configure::read('User.fieldMap.username')];
			}
		}
	
		return $this->url($route + array('?' => array('redirect_url' => $this->here . (count($this->request->query) > 0?'?' . http_build_query($this->request->query):''))));
	}

	/**
	 * Return true if the user is an admin.
	 *
	 * @return bool
	 */
	public function isAdmin() {
		return (bool) $this->Session->read('Acl.isAdmin');
	}

	/**
	 * Return true if the user is a super mod.
	 *
	 * @return bool
	 */
	public function isSuper() {
		return ($this->isAdmin() || $this->Session->read('Acl.isSuper'));
	}

	/**
	 * Check to see if the user has a role.
	 *
	 * @param int|string $role
	 * @return bool
	 */
	public function hasRole($role) {
		$roles = (array) $this->Session->read('Acl.roles');
	
		if (is_numeric($role)) {
			return isset($roles[$role]);
		}
	
		return in_array($role, $roles);
	}
	
	/**
	 * Checks to see if the user has mod status.
	 *
	 * @param string $model        	
	 * @param string $action        	
	 * @param int $role        	
	 * @return bool
	 */
	public function hasAccess($model, $action, $role = null) {
		$user = $this->Session->read ( 'Auth.User' );
		
		if (empty ( $user )) {
			return false;
		} else if ($this->isSuper ()) {
			return true;
		} else if ($role !== null) {
			if (! $this->hasRole ( $role )) {
				return false;
			}
		}

		// TODO: maybe implement finer access rules by controller permissions
		$has = true;

		// If permission doesn't exist, they have it by default
		if ($has === null) {
			return true;
		}
		
		return $has;
	}

	/**
	 * Normalize an array by grabbing the keys from a multi-dimension array (belongsTo, actsAs, etc).
	 *
	 * @param array $array
	 * @param bool $sort
	 * @return array
	 */
	public function normalizeArray($array, $sort = true) {
		$output = array();
	
		if ($array) {
			foreach ($array as $key => $value) {
				if (is_numeric($key)) {
					$output[] = $value;
				} else {
					$output[] = $key;
				}
			}
	
			if ($sort) {
				sort($output);
			}
		}
	
		return $output;
	}

    /**
     * Output a users avatar.
     *
     * @param array $user
     * @param int $size
     * @return string
     */
    public function avatar($user, $size = 100) {
        $userMap = Configure::read('User.fieldMap');
        $avatar = null;

        if (!empty($userMap['avatar']) && !empty($user['User'][$userMap['avatar']])) {
            $avatar = $this->Html->image($user['User'][$userMap['avatar']], array('width' => $size, 'height' => $size));

        } else if (Configure::read('Forum.settings.enableGravatar')) {
            $avatar = $this->Utility->gravatar($user['User'][$userMap['email']], array('size' => $size));
        }

        if ($avatar) {
            return $this->Html->div('avatar', $avatar);
        }

        return $avatar;
    }

    /**
     * Determine the forum icon state.
     *
     * @param array $forum
     * @param array $options
     * @return string
     */
    public function forumIcon($forum, array $options = array()) {
        $icon = 'open';
        $tooltip = '';

        if (isset($forum['LastPost']['created'])) {
            $lastPost = $forum['LastPost']['created'];

        } else if (isset($forum['LastTopic']['created'])) {
            $lastPost = $forum['LastTopic']['created'];
        }

        if ($forum['status'] == Forum::CLOSED) {
            $icon = 'closed';

        } else if (isset($lastPost) && $lastPost > $this->Session->read('Forum.lastVisit')) {
            $icon = 'new';
        }

        $custom = null;

        switch ($icon) {
            case 'open': $tooltip = __d('forum', 'No New Posts'); break;
            case 'closed': $tooltip = __d('forum', 'Closed'); break;
            case 'new': $tooltip = __d('forum', 'New Posts'); break;
        }

        return $this->Html->image('Forum.forum_icons/icon_' . $icon . '.png', array('valign' => 'middle', 'alt' => $tooltip, 'title' => $tooltip));
    }

    /**
     * Get topics made in the past hour.
     *
     * @return int
     */
    public function getTopicsMade() {
        $pastHour = strtotime('-1 hour');
        $count = 0;

        if ($topics = $this->Session->read('Forum.topics')) {
            foreach ($topics as $time) {
                if ($time >= $pastHour) {
                    ++$count;
                }
            }
        }

        return $count;
    }

    /**
     * Get posts made in the past hour.
     *
     * @return int
     */
    public function getPostsMade() {
        $pastHour = strtotime('-1 hour');
        $count = 0;

        if ($posts = $this->Session->read('Forum.posts')) {
            foreach ($posts as $time) {
                if ($time >= $pastHour) {
                    ++$count;
                }
            }
        }

        return $count;
    }

    /**
     * Return true if the user is a forum mod.
     *
     * @param int $forum_id
     * @return bool
     */
    public function isMod($forum_id) {
 		return ($this->isSuper() || in_array($forum_id, (array) $this->Session->read('Forum.moderates')));
    }

    /**
     * Return a user profile URL.
     *
     * @param array $user
     * @return string
     */
    public function profileUrl($user) {
        return $this->getUserRoute('profile', $user);
    }

    /**
     * Get the users timezone.
     *
     * @return string
     */
    public function timezone() {
        if ($timezone = $this->Session->read(AuthComponent::$sessionKey . '.' . Configure::read('User.fieldMap.timezone'))) {
            return $timezone;
        }

        return Configure::read('Forum.settings.defaultTimezone');
    }

    /**
     * Determine the topic icon state.
     *
     * @param array $topic
     * @param array $options
     * @return string
     */
    public function topicIcon($topic, array $options = array()) {
        $lastVisit = $this->Session->read('Forum.lastVisit');
        $readTopics = $this->Session->read('Forum.readTopics');
        $width = isset($options['width'])?$options['width']:null;
        $height = isset($options['height'])?$options['height']:null;

        if (!is_array($readTopics)) {
            $readTopics = array();
        }

        $icon = 'open';
        $tooltip = '';

        if (isset($topic['LastPost']['created'])) {
            $lastPost = $topic['LastPost']['created'];
        } else if (isset($topic['Topic']['created'])) {
            $lastPost = $topic['Topic']['created'];
        }

        if (!$topic['Topic']['status'] && $topic['Topic']['type'] != Topic::ANNOUNCEMENT) {
            $icon = 'closed';
        } else {
            if (isset($lastPost) && $lastPost > $lastVisit &&  !in_array($topic['Topic']['id'], $readTopics)) {
                $icon = 'new';
            } else if ($topic['Topic']['type'] == Topic::STICKY) {
                $icon = 'sticky';
            } else if ($topic['Topic']['type'] == Topic::IMPORTANT) {
                $icon = 'important';
            } else if ($topic['Topic']['type'] == Topic::ANNOUNCEMENT) {
                $icon = 'announcement';
            }
        }

        if ($icon === 'open' || $icon === 'new' || $icon === 'sticky') {
            if ($topic['Topic']['post_count'] >= Configure::read('Forum.settings.postsTillHotTopic')) {
                $icon .= '-hot';
            }
        }

        switch ($icon) {
            case 'open': $tooltip = __d('forum', 'No New Posts'); break;
            case 'open-hot': $tooltip = __d('forum', 'No New Posts'); break;
            case 'closed': $tooltip = __d('forum', 'Closed'); break;
            case 'new': $tooltip = __d('forum', 'New Posts'); break;
            case 'new-hot': $tooltip = __d('forum', 'New Posts'); break;
            case 'sticky': $tooltip = __d('forum', 'Sticky'); break;
            case 'sticky-hot': $tooltip = __d('forum', 'Sticky'); break;
            case 'important': $tooltip = __d('forum', 'Important'); break;
            case 'announcement': $tooltip = __d('forum', 'Announcement'); break;
        }

        $options = array('alt' => $tooltip, 'title' => $tooltip);
        if ($width != null) $options['width'] = $width;
        if ($height != null) $options['height'] = $height;
        return $this->Html->image(
        	'Forum.forum_icons/icon_' . $icon . '.png',
        	$options
       	);
    }

    /**
     * Get the amount of pages for a topic.
     *
     * @param array $topic
     * @return array
     */
    public function topicPages($topic) {
        if (empty($topic['page_count'])) {
            $postsPerPage = Configure::read('Forum.settings.postsPerPage');
            $topic['page_count'] = ($topic['post_count'] > $postsPerPage) ? ceil($topic['post_count'] / $postsPerPage) : 1;
        }

        $topicPages = array();

        for ($i = 1; $i <= $topic['page_count']; ++$i) {
            $topicPages[] = $this->Html->link($i, array('controller' => 'topics', 'action' => 'view', $topic['slug'], 'page' => $i));
        }

        if ($topic['page_count'] > Configure::read('Forum.settings.topicPagesTillTruncate')) {
            array_splice($topicPages, 2, $topic['page_count'] - 4, '...');
        }

        return $topicPages;
    }

    /**
     * Returns a user tag by given post count
     * @param int $count
     */
    public function getUserCountTag($count) {
    	foreach(Configure::read('User.tagCountMap') as $maxPosts => $tag) {
    		if ($maxPosts == 0) return $tag;
    		if ($count >= $maxPosts) return $tag;
    	}
    }

    /**
     * Get the type of topic.
     *
     * @param int $type
     * @return string
     */
    public function topicType($type = null) {
        if (!$type) {
            return null;
        }

        return '<b>' . $this->Utility->enum('Forum.Topic', 'type', $type) . '</b>';
    }

    /**
     * Modify Decoda before rendering the view.
     *
     * @param string $viewFile
     */
    public function beforeRender($viewFile) {
        $censored = Configure::read('Forum.settings.censoredWords');

        if (is_string($censored)) {
            $censored = array_map('trim', explode(',', $censored));
        }

        $decoda = $this->Decoda->getDecoda();
        $decoda->addFilter(new \Decoda\Filter\BlockFilter(array(
            'spoilerToggle' => "$('spoiler-content-{id}').toggle();"
        )));

        if ($censored) {
            $decoda->getHook('Censor')->blacklist($censored);
        }
    }

    /**
     * Time ago in words
     * @param unkown $dateString
     * @param array $options
     * @return string
     */
	public function timeAgoInWords($dateString, $options = array()) {
		// cut off is +1month actually, so everything from month is not required actually
		$search = array(
			' seconds', ' second',
			' minutes', ' minute',
			' hours', ' hour',
			' days', ' day',
			' weeks', ' week',
			' months', ' month',
			' years', ' year'
		);
		$replace = array(
			's', 's',
			'm', 'm',
			'h', 'h',
			'd', 'd',
			'w', 'w',
			'm', 'm',
			'y', 'y'
		);
		$time = CakeTime::timeAgoInWords($dateString, $options);
		$time = str_replace($search, $replace, $time);
		return $time;
	}

}