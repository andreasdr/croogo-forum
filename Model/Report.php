<?php
App::uses('ForumAppModel', 'Forum.Model');

/**
 * Report model class
 * @author Andreas Drewke
 * @version $Id$
 */
class Report extends ForumAppModel {

    /**
     * Belongs to.
     *
     * @type array
     */
    public $belongsTo = array(
        'Topic' => array(
            'className' => 'Forum.Topic',
            'counterCache' => true
        ),
        'Post' => array(
            'className' => 'Forum.Post',
            'counterCache' => true
        ),
        'Reporter' => array(
            'className' => USER_MODEL,
            'foreignKey' => 'reporter_id'
        ),
        'Owner' => array(
            'className' => USER_MODEL,
            'foreignKey' => 'owner_id'
        )
    );

    /**
     * Validation.
     *
     * @type array
     */
    public $validations = array(
        'default' => array(
            'type' => array(
                'rule' => 'notEmpty'
            ),
			'comment' => array(
				'rule' => 'notEmpty'
			),
        )
    );

    /**
     * Create a report of a topic
     * @param int $type
     * @param int $topicId
     * @param string $comment
     * @param int $reporterId
     * @param int $ownerId
     */
    public function createReportTopic($type, $topicId, $comment, $reporterId, $ownerId) {
    	$this->create();
    	$this->save(
			array(
				'type' => $type,
				'topic_id' => $topicId,
				'comment' => $comment,
				'reporter_id' => $reporterId,
				'owner_id' => $ownerId
    		)
    	);
    }

    /**
     * Create a report of a post
     * @param int $type
     * @param int $postId
     * @param string $comment
     * @param int $reporterId
     * @param int $ownerId
     */
    public function createReportPost($type, $postId, $comment, $reporterId, $ownerId) {
    	$this->create();
    	$this->save(
			array(
				'type' => $type,
				'post_id' => $postId,
				'comment' => $comment,
				'reporter_id' => $reporterId,
				'owner_id' => $ownerId
			)
    	);
    }

}
