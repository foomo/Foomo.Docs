<?php

namespace Foomo\Docs;

/**
 * docs utils
 */
class Utils {
	/**
	 * create new docs
	 *
	 * @param string $moduleName
	 * @throws Exception
	 */
	public static function createModuleDocs($moduleName, $language = 'en')
	{
		if(!in_array($moduleName, \Foomo\Modules\Manager::getEnabledModules())) {
			throw new \Exception('Invalid module ' . $moduleName . ' is not enabled or does not exist');
		}
		$docsFolder = \Foomo\CORE_CONFIG_DIR_MODULES . DIRECTORY_SEPARATOR . $moduleName . DIRECTORY_SEPARATOR . 'docs';
		$docsLangFolder = $docsFolder . DIRECTORY_SEPARATOR . $language;
		if(is_dir($docsLangFolder)) {
			throw new \Exception('There is already an existing docs folder for your language ' . $language);
		}
		if(!is_writable(dirname($docsFolder))) {
			throw new \Exception('Please check your rights for ' . dirname($docsFolder) . ' the folder is not writable');
		}
		$folders = array(
			$docsFolder,
			$docsLangFolder
		);
		foreach($folders as $folder) {
			mkdir($folder);
		}
		$defaultDocs = array(
			'en' => array(
				'Abstract' => 'Describe what your module does',
				'Installation' => 'How to install it',
				'Configuration' => 'How to configure it',
				'Cookbook' => 'How to cook with it'
			)
		);
		$indexFile = $docsFolder . DIRECTORY_SEPARATOR . $language;
		if(!isset($defaultDocs[$language])) {
			$indexArray = $defaultDocs['en'];
		} else {
			$indexArray = $defaultDocs[$language];
		}
		self::writeIndexFile($docsFolder . DIRECTORY_SEPARATOR . $language . '.txt', $indexArray);
		self::writeDefaultPages($docsLangFolder, $indexArray);
	}
	/**
	 * write the default documentaion pages in to the corresponding lang folder
	 *
	 * @param string $docsLangFolder
	 * @param array $indexArray entries 'title' => 'default content', ...
	 */
	private static function writeDefaultPages($docsLangFolder, $indexArray)
	{
		foreach($indexArray as $title => $contents) {
			$docsFile = $docsLangFolder . DIRECTORY_SEPARATOR . str_replace(' ', '_',strtolower($title)) . '.txt';
			if(!file_exists($docsFile)) {
				file_put_contents($docsFile, $contents);
			} else {
				trigger_error('can not create default documentation in ' . $docsFile, E_USER_WARNING);
			}
		}
	}
	/**
	 * generate the default index
	 *
	 * @param string $file name of the file to write to
	 * @param array $indexArray
	 */
	private static function writeIndexFile($file, $indexArray)
	{
		$contents = '';
		foreach(array_keys($indexArray) as $index) {
			$contents .= '+ ' . strtolower($index) . PHP_EOL;
		}
		if(!file_exists($file)) {
			file_put_contents($file, $contents);
		} else {
			trigger_error('can not write the index file to ' . $file, E_USER_ERROR);
		}
	}
	public static function streamWikiFile($magicFileName, $offerAsDownload)
	{
		$parts = explode(DIRECTORY_SEPARATOR, $magicFileName);
		$module = $parts[0];
		unset($parts[0]);
		$fileName = realpath(\Foomo\CORE_CONFIG_DIR_MODULES) . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $parts);
		if(realpath($fileName) == $fileName) {
			if($offerAsDownload === true) {
				\Foomo\Utils::streamFile($fileName, basename($fileName), null, true);
			} else {
				\Foomo\Utils::streamFile($fileName, null, null, false);
			}
		} else {
			trigger_error('someone is trying to screw with us - incoming file name ' . $magicFileName, E_USER_ERROR);
		}
	}
}