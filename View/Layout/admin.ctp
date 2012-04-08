<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Web App Theme</title>
	<?php echo $this->Html->css(array('base', 'themes/lighthouseapp/style')); ?>
	<?php echo $this->Html->script(array('jquery/jquery-1.3.min', 'jquery/jquery.scrollTo', 'jquery/jquery.localscroll')); ?>
	<script type="text/javascript" charset="utf-8">
		// <![CDATA[
		$(document).ready(function() {
			$.localScroll();
		});
		// ]]>
	</script>
	<?php echo $scripts_for_layout; ?>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1><?php echo $this->Html->link('Web App Theme', '/'); ?></h1>
			<div id="user-navigation">
				<ul class="wat-cf">
					<li><?php echo $this->Html->link(__('Profile', true), array(
						'plugin' => null, 'controller' => 'users', 'action' => 'profile')); ?></li>
					<li><?php echo $this->Html->link(__('Settings', true), array(
						'plugin' => 'settings', 'controller' => 'settings', 'action' => 'index')); ?></li>
					<li><?php echo $this->Html->link(__('Logout', true),
						array('plugin' => null, 'controller' => 'users', 'action' => 'logout'),
						array('class' => 'logout')); ?></li>
				</ul>
			</div>
			<div id="main-navigation">
				<ul class="wat-cf">
					<?php echo $this->element('admin/main_navigation'); ?>
				</ul>
			</div>
		</div>
		<div id="wrapper" class="wat-cf">
			<div id="main" <?php if (empty($sidebar_for_layout)) echo 'class="wide"';?>>
				<?php echo $content_for_layout; ?>

				<div id="footer">
					<div class="block">
						<p>Copyright &copy; 2010 Your Site.</p>
					</div>
				</div>
			</div>
			<?php if (!empty($sidebar_for_layout)) : ?>
				<div id="sidebar">
					<?php if (!empty($sidebar_for_layout['navigation'])) : ?>
						<?php echo $this->element('admin/sidebar_navigation'); ?>
					<?php endif; ?>
					<?php if (!empty($sidebar_for_layout['notice'])) : ?>
						<?php echo $this->element('admin/sidebar_notice_block'); ?>
					<?php endif; ?>
					<?php if (!empty($sidebar_for_layout['inner'])) : ?>
						<?php echo $this->element('admin/sidebar_inner_block'); ?>
					<?php endif; ?>
					<?php if (!empty($sidebar_for_layout['simple'])) : ?>
						<?php echo $this->element('admin/sidebar_simple_block'); ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</body>
</html>