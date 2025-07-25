Feature: Book Finder
  In order to get information about a specific book
  As an API user
  I need to be able to retrieve a book by its ID

  Scenario: Successfully retrieving a book by ID
    Given there is a book with ID "1"
    When I send a GET request to "/books/1"
    Then the response status code should be 200
    And the response should be in JSON format
    And the JSON response should contain a book with ID "1"
    And the JSON response should contain a book with a title
    And the JSON response should contain a book with subjects
    And the JSON response should contain a book with authors

  Scenario: Trying to retrieve a non-existent book
    Given there is no book with ID "999999"
    When I send a GET request to "/books/999999"
    Then the response status code should be 404
    And the response should be in JSON format
    And the JSON response should contain an error message