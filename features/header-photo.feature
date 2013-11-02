Feature: Header Photo
  In order to showcase our meetings
  As a visitor
  I should see a random header photo selected from a pre-selection

#  Scenario: Show pictures
#    Given Meetup API returns "10" photos
#     When I go to "/"
#     Then I should see an ".photo-bar" element
#      And I should see an ".photo" element

  Scenario: Show Header Image
    Given Meetup API returns "10" photos
      And Pre-Selection has "5" photos
     When I go to "/"
     Then I should see an ".header-photo" element

  Scenario: Fallback when no pre-selection
    Given Meetup API returns "10" photos
    And Pre-Selection is empty
    When I go to "/"
    Then I should see an ".header-photo" element

  Scenario: Shown Image must be from pre-selection
    Given Meetup API returns "10" photos
    And Pre-Selection has "5" photos
    When I go to "/"
    Then I should see an ".header-photo" element
     And The Image should belong to the pre-selection
