Feature: PhotoBar
  In order to get to know the meetups
  As a visitor
  I need a row of random pictures to be shown to me

  Scenario: Show pictures
    Given Meetup API returns "10" photos
     When I go to "/"
     Then I should see an ".photo-bar" element
      And I should see an ".photo" element
