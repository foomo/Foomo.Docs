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
 * renders documentations
 *
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 * @author jan <jan@bestbytes.de>
 */
class Model
{
	//---------------------------------------------------------------------------------------------
	// ~ Constants
	//---------------------------------------------------------------------------------------------

	const CODE_ERROR_INVALID_ROOT = 1;
	const CODE_ERROR_INVALID_LANGUAGE = 2;

	//---------------------------------------------------------------------------------------------
	// ~ Static variables
	//---------------------------------------------------------------------------------------------

	/**
	 * me for myself in a hacky, ugly and pragmetic dependency
	 *
	 * @internal
	 * @var Foomo\Docs\Frontend\Model
	 */
	public static $_inst;

	//---------------------------------------------------------------------------------------------
	// ~ Variables
	//---------------------------------------------------------------------------------------------

	/**
	 * @var string[]
	 */
	private $availableLangs;
	/**
	 * @var string
	 */
	public $docsRoot;
	/**
	 * currently rendered wiki file
	 *
	 * @var string
	 */
	private $currentWikiFile;
	/**
	 * current module
	 *
	 * @var string
	 */
	public $docsModule;
	/**
	 * did the last docs creation succeed
	 * @var boolean
	 */
	public $docsCreationSuccess;
	/**
	 * error that occurred at creating docs
	 *
	 * @var string
	 */
	public $docsCreationError;

	//---------------------------------------------------------------------------------------------
	// ~ Constructor
	//---------------------------------------------------------------------------------------------

	/**
	 *
	 */
	public function __construct()
	{
		self::$_inst = $this;
	}

	//---------------------------------------------------------------------------------------------
	// ~ Public methods
	//---------------------------------------------------------------------------------------------

	/**
	 * @param string $file
	 */
	public function setCurrentWikiFile($file)
	{
		$this->currentWikiFile = $file;
	}

	/**
	 * Get an Url for a file lying relative to a wiki file
	 *
	 * @param string $relativeFileName relative file name of the file
	 * @param boolean $offerAsDownload if set to true the file will be offered as a download
	 *
	 * @return string the file url /r/modules/docs/wikiFile.php?f=...&d=...
	 */
	public function getWikiFileUrl($relativeFileName, $offerAsDownload=false, $width=null, $height=null)
	{
		$fileName = realpath(dirname($this->currentWikiFile) . DIRECTORY_SEPARATOR . $relativeFileName);
		if(!file_exists($fileName)) {
			trigger_error('file to linked can not be found ' . dirname($this->currentWikiFile) . DIRECTORY_SEPARATOR . $relativeFileName, E_USER_WARNING);
			return '';
		} else {
			$fileName = substr($fileName, strlen(realpath(\Foomo\CORE_CONFIG_DIR_MODULES))+1);
			$parts = explode(DIRECTORY_SEPARATOR, $fileName);
			unset($parts[1]);
			$fileName = implode(DIRECTORY_SEPARATOR, $parts);
			return \Foomo\Utils::getServerUrl() . \Foomo\ROOT_HTTP . '/modules/' . \Foomo\Docs\Module::NAME . '/wikiFile.php?f=' . urlencode($fileName) . '&d=' . ($offerAsDownload?'true':'false');
		}
	}

	/**
	 *
	 * @param string $relativeFileName
	 * @return string
	 */
	public function getRelativeFile($relativeFileName)
	{
		$fileName = dirname($this->currentWikiFile) . DIRECTORY_SEPARATOR . $relativeFileName;
		return file_exists($fileName)?realpath($fileName):'';
	}

	/**
	 * set up a html docs renderer
	 *
	 * @param string $docsRoot folder where the docs are
	 */
	public function setDocsRoot($docsRoot)
	{
		if (!is_dir($docsRoot)) throw new \InvalidArgumentException('invalid root folder', self::CODE_ERROR_INVALID_ROOT);
		$this->docsRoot = $docsRoot;
	}
	/**
	 * set the docs module
	 *
	 * @param string $moduleName
	 */
	public function setDocsModule($moduleName)
	{
		if(in_array($moduleName, \Foomo\Modules\Manager::getEnabledModules())) {
			$modDocsFolder = \Foomo\CORE_CONFIG_DIR_MODULES . DIRECTORY_SEPARATOR . $moduleName . DIRECTORY_SEPARATOR . 'docs';
			if (is_dir($modDocsFolder)) {
				$this->setDocsRoot($modDocsFolder);
				$this->docsModule = $moduleName;
			}
		}
	}

	/**
	 * in which versions is the documentaion available
	 *
	 * @return array
	 */
	public function getAvailableLanguages()
	{
		if(!isset($this->availableLangs)) {
			$this->availableLangs = array();
			$directoryIterator = new \DirectoryIterator($this->docsRoot);
			for($directoryIterator->rewind();$directoryIterator->valid();$directoryIterator->next()) {
				/* @var $current SplFileInfo */
				$current = $directoryIterator->current();
				if($current->isFile() && strpos('.txt', $current->getFilename()) !== null && substr($current->getFilename(), strlen($current->getFilename()) - strlen('.txt')) == '.txt') {
					$this->availableLangs[] = substr($current->getFilename(), 0, strlen($current->getFilename()) - strlen('.txt'));
				}
			}
		}
		return $this->availableLangs;
	}

	/**
	 * get the table of contents as html
	 *
	 * @param string $language language of the docs
	 */
	public function getToc($language='en')
	{
		return \Foomo\Cache\Proxy::call($this, 'cachedGetToc', array($language, $this->getAvailableLanguages(), $this->docsRoot));
		//return self::cachedGetToc($language, $this->getAvailableLanguages(), $this->docsRoot);
	}
	/**
	 * @Foomo\Cache\CacheResourceDescription
	 * 
	 * @param string $language
	 * @param array $availableLanguages
	 * @param string $docsRoot
	 * 
	 * @return string
	 */
	public function cachedGetToc($language, $availableLanguages, $docsRoot)
	{
		return self::getRenderer($language, $availableLanguages, $docsRoot)->renderToc();
	}

	/**
	 * get the content as html
	 *
	 * @param string $language language of the docs
	 */
	public function getContents($language='en')
	{
		return \Foomo\Cache\Proxy::call($this, 'cachedGetContents', array($language, $this->getAvailableLanguages(), $this->docsRoot));		
		//return self::cachedGetContents($language, $this->getAvailableLanguages(), $this->docsRoot);
	}
	/**
	 * @Foomo\Cache\CacheResourceDescription
	 * 
	 * @param string $language
	 * @param array $availableLanguages
	 * @param string $docsRoot
	 * 
	 * @return string
	 */
	public function cachedGetContents($language, $availableLanguages, $docsRoot)
	{
		return self::getRenderer($language, $availableLanguages, $docsRoot)->render();
	}
	//---------------------------------------------------------------------------------------------
	// ~ Private methods
	//---------------------------------------------------------------------------------------------

	/**
	 * get the renderer
	 *
	 * @param string $language
	 * @return Sensei_Doc_Renderer_Xhtml
	 */
	private function getRenderer($language, $availableLanguages, $docsRoot)
	{
		if(in_array($language, $availableLanguages)) {
			$toc = new \Sensei_Doc_Toc($docsRoot . '/' . $language . '.txt');
			$renderer = new \Sensei_Doc_Renderer_Xhtml($toc);
			return $renderer;
		} else {
			throw new \Exception('invalid language', self::CODE_ERROR_INVALID_LANGUAGE);
		}
	}
}
