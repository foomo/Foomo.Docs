<?= $view->partial('header'); ?>
<?php
/* @var $view Foomo\MVC\View */
$enabledModuleNames = Foomo\Modules\Manager::getEnabledModules();
$documented = array();
$undocumented = array();
foreach($enabledModuleNames as $enabledModuleName) {
	$modDocsFolder = \Foomo\CORE_CONFIG_DIR_MODULES . DIRECTORY_SEPARATOR . $enabledModuleName .  DIRECTORY_SEPARATOR . 'docs';
	if(is_dir($modDocsFolder)) {
		$documented[] =  '<li>'. $view->link($enabledModuleName, 'showModuleDocs', array($enabledModuleName), 'show docs for module' . $enabledModuleName) . '</li>';
	} else {
		$undocumented[] =  '<li>' . $view->link($enabledModuleName, 'createModuleDocs', array($enabledModuleName), 'create docs for module' . $enabledModuleName) . '</a></li>';
	}
}
?>

<h2>TOC - modules</h2>
<ul>
	<?= implode(PHP_EOL, $documented) ?>
</ul>
<? if(count($undocumented) > 0): ?>
	<hr>
	<h2>not documented modules</h2>
	<p>click to create a documentation skeleton</p>
	<ul>
		<?= implode(PHP_EOL, $undocumented) ?>
	</ul>
<? endif; ?>