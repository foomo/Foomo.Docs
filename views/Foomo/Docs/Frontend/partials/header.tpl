<? /* @var $view Foomo\MVC\View */ 
 Foomo\HTMLDocument::getInstance()->addStylesheets(array(
	Foomo\ROOT_HTTP . '/modules/' . Foomo\Docs\Module::NAME . '/css/module.css'
 ));
?>
<h1><?= $view->link(Foomo\Docs\Module::NAME) ?></h1>