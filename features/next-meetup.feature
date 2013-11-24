Feature: Showcase next meetup
  In order to get involved with the UG and join the next meeting
  As a visitor
  I need to see a call-to-action of the next event with required information

  Scenario: Next meetup is highlighted
    Given Meetup API returns upcoming events with:
      | date    | name               | venue_name         | venue_addr_1          | venue_addr_2 |
      | +1 day  | Meetup Tomorrow    | True HQ            | Coöperatiehof 16      | 1073 JR      |
      | +15 day | Meetup This  Month | Copernica          | De Ruijterkade 112    |              |
      | +45 day | Meetup Next Month  | Optimizely         | 143 egelantiersstraat |              |
     When I go to "/"
     Then I should see "Meetup Tomorrow" in the ".meetup.upcoming .name" element
      And I should see "Coöperatiehof 16, Amsterdam" in the ".meetup.upcoming .address" element

#  Scenario: RSVP stats are updated hourly

  Scenario: Meetup displays map of location
    Given Meetup API returns upcoming events with:
      | date    | name               | venue_name         | venue_addr_1          | venue_addr_2 |
      | +3 day  | Meetup This  Month | True HQ            | Coöperatiehof 16      | 1073 JR      |
    When I go to "/"
    Then I should see an ".meetup.upcoming .map" element

  Scenario: Geeks and drinks are not show in meetup showcase
    Given Meetup API returns upcoming events with:
      | date    | name               | venue_name         | venue_addr_1          | venue_addr_2 |
      | +1 day  | Geeks & drinks     | De Bekeerde Suster | Kloveniersburgwal 6   |              |
      | +15 day | Meetup This  Month | True HQ            | Coöperatiehof 16      | 1073 JR      |
      | +45 day | Meetup Next Month  | Optimizely         | 143 egelantiersstraat |              |
    When I go to "/"
    Then I should see "Meetup This Month" in the ".meetup.upcoming .name" element

    Scenario: Show "spots left" if a limit defined
      Given Meetup API returns upcoming events with:
        | date    | name               | venue_name         | venue_addr_1          | venue_addr_2 | rsvp_limit |
        | +1 day  | Meetup Tomorrow    | True HQ            | Coöperatiehof 16      | 1073 JR      | 45         |
      When I go to "/"
      Then I should see an ".meetup.upcoming .stats .spots-left" element

    Scenario: Hide "spots left" if no limit defined
      Given Meetup API returns upcoming events with:
        | date    | name               | venue_name         | venue_addr_1          | venue_addr_2 |
        | +1 day  | Meetup Tomorrow    | True HQ            | Coöperatiehof 16      | 1073 JR      |
      When I go to "/"
      Then I should not see an ".meetup.upcoming .stats .spots-left" element
       And I should not see an ".meetup.upcoming .stats .waiting-list" element

    Scenario: Show "waiting list" count if waiting list over 0
      Given Meetup API returns upcoming events with:
        | date    | name               | venue_name         | venue_addr_1          | venue_addr_2 | waiting_list |
        | +1 day  | Meetup Tomorrow    | True HQ            | Coöperatiehof 16      | 1073 JR      | 20           |
      When I go to "/"
      Then I should see an ".meetup.upcoming .stats .waiting-list" element
       And I should not see an ".meetup.upcoming .stats .spots-left" element
