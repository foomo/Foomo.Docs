<?php

/*
 * This file is part of the foomo Opensource Framework.
 *
 * The foomo Opensource Framework is free software: you can redistribute it
 * and/or modify it under the terms of the GNU Lesser General Public License as
 * published  by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * The foomo Opensource Framework is distributed in the hope that it will
 * be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along with
 * the foomo Opensource Framework. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Foomo\Docs\Frontend;

/**
 * controller of the toolbox docs function
 *
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 * @author jan <jan@bestbytes.de>
 */
class Controller
{
	//---------------------------------------------------------------------------------------------
	// ~ Variables
	//---------------------------------------------------------------------------------------------

	/**
	 * my model
	 *
	 * @var Foomo\Docs\Frontend\Model
	 */
	public $model;

	//---------------------------------------------------------------------------------------------
	// ~ Action methods
	//---------------------------------------------------------------------------------------------

	/**
	 *
	 */
	public function actionDefault()
	{
	}

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
	public function actionShowModuleDocs($moduleName='core')
	{
		$this->model->setDocsModule($moduleName);
	}
}