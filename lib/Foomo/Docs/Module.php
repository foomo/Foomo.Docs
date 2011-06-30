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

namespace Foomo\Docs {

	use Foomo\Modules\ModuleBase;

	/**
	 * wiki for radact
	 *
	 * @link www.foomo.org
	 * @license www.gnu.org/licenses/lgpl.txt
	 * @author jan <jan@bestbytes.de>
	 */
	class Module extends \Foomo\Modules\ModuleBase implements \Foomo\Frontend\ToolboxInterface
	{
		//---------------------------------------------------------------------------------------------
		// ~ Constants
		//---------------------------------------------------------------------------------------------

		const NAME = 'Foomo.Docs';

		//---------------------------------------------------------------------------------------------
		// ~ Public static methods
		//---------------------------------------------------------------------------------------------

		public static function initializeModule()
		{
			// include my vendor sensei
			\Foomo\Utils::addIncludePaths(array(
					\Foomo\CORE_CONFIG_DIR_MODULES . DIRECTORY_SEPARATOR . self::NAME . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'sensei',
					\Foomo\CORE_CONFIG_DIR_MODULES . DIRECTORY_SEPARATOR . self::NAME . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'geshi',
					\Foomo\CORE_CONFIG_DIR_MODULES . DIRECTORY_SEPARATOR . self::NAME . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'wiki'
			));
		}

		public static function getDescription()
		{
			return 'a wiki for (self) documentation purposes';
		}

		public static function getResources()
		{
			return array(
				\Foomo\Modules\Resource\Module::getResource('Foomo', '0.1'),
				\Foomo\Modules\Resource\PearPackage::getResource('Text_Wiki')
			);
		}

		//---------------------------------------------------------------------------------------------
		// ~ Toolbox interface methods
		//---------------------------------------------------------------------------------------------

		/**
		 * @internal
		 * @return array
		 */
		public static function getMenu()
		{
			return array(
				\Foomo\Frontend\ToolboxConfig\MenuEntry::create('Root.Docs', 'Docs', self::NAME, 'Foomo.Docs'),
			);
		}
	}
}

namespace {
	/**
	 * this ensures, that the docs will work a little ;) even without gettext
	 */
	if(!function_exists('_')) {
		function _($message)
		{
			return $message;
		}
	}
}