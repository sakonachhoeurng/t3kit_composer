<?php
/***************************************************************
 *
 * Copyright (C) Web Essentials
 *
 ***************************************************************/

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Behat\Context\Context as ContextInterface;

class ResponsiveContext extends RawMinkContext implements ContextInterface {

	/**
	 * @var array
	 */
	protected $screenSizes;



	/**
	 * {@inheritDoc}
	 */
	public function __construct($screenSizes = array()) {
		$this->screenSizes = $screenSizes;
	}

	/**
	 * Resize the browser window to a preset layout
	 *
	 * @When /^I am in "([^"]*)" layout$/
	 */
	public function iResizeTheWindowToLayout($layout) {
		if (array_key_exists($layout, $this->screenSizes)) {
			$currentLayout = $this->screenSizes[$layout];
			$this->getSession()->getDriver()->resizeWindow($currentLayout['width'], $currentLayout['height'], 'current');
			return TRUE;
		}
		throw new \Exception(sprintf('Layout "%s" not defined', $layout));
	}

	/**
	 * Resize the browser with full screen
	 * Example When I am in full screen
	 *
	 * @When /^I am in full screen$/
	 */
	public function resizeWindow() {
		$this->getSession()->maximizeWindow();
	}

}
