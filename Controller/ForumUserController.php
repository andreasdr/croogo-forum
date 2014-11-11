<?php
App::uses('ForumAppController', 'Forum.Controller');

/**
 * User Controller
 * @author Andreas Drewke
 *
 */
class ForumUserController extends ForumAppController {

	public $uses = array('Users.User');

    /**
     * View a user
     */
    public function view() {
    	$this->request->data = $this->User->read(null, $this->request['pass']['0']);
    }
   
}
