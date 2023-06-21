<?php

namespace app\library;

use Exception;

class View
{
	public $layout = 'layout';

	protected $ViewEnabled = true;
	protected $LayoutEnabled = true;
	protected $pageTitle = '';
	protected $viewContent = "";


	public function ViewContent()
	{
		return $this->viewContent;
	}

	protected function getLayout()
	{
		return $this->layout;
	}

	public function message(array $message)
	{
		exit(json_encode($message));
	}

	protected function renderView(string $viewScript)
	{
		ob_start();

		include(dirname(__FILE__, 3) . '\\app\\views\\' .  $viewScript);

		$this->viewContent = ob_get_clean();
	}

	public function render(string $controller, string $viewScript)
	{

		if ($viewScript && $this->ViewEnabled) {
			$this->renderView($viewScript);
		}
		if ($this->isLayoutDisabled()) {
			echo $this->viewContent;
		} else if ($controller == 'admin') {
			include(dirname(__FILE__, 3) . '\\app\\views\\admin\\layout.php');
		} else {
			include(dirname(__FILE__, 3) . '\\app\\views\\site\\layout.php');
		}
	}

	public function getComponent(string $name = null)
	{
		$name   = (string) $name;
		if ('' !== $name) {
			$templates = dirname(__FILE__, 3) . '\\app\\views\\partials\\site\\' . $name . '.php';
		}
		if (file_exists($templates)) {
			return require($templates);
		}
	}

	public function setPageTitle(string $pageTitle)
	{
		$this->pageTitle  = $pageTitle;
	}

	public function getTitlePage($sep = false)
	{
		if ($sep && !is_null($this->pageTitle)) {
			return $this->pageTitle . ' - ';
		} else {
			return $this->pageTitle;
		}
	}

	public function disableLayout()
	{
		$this->LayoutEnabled = false;
	}

	public function disableView()
	{
		$this->ViewEnabled = false;
	}

	protected function  enableLayout()
	{
		$this->LayoutEnabled = true;
	}

	protected function  isLayoutDisabled()
	{
		return !$this->LayoutEnabled;
	}

	public function makeNonce($seed, $i = 0)
	{
		$timestamp = time();
		$q = -3;
		$TimeReduced = substr($timestamp, 0, $q) - $i;
		$string = $seed . $TimeReduced;
		$hash = hash('sha1', $string, false);
		return  $hash;
	}

	public function checkNonce(string $nonce, string $seed)
	{
		if ($nonce == $this->makeNonce($seed, 0) || $nonce == $this->makeNonce($seed, 1)) {
			return true;
		} else {
			return false;
		}
	}
}
