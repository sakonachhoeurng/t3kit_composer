<?php

use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Element\NodeElement;

class FeatureContext extends \Behat\MinkExtension\Context\MinkContext {

	const LOGO_SELECTOR = 'img.header-middle__logo-img';



	/**
	 * @param $nodes
	 *
	 * @return array
	 */
	public function filterVisibleNodes($nodes) {
		$visibleNodes = [];
		/** @var NodeElement $node */
		foreach ($nodes as $node) {
			if ($node->isVisible()) {
				$visibleNodes[] = $node;
			}
		}
		return $visibleNodes;
	}

	/**
	 * Checks, logo with the predefined css selector
	 * Example: I should see the logo
	 *
	 * @Then I should see the logo
	 *
	 * @throws \Exception
	 */
	public function iShouldSeeTheLogo() {
		$element = $this->assertSession()->elementExists('css', self::LOGO_SELECTOR);
		if (empty($element)) {
			throw new \Exception(sprintf("The logo with css '%s' selector does not exist.", self::LOGO_SELECTOR));
		}
	}

	/**
	 * Checks, page have css selectors
	 * Example: The element with css selector "div.className" exists
	 *
	 * @Then /^the element with css selector "([^"]*)" exists$/
	 *
	 * @param $cssSelector
	 *
	 * @throws \Exception
	 */
	public function theElementWithCssSelector($cssSelector) {
		$element = $this->getSession()->getPage()->has("css", $cssSelector);
		if (empty($element)) {
			throw new \Exception(sprintf("The page '%s' does not contain the css selector '%s'", $this->getSession()->getCurrentUrl(), $cssSelector));
		}
	}

	/**
	 * Checks, tag should have an attribute with value
	 * Example: Then tag "a" should have an attribute "title" with "Federation de la Haute Horlogerie"
	 *
	 * @param $tag
	 * @param $attribute
	 * @param $value
	 *
	 * @Then tag :tag should have an attribute :attribute with value :value
	 * @throws \Exception
	 */
	public function tagShouldHaveAnAttributeWithValue($tag, $attribute, $value) {
		$session = $this->getSession();
		$element = $session->getPage()->find("css", $tag . "[" . $attribute . "='" . $value . "']");
		if (empty($element)) {
			throw new \Exception(sprintf("The page does not contain tag '%s' with attribute '%s' equal '%s'.", $tag, $attribute, $value));
		}
	}

	/**
	 * Hover on the element with the provided css selector
	 * Example: When I hover the element "nav ul li a"
	 *
	 * @When /^I hover over the element "([^"]*)"$/
	 *
	 * @param $locator
	 *
	 * @throws \Exception
	 */
	public function iHoverOverTheElement($locator) {
		$session = $this->getSession();
		$element = $session->getPage()->find('css', $locator);
		if (empty($element)) {
			throw new \InvalidArgumentException(sprintf('Could not evaluate CSS selector: "%s"', $locator));
		}
		$element->mouseOver();
	}

	/**
	 * Click some text in span
	 * Example: When I click on the text "spanText"
	 *
	 * @When /^I click on the text "([^"]*)"$/
	 *
	 * @param $text
	 *
	 * @throws \Exception
	 */
	public function iClickOnTheText($text) {
		$session = $this->getSession();
		$element = $session->getPage()->find('xpath', $session->getSelectorsHandler()->selectorToXpath('xpath', '//span[text()="' . $text . '"]'));
		if (empty($element)) {
			throw new \InvalidArgumentException(sprintf('Cannot find text: "%s"', $text));
		}
		$element->click();
	}

	/**
	 * @param $nth
	 * @param $selector
	 *
	 * Click on the elements that have the same value
	 * Example: I follow the "1" of "nav ul li a"
	 *
	 * @When /^I follow the "([^"]*)" of "([^"]*)" element$/
	 *
	 * @throws \Exception
	 */
	public function iFollowTheNthElement($nth, $selector) {
		$elements = $this->getSession()->getPage()->findAll('css', $selector);
		$elements = $this->filterVisibleNodes($elements);
		if (empty($elements)) {
			throw new \Exception(sprintf("The element with css '%s' selector does not exist.", $selector));
		}

		if ($nth < 1 || $nth > count($elements)) {
			throw new \Exception(sprintf("Index '%s' out of range. The element index starts from 1.", $nth));
		}

		/** @var NodeElement $element */
		$element = $elements[$nth - 1];
		$element->click();
	}

	/**
	 * Click on the element with the provided css selector
	 * Example: When I click on the "1" element "nav ul li a"
	 *
	 * @When /^I click on the "([^"]*)" element "([^"]*)"$/
	 *
	 * @param $nth
	 * @param $locator
	 *
	 * @throws \Exception
	 */
	public function iClickOnTheElement($nth, $locator) {
		$this->iFollowTheNthElement($nth, $locator);
	}

	/**
	 * Loop menu navigation
	 * Example: I should access all pages of navigation with css selector "#main-nav ul li a"

	 * @param string $selector
	 * @throws \Exception
	 *
	 * @Then /^I should access all pages of navigation with css selector "([^"]*)"$/
	 */
	public function iShouldAccessAllPagesOfNavigationWithCssSelector($selector) {
		$page = $this->getSession()->getPage();
		$elements = $page->findAll('css', $selector);
		if (empty($elements)) {
			throw new \Exception(sprintf("Cannot find css '%s' selector.", $selector));
		}

		/** @var \Behat\Mink\Element\NodeElement $element */
		foreach ($elements as $element) {

			/**
			 * Force to every visit the page go to homepage first.
			 * It also prevents external links inside main menu.
			 */
			$this->visitPath('/');

			$link = $element->getAttribute('href');
			$string = sprintf('Visiting page with link: %s', $link);
			echo "\033[36m    ->  " . strtr($string, array("\n" => "\n|  ")) . "\033[0m\n";
			$this->visitPath($link);
		}
	}

