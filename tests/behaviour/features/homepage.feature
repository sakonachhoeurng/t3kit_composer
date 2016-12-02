Feature: Homepage Overview
	In order to see and view homepage
	As website visitor
	I want to open the website and see an overview of the page

	Background:
		Given I am in full screen
		And I am on the homepage

	Scenario: User click access to main menu navigation
		Then I should access all pages of navigation with css selector "nav.main-navigation div ul li a"

	Scenario: Header on the top menu
		Then I should see the logo
		And I should see "Call us on"
		And I should see "Email us at"
		And I should see "Login"
		And I should see "Sitemap"
		And I should see "Icon text and link"

	Scenario: Homepage has slideshow
		Then I should see "Vel mollis massa varius sed"
		And I should see "Read more"
		When I follow "Read more"
		Then the page should be opened in a new tab
		Then I should see "Lagen"

	Scenario: User click scroll down
		When I scroll down "400" pixels
		Then I should see "EVOLUTION CONTINUES ... with 7LTS"
		And I should see " GET IT NOW - it's better than ever!"
