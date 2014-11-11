<?php
use Decoda\Decoda;
use Decoda\Loader\FileLoader;
use Decoda\Hook\AbstractHook;

/**
 * Converts smiley faces into emoticon images.
 * @author Andreas Drewke
 * @version $Id$
 */
class ForumEmoticonHook extends AbstractHook {

	private $smileys2Emoticons = array(
		':)' => 'smile',
		':angel:' => 'angel',
		':angry:' => 'angry',
		'8-)' => 'cool',
		":'(" => 'cwy',
		':ermm:' => 'ermm',
		':D' => 'grin',
		'<3' => 'heart',
		'&lt;3' => 'heart',
		':(' => 'sad',
		':O' => 'shocked',
		':P' => 'tongue',
		';)' => 'wink',
		':alien:' => 'alien',
		':blink:' => 'blink',
		':blush:' => 'blush',
		':cheerful:' => 'cheerful',
		':devil:' => 'devil',
		':dizzy:' => 'dizzy',
		':getlost:' => 'getlost',
		':happy:' => 'happy',
		':kissing:' => 'kissing',
		':ninja:' => 'ninja',
		':pinch:' => 'pinch',
		':pouty:' => 'pouty',
		':sick:' => 'sick',
		':sideways:' => 'sideways',
		':silly:' => 'silly',
		':sleeping:' => 'sleeping',
		':unsure:' => 'unsure',
		':woot:' => 'w00t',
		':wassat:' => 'wassat',
		':whistling:' => 'whistling',
		':love:' => 'wub'
	);

	/**
	 * @var string[]
	 */
	private $smileysSearch;

	/**
	 * @var string[]
	 */
	private $smileysReplace;

	/**
	 * Public constructor
	 */
	public function __construct() {
		$this->smileysSearch = array();
		$this->smileysReplace = array();
		foreach ($this->smileys2Emoticons as $smiley => $emoticon) {
			$this->smileysSearch[] = $smiley;
			$this->smileysReplace[] = '<img src="/forum/img/emoticons/' . $emoticon . '.png" alt="" />';
		}
	}

	/**
	 * Start up
	 */
	public function startup() {
		// no op
	}

	/**
	 * Parse out the emoticons and replace with images.
	 *
	 * @param string $content
	 * @return string
	 */
	public function beforeParse($content) {
		return str_replace($this->smileysSearch, $this->smileysReplace, $content);
	}

}
