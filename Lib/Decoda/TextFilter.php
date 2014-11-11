<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

use Decoda\Decoda;
use Decoda\Filter\AbstractFilter;

/**
 * Provides tags for text and font styling.
 */
class TextFilter extends AbstractFilter {

    /**
     * Supported tags.
     *
     * @type array
     */
    protected $_tags = array(
        'font' => array(
            'htmlTag' => 'span',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE,
            'escapeAttributes' => false,
            'attributes' => array(
                'default' => array('/^[a-z0-9\-\s,\.\']+$/i', 'font-family: {default}')
            ),
            'mapAttributes' => array(
                'default' => 'style'
            )
        ),
        'size' => array(
            'htmlTag' => 'span',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE,
            'attributes' => array(
                'default' => array('/^[1-7]{1}$/', '{default}'),
            ),
            'mapAttributes' => array(
                'default' => 'style'
            )
        ),
        'color' => array(
            'htmlTag' => 'span',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE,
            'attributes' => array(
                'default' => array('/^(?:#[0-9a-f]{3,6}|[a-z]+)$/i', 'color: {default}'),
            ),
            'mapAttributes' => array(
                'default' => 'style'
            )
        ),
        'h1' => array(
            'htmlTag' => 'h1',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_INLINE
        ),
        'h2' => array(
            'htmlTag' => 'h2',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_INLINE
        ),
        'h3' => array(
            'htmlTag' => 'h3',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_INLINE
        ),
        'h4' => array(
            'htmlTag' => 'h4',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_INLINE
        ),
        'h5' => array(
            'htmlTag' => 'h5',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_INLINE
        ),
        'h6' => array(
            'htmlTag' => 'h6',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_INLINE
        )
    );

    /**
     * Use the content as the image source.
     *
     * @param array $tag
     * @param string $content
     * @return string
     */
    public function parse(array $tag, $content) {
    	if (isset($tag['tag']) && $tag['tag'] == 'size') {
    		switch((int)$tag['attributes']['style']) {
    			case(1):
    				$tag['attributes']['style'] = 'font-size: xx-small';
    				break;
    			case(2):
    				$tag['attributes']['style'] = 'font-size: small';
    				break;
    			case(3):
    				$tag['attributes']['style'] = 'font-size: medium';
    				break;
				case(4):
					$tag['attributes']['style'] = 'font-size: large';
    				break;
				case(5):
					$tag['attributes']['style'] = 'font-size: x-large';
    				break;
				case(6):
					$tag['attributes']['style'] = 'font-size: xx-large';
    				break;
				case(7):
					$tag['attributes']['style'] = 'font-size: 300%';
    				break;
				default:
					// no op
    		}
    	}

    	//
    	return parent::parse($tag, $content);
    }

}
