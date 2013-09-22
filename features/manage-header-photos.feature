Feature: Manage Header Photos
  In order to select the header images
  As an Admin
  I need to have a management interface

  Scenario: Show photos for selection
    Given Meetup API returns "10" photos
    And Pre-Selection has "0" photos
    And I'm an Admin
    When I go to "/admin/header/selection"
    Then I should see 10 ".photo" elements

  Scenario: Block access for non-admin
    Given I'm not an Admin
    When I go to "/admin/header/selection"
    Then The response status should be "401"

  Scenario: Able to select an image


  Scenario: Able to remove an image

  Scenario: Previously selected photos should be highlighted

