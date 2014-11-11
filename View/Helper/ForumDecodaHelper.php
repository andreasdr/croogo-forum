<?php

App::uses('DecodaHelper', 'Utility.View/Helper');
App::uses('TextFilter', 'Forum.Lib/Decoda');
App::uses('ListFilter', 'Forum.Lib/Decoda');
App::uses('BlockFilter', 'Forum.Lib/Decoda');
App::uses('BlockFilterNoSpoiler', 'Forum.Lib/Decoda');
App::uses('ForumEmoticonHook', 'Forum.Lib/Decoda');

/**
 * Customized Decoda Helper
 * @author Andreas Drewke
 * @version $Id$
 */
class ForumDecodaHelper extends DecodaHelper {

	/**
	 * Set up decoda
	 */
	public function setupDecoda() {
		// remove decoda text filter
		$this->_decoda->removeFilter('Text');
		$this->_decoda->removeFilter('List');
		$this->_decoda->removeFilter('Block');
		$this->_decoda->addFilter(new TextFilter());
		$this->_decoda->addFilter(new ListFilter());
		$this->_decoda->addFilter(new BlockFilter());
		$this->_decoda->removeHook('Emoticon');
		$this->_decoda->addHook(new ForumEmoticonHook());
	}

	/**
	 * Set up decoda to not to render spoilers
	 */
	public function disableSpoiler() {
		$this->_decoda->removeFilter('Block');
		$this->_decoda->addFilter(new BlockFilterNoSpoiler());
	}

}