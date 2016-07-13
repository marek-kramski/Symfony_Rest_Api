Feature: Testing Rest

  Scenario: Checking for content type and headers after getting form by message's ID
    When I send a "GET" request to "/api/form/1"
    Then the response should be in JSON
    And the header "Content-Type" should contain "application/json"

  Scenario: Checking for content type and headers after getting message by it's ID
    When I send a "GET" request to "/api/messages/1"
    Then the response should be in JSON
    And the header "Content-Type" should contain "application/json"

  Scenario: Checking for json in POST route
    When I send a "POST" request to "api/messages"
    Then the response should be in JSON

  Scenario: Checking for json in PUT route
    When I send a "POST" request to "api/messages"
    Then the response should be in JSON


  Scenario: Checking for contents in json requested in /form/1 and correct HAL implementation
    Given I send a "GET" request to "/api/form/1"
    Then the JSON node "_links" should exist
    And the JSON node "_links.self" should exist
    And the JSON node "_links.next" should exist
    And the JSON node "_links.prev" should not exist
    And the JSON node "message" should exist
    And the JSON node "Contact_info" should exist

  Scenario: Checking for contents in json requested in /form/2 and correct HAL implementation
    Given I send a "GET" request to "/api/form/2"
    Then the JSON node "_links" should exist
    And the JSON node "_links.self" should exist
    And the JSON node "_links.next" should exist
    And the JSON node "_links.prev" should exist
    And the JSON node "message" should exist
    And the JSON node "Contact_info" should exist

  Scenario: Checking for contents in json requested in /messages/1 and correct HAL implementation
    Given I send a "GET" request to "/api/messages/1"
    Then the JSON node "_links" should exist
    And the JSON node "_links.self" should exist
    And the JSON node "_links.next" should exist
    And the JSON node "_links.prev" should not exist
    And the JSON node "message" should exist
    And the JSON node "Contact_info" should not exist

  Scenario: Checking for contents in json requested in /messages/2 and correct HAL implementation
    Given I send a "GET" request to "/api/messages/2"
    Then the JSON node "_links" should exist
    And the JSON node "_links.self" should exist
    And the JSON node "_links.next" should exist
    And the JSON node "_links.prev" should exist
    And the JSON node "message" should exist
    And the JSON node "Contact_info" should not exist
