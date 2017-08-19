Feature: About me
  In order to see the about me page correctly
  As a website user
  I need to be able to read all the excerpt about Fernando

  Scenario: View minimum information about me
    Given I am in the "homepage"
    When I browse to "about me" page
    Then the header should contain "Fernando Ripoll Lafuente"
    And first paragraph should contain "ingeniero del software"

  Scenario: View the social network links
    Given I am in the "homepage"
    When I browse to "about me" page
    Then nav should contain "2" social links




