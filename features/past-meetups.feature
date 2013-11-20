Feature: Past Meetups
  In order to see how good past events were
  As a visitor
  I need to see a list of recently past meetups with ratings and further information on the homepage

  Scenario: Displays the last meetups
    Given Meetup API returns "5" events

  Scenario: New meetups replace older ones
  Scenario: Proper information is shown
  Scenario: Cronjob can pickup latest events
  Scenario: Event stats are updated daily
