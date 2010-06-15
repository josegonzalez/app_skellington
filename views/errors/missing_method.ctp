<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.templates.errors
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<h2><?php printf(__('Missing Method in %s', true), $className); ?></h2>
<p class="error">
	<strong><?php __('Error'); ?>: </strong>
	<?php printf(__('The method %1$s is not defined in %2$s', true), '<em>' . $methodName . '</em>', '<em>' . $className . '</em>'); ?>
</p>
<p class="error">
	<strong><?php __('Error'); ?>: </strong>
	<?php printf(__('Create %1$s%2$s in file: %3$s.', true), '<em>' . $className . '::</em>', '<em>' . $methodName . '()</em>', APP_DIR . DS . $path . DS . Inflector::underscore($className) . '.php'); ?>
</p>


<pre>
&lt;?php
class <?php echo $className; ?> extends <?php echo $parentClass; ?> {

	var $name = '<?php echo $className; ?>';

<strong>
	function <?php echo $methodName; ?>(<?php echo $parameters; ?>) {

	}
</strong>
}
?&gt;
</pre>