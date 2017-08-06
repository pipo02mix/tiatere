Feature: About me
  In order to see the about me page correctly
  As a website user
  I need to be able to read all the excerpt about Fernando

  Scenario: View the contact information
    Given I am in the "homepage"
    When I browse to "about me" page
    Then first paragraph should contain "Fernando Ripoll Lafuente"




