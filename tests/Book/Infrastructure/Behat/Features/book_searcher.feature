Feature: Book Searcher
  In order to find books
  As an API user
  I need to be able to search for books with optional filtering and pagination

  Scenario: Retrieving a list of books without filters
    When I send a GET request to "/books"
    Then the response status code should be 200
    And the response should be in JSON format
    And the JSON response should contain a "count" field
    And the JSON response should contain a "results" array
    And the JSON response should contain pagination information

  Scenario: Retrieving a list of books with pagination
    When I send a GET request to "/books?page=2"
    Then the response status code should be 200
    And the response should be in JSON format
    And the JSON response should contain a "count" field
    And the JSON response should contain a "results" array
    And the JSON response should contain pagination information
    And the JSON response should have "currentPage" equal to 2

  Scenario: Searching for books with a search term
    When I send a GET request to "/books?search=moby"
    Then the response status code should be 200
    And the response should be in JSON format
    And the JSON response should contain a "count" field
    And the JSON response should contain a "results" array
    And the JSON response should contain books related to "moby"