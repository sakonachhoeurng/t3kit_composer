Feature: Sitemap XML
	In order to see the sitemap
	As a website owner
	I want to see list of xml sitemap

	Scenario: sitemap.xml is in valid XML sitemap format
		Given I am on "/sitemap.xml"
		Then the xml encoding should be "UTF-8"
		And I should see xml element "urlset"
		And I should see xml element "url"
		And I should see xml element "loc"
		And the xml sitemap should contain links "/content/plugins/news/,/content/form-elements/mail-form/,/content/typical-page-content/headers/"
