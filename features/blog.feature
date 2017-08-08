Feature: Blog roll
  In order to see the blog posts
  As a website user
  I need to be able to read all publish posts

  Scenario: See blog roll
    Given I am in the "homepage"
    When I browse to "blog" page
    Then I see the list of the last 3 entries