	/**
	 * Wait a number of seconds
	 * Example: When I wait for "2" seconds
	 *
	 * @When /^I wait for "([^"]*)" seconds$/
	 *
	 * @param $num
	 */
	public function iWaitForSeconds($num) {
		$session = $this->getSession();
		if ($session->getDriver() instanceof Selenium2Driver) {
			$this->getSession()->wait($num * 1000);
		}
	}

	/**
	 * Wait a number of milliseconds
	 * Example: When I wait for "2" milliseconds
	 *
	 * @When /^I wait for "([^"]*)" milliseconds$/
	 *
	 * @param $num
	 */
	public function iWaitForMilliseconds($num) {
		$session = $this->getSession();
		if ($session->getDriver() instanceof Selenium2Driver) {
			$this->getSession()->wait($num);
		}
	}

	/**
	 * This code works only for Tinymce version >= 4.*
	 * Example: I fill :id of tinymce with :value
	 * - ":id" the id of textarea that you want to wrap tinymce
	 *
	 * @Then /^I fill "([^"]*)" of tinymce with "([^"]*)"$/
	 *
	 * @param $id
	 * @param $value
	 */
	public function iSetTinymce($id, $value) {
		$js = sprintf('tinymce.get("%s").setContent("%s");', $id, $value);
		$this->getSession()->executeScript($js);
	}

	/**
	 * @param $nth
	 * @param $linkText
	 *
	 * Example: I follow the "1" of link "link-text"
	 *
	 * @When /^I follow the "([^"]*)" of link "([^"]*)"$/
	 *
	 * @throws \Exception
	 */
	public function iFollowTheNthText($nth, $linkText) {
		$linkText = $this->fixStepArgument($linkText);
		$links = $this->getSession()->getPage()->findAll('named', array('link', $linkText));
		$links = $this->filterVisibleNodes($links);
		if (empty($links)) {
			throw new \Exception(sprintf("The '%s' text does not exist.", $linkText));
		}

		if ($nth < 1 || $nth > count($links)) {
			throw new \Exception(sprintf("Index '%s' is out of range. The element index starts from 1.", $nth));
		}

		/** @var NodeElement $link */
		$link = $links[$nth - 1];
		$link->click();
	}

	/**
	 * @param $nth
	 * @param $text
	 *
	 * Example: I press the "1" of button "text"
	 *
	 * @When /^I press the "([^"]*)" of button "([^"]*)"$/
	 *
	 * @throws \Exception
	 */
	public function iPressTheNthText($nth, $text) {
		$text = $this->fixStepArgument($text);
		$buttons = $this->getSession()->getPage()->findAll('named', array('button', $text));
		$buttons = $this->filterVisibleNodes($buttons);
		if (empty($buttons)) {
			throw new \Exception(sprintf("The button with '%s' text does not exist.", $text));
		}

		if ($nth < 1 || $nth > count($buttons)) {
			throw new \Exception(sprintf("Index '%s' is out of range. The element index starts from 1.", $nth));
		}

		/** @var NodeElement $button */
		$button = $buttons[$nth - 1];
		$button->press();
	}

	/**
	 * @When /^I should be on the top of page$/
	 */
	public function iShouldBeOnTheTopOfPage() {
		$this->getSession()->executeScript("window.scrollTo(0, 0);");
	}

	/**
	 * Scroll the page down to the specific element
	 *
	 * @When /^I scroll down "([^"]*)" pixels$/
	 *
	 * @param $number
	 *
	 * @throws \Exception
	 */
	public function scrollDown($number) {
		$function = <<<JS
		(function() {
			var body = document.body;
			body.scrollTop = $number;
		})();
JS;

		try {
			$this->getSession()->executeScript($function);
		} catch(Exception $e) {
			throw new \Exception("ScrollIntoView failed");
		}
	}

	/**
	 * Locates url, based on provided path.
	 * Override to provide custom routing mechanism.
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	public function locatePathUrl($path) {
		$startUrl = rtrim($this->getMinkParameter('base_url'), '//') . '//';
		return 0 !== strpos($path, 'http') ? $startUrl . ltrim($path, '//') : $path;
	}

	/**
	 * Checks, that current page PATH is equal to specified
	 * Example: Then I should be on the page "//"
	 * Example: And I should be on the page "//bats"
	 * Example: And I should be on the page "http://google.com"
	 *
	 * @Then /^(?:|I )should be on the page "(?P<page>[^"]+)"$/
	 *
	 * @param $page
	 */
	public function assertPageUrlAddress($page) {
		$this->assertSession()->addressEquals($this->locatePathUrl($page));
	}


	/**
	 * Check, that the page should open with the new tab
	 * Example: Then the page should open in a new tab
	 *
	 * @Then /^the page should be opened in a new tab$/
	 *
	 * @throws \Exception
	 */
	public function documentShouldBeOpenedInNewTab() {
		$session = $this->getSession();
		$windowNames = $session->getWindowNames();
		if (count($windowNames) < 2) {
			throw new \ErrorException("Expected to see at least new tab opened");
		}
		$session->switchToWindow($windowNames[1]);
	}

}
