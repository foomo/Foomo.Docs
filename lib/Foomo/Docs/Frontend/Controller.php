<?php

namespace Foomo\Docs\Frontend;

/**
 * controller of the toolbox docs function
 *
 */
class Controller {
	/**
	 * my model
	 * 
	 * @var Foomo\Docs\Frontend\Model
	 */
	public $model;
	/**
	 * if the module does not have any docs yet, the will be created
	 *
	 * @param string $moduleName
	 * 
	 * @return string
	 */
	public function actionCreateModuleDocs($moduleName)
	{
		$language = 'en';
		$this->model->docsModule = $moduleName;
		try {
			\Foomo\Docs\Utils::createModuleDocs($moduleName, $language);
			$this->model->docsCreationSuccess = true;
		} catch(Exception $e) {
			$this->model->docsCreationSuccess = false;
			$this->model->docsCreationError = $e->getMessage();
		}
	}
	/**
	 * show docs for a module
	 *
	 * @param string $module
	 * 
	 * @return string
	 */
	public function actionShowModuleDocs($moduleName = 'core')
	{
		$this->model->setDocsModule($moduleName);
	}
	public function actionDefault()
	{
	}
}