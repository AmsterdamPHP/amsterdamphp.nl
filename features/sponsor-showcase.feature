Feature:
  In order to be attracted to sponsoring the user group
  As a potential sponsor
  I need to see a showcase of current sponsors and see a callout to become one

  Scenario: See a call to action

  Scenario: See list of sponsors on homepage
    Given Meetup API returns "5" sponsors
#      And Meetup API returns "10" photos
#      And Pre-Selection is empty
     When I go to "/"
     Then the response status code should be 200
     Then I should see an ".sponsor-box" element
      And I should see 5 ".sponsor-list li" elements

  Scenario: See list of sponsors on separate page
     Given Meetup API returns "5" sponsors
      When I go to "/sponsors"
      Then I should see an ".sponsor-list" element
       And I should see 5 ".sponsor-list li" elements

  Scenario: Inactive sponsor is grayscaled but listed
  Scenario: Suspended sponsor is ot listed

  @v1.1
  Scenario: Sponsor page shows tiers of contribution
  @v1.1
  Scenario: Sponsor list page shows sponsors grouped by tiers
