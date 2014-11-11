<?php
App::uses('ForumAppController', 'Forum.Controller');
/**
 * Moderators Controller
 *
 * @property Moderator $ForumModerator
 * @property PaginatorComponent $Paginator
 */
class ModeratorController extends ForumAppController {

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator');

	/**
	 * admin_index method
	 *
	 * @return void
	 */
	public function admin_index() {
		$this->Moderator->recursive = 0;
		$this->set('moderators', $this->paginate());
	}

	/**
	 * admin_view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function admin_view($id = null) {
		$this->Moderator->recursive = 0;
		if (!$this->Moderator->exists($id)) {
			throw new NotFoundException(__d('croogo', 'Invalid moderator'));
		}
		$options = array('conditions' => array('Moderator.' . $this->Moderator->primaryKey => $id));
		$this->set('moderator', $this->Moderator->find('first', $options));
	}

	/**
	 * admin_add method
	 *
	 * @return void
	 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Moderator->create();
			if ($this->Moderator->save($this->request->data)) {
				$this->Session->setFlash(__d('croogo', 'The moderator has been saved'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('croogo', 'The moderator could not be saved. Please, try again.'), 'default', array('class' => 'error'));
			}
		}
		$forums = $this->Moderator->Forum->find('list');
		$users = $this->Moderator->User->find('list');
		$this->set(compact('forums', 'users'));
	}

	/**
	 * admin_edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function admin_edit($id = null) {
		$this->Moderator->recursive = 0;
		if (!$this->Moderator->exists($id)) {
			throw new NotFoundException(__d('croogo', 'Invalid moderator'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Moderator->save($this->request->data)) {
				$this->Session->setFlash(__d('croogo', 'The moderator has been saved'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('croogo', 'The moderator could not be saved. Please, try again.'), 'default', array('class' => 'error'));
			}
		} else {
			$options = array('conditions' => array('Moderator.' . $this->Moderator->primaryKey => $id));
			$this->request->data = $this->Moderator->find('first', $options);
		}
		$forums = $this->Moderator->Forum->find('list');
		$users = $this->Moderator->User->find('list');
		$this->set(compact('forums', 'users'));
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
		$this->Moderator->id = $id;
		if (!$this->Moderator->exists()) {
			throw new NotFoundException(__d('croogo', 'Invalid moderator'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Moderator->delete()) {
			$this->Session->setFlash(__d('croogo', 'Moderator deleted'), 'default', array('class' => 'success'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__d('croogo', 'Moderator was not deleted'), 'default', array('class' => 'error'));
		$this->redirect(array('action' => 'index'));
	}}
