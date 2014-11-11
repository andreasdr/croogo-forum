<?php
/**
 * @copyright   2006-2013, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/admin/blob/master/license.md
 * @link        http://milesj.me/code/cakephp/admin
 */

App::uses('ClassRegistry', 'Utility');
App::uses('Sanitize', 'Utility');

// load required plugins
CakePlugin::load('Utility', array('bootstrap' => true, 'routes' => true));

// force timestamps to assets
// Optional constants before plugin loading
define('USER_MODEL', 'User'); // Name of the user model (supports plugin syntax)
define('FORUM_PREFIX', 'forum_'); // Table prefix, must not be empty
define('FORUM_DATABASE', 'default'); // Database config to create tables in

/**
 * Forum critical constants.
 */
define('FORUM_PLUGIN', dirname(__DIR__) . '/');

// Table Prefix
if (!defined('FORUM_PREFIX')) {
    define('FORUM_PREFIX', 'forum_');
}

// Database config
if (!defined('FORUM_DATABASE')) {
    define('FORUM_DATABASE', 'default');
}

/**
 * A map of user fields that are used within this plugin. If your users table has a different naming scheme
 * for the username, email, status, etc fields, you can define their replacement here.
 */
if (!Configure::check('User.fieldMap')) {
	Configure::write('User.fieldMap', array(
		'id'    => 	'id',
		'username'    => 'username',
		'password'    => 'password',
		'email'        => 'email',
		'status'    => 'status',
		'avatar'    => 'image',
		'locale'    => 'locale',
		'timezone'    => 'timezone',
		'website'    	=> 	'website',
		'location'    	=> 	'location',
		'bio'    		=> 	'bio',
		'signature'   	=> 	'signature',
		'lastLogin'   	=> 'lastLogin',
		'totalTopics'    => 'topic_count',
		'totalPosts'    => 'post_count',
	));
}

/**
 * Model methods to execute as process callbacks.
 * The callback method accepts a record ID as the 1st argument.
 * The titles are passed through localization and will also replace %s with the model name.
 *
 * @link http://milesj.me/code/cakephp/admin#model-and-behavior-callbacks
 */
Configure::write('Admin.modelCallbacks', array());

/**
 * Provide overrides for CRUD actions.
 * This allows one to hook into the system and provide their own controller action logic.
 *
 * @link http://milesj.me/code/cakephp/admin#action-overrides
 */
Configure::write('Admin.actionOverrides', array());

/**
 * Current version.
 */
Configure::write('Forum.version', file_get_contents(dirname(__DIR__) . '/version.md'));

/**
 * Customizable layout; defaults to the plugin layout.
 */
Configure::write('Forum.viewLayout', 'default');

/**
 * List of settings that alter the forum system.
 */
Configure::write('Forum.settings', array(
    'name' => __d('forum', 'Forum'),
    'email' => 'forum@cakephp.org',
    'url' => 'http://milesj.me/code/cakephp/forum',
    'titleSeparator' => ' - ',

    // Topics
    'topicsPerPage' => 20,
    'topicsPerHour' => 3,
    'topicFloodInterval' => 300,
    'topicPagesTillTruncate' => 10,
    'topicDaysTillAutolock' => 21,
    'excerptLength' => 500,

    // Posts
    'postAutoRate' => true,
    'postsPerPage' => 15,
    'postsPerHour' => 15,
    'postsTillHotTopic' => 35,
    'postFloodInterval' => 60,

    // Subscriptions
    'enableTopicSubscriptions' => true,
    'enableForumSubscriptions' => true,
    'autoSubscribeSelf' => true,
    'subscriptionTemplate' => '',

    // Ratings
    'enablePostRating' => true,
    'showRatingScore' => true,
    'ratingBuryThreshold' => -25,
    'rateUpPoints' => 1,
    'rateDownPoints' => 1,

    // Misc
    'whosOnlineInterval' => '-15 minutes',
    'enableQuickReply' => true,
    'enableGravatar' => true,
    'censoredWords' => array(),
    'defaultLocale' => 'eng',
    'defaultTimezone' => '-8',
));

/**
 * Add model callbacks for admin panel.
 */
Configure::write('Admin.modelCallbacks', 
	array(
	    'Forum.Forum' => array(
	        'open' => 'Open %s',
	        'close' => 'Close %s'
	    ),
	    'Forum.Topic' => array(
	        'open' => 'Open %s',
	        'close' => 'Close %s',
	        'sticky' => 'Sticky %s',
	        'unsticky' => 'Unsticky %s'
	    )
	) +
	Configure::read('Admin.modelCallbacks')
);

/**
 * Add overrides for admin CRUD actions.
 */
Configure::write('Admin.actionOverrides',
	array(
    	'Forum.Forum' => array(
        	'delete' => array('plugin' => 'forum', 'controller' => 'stations', 'action' => 'admin_delete')
    	)
	) +
	Configure::read('Admin.actionOverrides')
);

/**
 * Custom routes
 */
Configure::write('User.routes', array(
	'login' => array('plugin' => 'rogue_assembly', 'admin' => false, 'controller' => 'user', 'action' => 'login'),
	'logout' => array('plugin' => 'rogue_assembly', 'admin' => false, 'controller' => 'user', 'action' => 'logout'),
	'signup' => array('plugin' => 'rogue_assembly', 'admin' => false, 'controller' => 'user', 'action' => 'signup'),
	'forgotPass' => array('plugin' => 'rogue_assembly', 'admin' => false, 'controller' => 'user', 'action' => 'forgotPass'),
	'settings' => array('plugin' => 'rogue_assembly', 'admin' => false, 'controller' => 'user', 'action' => 'edit', '{id}'),
	'profile' => array('plugin' => 'forum', 'admin' => false, 'controller' => 'forum_user', 'action' => 'view', '{id}'),
));

// decoda config
Configure::write('Decoda.config',
	array(
		'strictMode' => false
	) +
	Configure::read('Decoda.config')
);

/**
 * Admin menu (navigation)
 */
CroogoNav::add('extensions.children.forum', array(
	'title' => 'Forum',
	'url' => '#',
	'children' => array(
		'forum' => array(
			'title' => 'Forums',
			'url' => array(
				'admin' => true,
				'plugin' => 'forum',
				'controller' => 'forum',
				'action' => 'admin_index',
			),
		),
		'moderator' => array(
			'title' => 'Moderators',
			'url' => array(
				'admin' => true,
				'plugin' => 'forum',
				'controller' => 'moderator',
				'action' => 'admin_index',
			),
		),
		'reports' => array(
			'title' => 'Reports',
			'url' => array(
				'admin' => true,
				'plugin' => 'forum',
				'controller' => 'reports',
				'action' => 'admin_index',
			),
		)
	),
));

// this maps a user post count to a tag name
if (!Configure::check('User.tagCountMap')) {
	Configure::write('User.tagCountMap', array(
		10001 => 'Too Much Free Time',
		5001 => 'Prophet',
		3001 => 'Oracle',
		2001 => 'Entity',
		1501 => 'Herald',
		1001 => 'Respected',
		701 => 'Pretty Talkative',
		401 => 'Well Known',
		201 => 'Growing',
		101 => 'Well Established',
		66 => 'Established',
		36 => 'Setting Up',
		6 => 'Swimming',
		0 => 'Just Arrived'
	));
}