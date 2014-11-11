<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

use Decoda\Decoda;
use Decoda\Filter\AbstractFilter;

/**
 * Provides tags for ordered and unordered lists.
 */
class ListFilter extends AbstractFilter {

    const LIST_TYPE = '/^[-a-z]+$/i';

    /**
     * Supported tags.
     *
     * @type array
     */
    protected $_tags = array(
        'ol' => array(
            'htmlTag' => 'ol',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'lineBreaks' => Decoda::NL_REMOVE,
            'childrenWhitelist' => array('li', '*'),
            'onlyTags' => true,
            'attributes' => array(
                'default' => array(self::LIST_TYPE, 'type-{default}')
            ),
            'mapAttributes' => array(
                'default' => 'class'
            ),
            'htmlAttributes' => array(
                'class' => 'decoda-olist'
            )
        ),
        'ul' => array(
            'htmlTag' => 'ul',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'lineBreaks' => Decoda::NL_REMOVE,
            'childrenWhitelist' => array('li', '*'),
            'onlyTags' => true,
            'attributes' => array(
                'default' => array(self::LIST_TYPE, 'type-{default}')
            ),
            'mapAttributes' => array(
                'default' => 'class'
            ),
            'htmlAttributes' => array(
                'class' => 'decoda-list'
            )
        ),
        'li' => array(
            'htmlTag' => 'li',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'parent' => array('ol', 'ul')
        ),
        '*' => array(
            'htmlTag' => 'li',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'childrenBlacklist' => array('ol', 'ul', 'li'),
            'parent' => array('ol', 'ul')
        )
    );

}