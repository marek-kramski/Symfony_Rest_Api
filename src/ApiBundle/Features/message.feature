Feature: Getting form by message's ID

  Scenario: Finding Message by it's id
    When I send a "GET" request to "/api/form/1"
    Then the response should be in JSON