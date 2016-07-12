Feature: Getting form by message's ID

  Scenario: Getting form by message's ID
    When I send a "GET" request to "/api/form/1"
    Then the response should be in JSON
    And the header "Content-Type" should contain "application/json"
    And I should see