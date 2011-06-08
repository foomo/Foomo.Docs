<?php
if(!empty($_GET['f']) && !empty($_GET['d'])) {
	Foomo\Docs\Utils::streamWikiFile($_GET['f'], (boolean) $_GET['d'] );
}