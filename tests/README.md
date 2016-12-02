# Testing t3kit with behat
### t3kit with behat php framework

## Getting Started:

First you need to understand what is behat test by go to this link <http://behat.org/en/latest/>

### How to integrate behat test to your project directory

1. Add this code below to your `composer.json` root file of project

    ```json
     "require-dev": {
          "behat/behat": "~3.0@dev",
          "behat/mink-extension": "~2.0@dev",
          "behat/mink-selenium2-driver": "~1.2@dev",
          "emuse/behat-html-formatter": "dev-master"
       },
       "config": {
          "bin-dir": "bin/"
       }
    ```
2. Run `composer update behat/behat`
3. Create `tests` folder inside your root project directory
4. Create `behaviour` then `features` and `bootstrap` folder
5. Create `FeatureContext` php file inside `bootstrap` folder

    ```php
    <?php
    use Behat\Mink\Driver\Selenium2Driver;

    class FeatureContext extends \Behat\MinkExtension\Context\MinkContext {
       const LOGO_SELECTOR = 'img.header-middle__logo-img';
    
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

    }
    ```
6. Create `feature file` to check homepage inside `features` folder
    ```gherkin
    Feature: Homepage Overview
        In order to see and view homepage
        As website visitor
        I want to open the website and see an overview of the page
        Background:
            Given I am in full screen
            And I am on the homepage
        Scenario: Check logo on homepage
            Then I should see the logo
    ```
7. Create `behat.yml` and configuration
    ```yaml
    default:
      autoload:
        '': %paths.base%/features/bootstrap
      extensions:
        Behat\MinkExtension:
          # put your <http://local.your-site-name/>
          base_url: http://local.t3kit.wehost.asia/
          default_session: selenium2
          files_path: features/Resources
          show_cmd: 'open %s'
          selenium2:
            browser: chrome
            wd_host: http://dockerhost:4444/wd/hub
    
        emuse\BehatHTMLFormatter\BehatHTMLFormatterExtension:
          name: html
          renderer: Twig,Behat2
          file_name: uat-report
          print_args: true
          print_outp: true
          loop_break: true
      formatters:
        pretty: true
        junit:
          output_path: ./build/logs
        html:
          output_path: ./build/logs
    
      suites:
        default:
          paths: &featurePaths
            - '%paths.base%/features'
          contexts: &contexts
            - FeatureContext
            - XmlContext
            - ResponsiveContext:
                screenSizes:
                  desktop:
                    width: 1200
                    height: 1400
                  tablet:
                    width: 785
                    height: 1024
                  mobile:
                    width: 320
                    height: 1024
          filters:
            tags: ~@skip
    ```
Then you can follow `README` in root file of docker with path `User Aceptance Test`
