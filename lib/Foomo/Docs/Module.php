<?php

namespace Foomo\Docs {

	use Foomo\Modules\ModuleBase;

	/**
	 * wiki for radact
	 */
	class Module extends ModuleBase {
		const NAME = 'Foomo.Docs';

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