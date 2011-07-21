<?= $view->partial('header'); ?>

<? if (is_null($model->docsRoot)): ?>


	<?php
	/* @var $view Foomo\MVC\View */
	$enabledModuleNames = Foomo\Modules\Manager::getEnabledModules();
	$documented = array();
	$undocumented = array();
	foreach($enabledModuleNames as $enabledModuleName) {
		$modDocsFolder = \Foomo\CORE_CONFIG_DIR_MODULES . DIRECTORY_SEPARATOR . $enabledModuleName .  DIRECTORY_SEPARATOR . 'docs';
		if(is_dir($modDocsFolder)) {
			$documented[] =  '
			<div class="greyBox">
				<div class="innerBox" style="margin: 5px 10px"><b>'. $view->link($enabledModuleName, 'showModuleDocs', array($enabledModuleName), array('title' => 'show docs for module' . $enabledModuleName)) . '</b></div>
			</div>';
		} else {
			$undocumented[] =  '
			<div class="greyBox">
				<div class="innerBox" style="margin: 5px 10px"><b>' . $view->link($enabledModuleName, 'createModuleDocs', array($enabledModuleName), array('title' => 'create docs for module' . $enabledModuleName)) . '</b></div>
			</div>';
		}
	}
	?>

	<? if(count($documented) > 0): ?>

		<h2>Documented modules</h2>

		<?= implode(PHP_EOL, $documented) ?>


	<? endif; ?>

	<? if(count($undocumented) > 0): ?>
		<br>
		<br>
		<h2>Not documented modules</h2>
		click to create a documentation skeleton<br>

		<?= implode(PHP_EOL, $undocumented) ?>

	<? endif; ?>
<? else: ?>
	<?= $view->partial('content'); ?>
<? endif; ?>


