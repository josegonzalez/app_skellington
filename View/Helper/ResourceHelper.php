<?php
class ResourceHelper extends Helper {

/**
 * Other helpers used by ResourceHelper
 *
 * @var array
 * @access public
 */
	var $helpers = array('Html');

/**
 * Instance of View class this helper sends output to.
 *
 * @var string
 * @access public
 */
	var $view = null;

/**
 * Array of sidebar blocks for use in the layout
 *
 * @var array
 * @access public
 */
	var $sidebar_for_layout = array();

	var $definition_list = 0;
	var $definition_list_class = ' class="altrow"';

/**
 * Set captured navigation and blocks for the view
 *
 * @return void
 * @author Jose Diaz-Gonzalez
 */
	function afterRender() {
		parent::afterRender();
		if (!$this->view) {
			$this->view = ClassRegistry::getObject('view');
		}

		$this->view->set('sidebar_for_layout', $this->sidebar_for_layout);
	}

/**
 * Begin capturing a block of HTML content
 *
 * @author Chris Your
 */
	function capture() {
		ob_start();
	}

/**
 * Set the captured block of HTML content to a $variable
 *
 * @param string $variable Assigned name
 * @return void
 * @author Chris Your
 */
	function for_layout($variable) {
		if (!$this->view) {
			$this->view = ClassRegistry::getObject('view');
		}
		$this->view->set("{$variable}_for_layout", ob_get_clean());
	}

/**
 * Adds a Navigation Link to the sidebar
 *
 * @param string $navigation Name of the navigation block to append link to
 * @param array $link Array of link options
 * @return void
 * @author Jose Diaz-Gonzalez
 **/
	function sidebar_navigation($navigation = null, $link = array()) {
		if (is_array($nagivation)) {
			$link = $navigation;
			$navigation = 'default';
		}

		$this->sidebar_for_layout['navigation'][$navigation][] = array(
			'title' => $link['title'],
			'url' => (isset($link['url'])) ? $link['url'] : array(),
			'options' => (isset($link['options'])) ? $link['options'] : array(),
		);
	}

/**
 * undocumented function
 *
 * @return void
 * @author Jose Diaz-Gonzalez
 **/
	function sidebar_inner_block($title, $content) {
		$this->sidebar_for_layout['inner'][] = array(
			'title' => $title,
			'content' => $content
		);
	}

/**
 * undocumented function
 *
 * @return void
 * @author Jose Diaz-Gonzalez
 **/
	function sidebar_notice_block($title, $content) {
		$this->sidebar_for_layout['notice'][] = array(
			'title' => $title,
			'content' => $content
		);
	}

/**
 * undocumented function
 *
 * @return void
 * @author Jose Diaz-Gonzalez
 **/
	function sidebar_simple_block($title, $content) {
		$this->sidebar_for_layout['simple'][] = array(
			'title' => $title,
			'content' => $content
		);
	}

	function term($term) {
		if ($this->definition_list % 2 == 0) {
			return "<dt{$this->definition_list_class}>{$term}</dt>";
		}
		return "<dt>{$term}</dt>";
	}

	function definition($definition, $url = array(), $options = array()) {
		$definition = $this->categorize_definition($definition, $options);
		if (!empty($url)) $definition = $this->Html->link($definition, $url, $options);

		if ($this->definition_list++ % 2 == 0) {
			return "<dd{$this->definition_list_class}>{$definition}&nbsp;</dd>";
		}
		return "<dd>{$definition}&nbsp;</dd>";
	}

	function categorize_definition($definition, $options) {
		if (!isset($options['type'])) return $definition;

		switch ($options['type']) {
			case 'boolean' :
				$definition = ($definition == 0) ? 'No' : 'Yes';
				break;
			case 'date' :
				break;
			case 'time' :
				break;
			case 'datetime' :
				break;
		}
		return $definition;
	}

/**
 * Truncates text.
 *
 * Cuts a string to the length of $length and replaces the last characters
 * with the ending if the text is longer than length.
 *
 * ### Options:
 *
 * - `ending` Will be used as Ending and appended to the trimmed string
 * - `exact` If false, $text will not be cut mid-word
 * - `html` If true, HTML tags would be handled correctly
 *
 * @param string  $text String to truncate.
 * @param integer $length Length of returned string, including ellipsis.
 * @param array $options An array of html attributes and options.
 * @return string Trimmed string.
 * @access public
 */
	function truncate($text, $length = 100, $options = array()) {
		$default = array(
			'ending' => '...', 'exact' => true, 'html' => false
		);
		$options = array_merge($default, $options);
		extract($options);

		if ($html) {
			if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
				return $text;
			}
			$totalLength = mb_strlen(strip_tags($ending));
			$openTags = array();
			$truncate = '';

			preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
			foreach ($tags as $tag) {
				if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
					if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
						array_unshift($openTags, $tag[2]);
					} else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
						$pos = array_search($closeTag[1], $openTags);
						if ($pos !== false) {
							array_splice($openTags, $pos, 1);
						}
					}
				}
				$truncate .= $tag[1];

				$contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
				if ($contentLength + $totalLength > $length) {
					$left = $length - $totalLength;
					$entitiesLength = 0;
					if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
						foreach ($entities[0] as $entity) {
							if ($entity[1] + 1 - $entitiesLength <= $left) {
								$left--;
								$entitiesLength += mb_strlen($entity[0]);
							} else {
								break;
							}
						}
					}

					$truncate .= mb_substr($tag[3], 0 , $left + $entitiesLength);
					break;
				} else {
					$truncate .= $tag[3];
					$totalLength += $contentLength;
				}
				if ($totalLength >= $length) {
					break;
				}
			}
		} else {
			if (mb_strlen($text) <= $length) {
				return $text;
			} else {
				$truncate = mb_substr($text, 0, $length - mb_strlen($ending));
			}
		}
		if (!$exact) {
			$spacepos = mb_strrpos($truncate, ' ');
			if (isset($spacepos)) {
				if ($html) {
					$bits = mb_substr($truncate, $spacepos);
					preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
					if (!empty($droppedTags)) {
						foreach ($droppedTags as $closingTag) {
							if (!in_array($closingTag[1], $openTags)) {
								array_unshift($openTags, $closingTag[1]);
							}
						}
					}
				}
				$truncate = mb_substr($truncate, 0, $spacepos);
			}
		}
		$truncate .= $ending;

		if ($html) {
			foreach ($openTags as $tag) {
				$truncate .= '</'.$tag.'>';
			}
		}

		return $truncate;
	}
}
?>