<?php

App::uses('CakeEventListener', 'Event');

/**
 * Forum Event Handler
 */
class ForumEventHandler extends Object implements CakeEventListener {

	/**
	 * implemented events
	 * @return array
	 */
	public function implementedEvents() {
		return array(
			'Controller.Users.beforeLogout' => array(
				'callable' => 'onBeforeLogout',
			),
			'Controller.Users.loginSuccessful' => array(
				'callable' => 'onLoginSuccessful',
			)
		);
	}

	/**
	 * onBeforeLogout
	 * @param CakeEvent $event
	 * @return void
	 */
	public function onBeforeLogout($event) {
		// remove forum session data
		$event->subject->Session->delete('Forum');
		$event->subject->Session->delete('Acl.isAdmin');
	}

	/**
	 * onBeforeLogout
	 * @param CakeEvent $event
	 * @return void
	 */
	public function onLoginSuccessful($event) {
		// set up initial forum session data
		if (AuthComponent::user()['Role']['alias'] == 'admin') {
			$event->subject->Session->write('Acl.isAdmin', true);
		}
	}

}
