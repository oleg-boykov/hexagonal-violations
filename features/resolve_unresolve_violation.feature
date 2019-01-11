Feature: As quality manager I can resolve/unresolve violation

  Scenario: I can resolve violation
    Given I am authenticated as user "1"
    And There are violations:
      | id | violator_id | rule_id | victim_type | victim_id | resolved | comment |
      | 1  | 2           | 1       | null        | null      | 0        | Hello   |
    When I send a PUT request to "/violations/1" with body:
    """
    {
      "resolved": "1"
    }
    """
    Then the response code should be 200
    And the response should contain json like:
    """
    {
      "data": {
        "violation": {
          "id":1,
          "violator_id":2,
          "offered_by":null,
          "processed_by":null,
          "related_violations":null,
          "comment":"Hello",
          "resolved":1,
          "title":"Wrong information provided to the customer",
          "created_at":"@regex:/\\d{4}-\\d{2}-\\d{2}T\\d{2}:\\d{2}:\\d{2}\\+\\d{2}:\\d{2}/",
          "violationable_type":null,
          "violationable_id":null,
          "rule_id":1,
          "relations":null
        }
      },
      "errors":[],
      "alerts":[],
      "status":200
    }
    """

  Scenario: I can unresolve violation
    Given I am authenticated as user "1"
    And There are violations:
      | id | violator_id | rule_id | victim_type | victim_id | resolved | comment |
      | 1  | 2           | 1       | null        | null      | 1        | Hello   |
    When I send a PUT request to "/violations/1" with body:
    """
    {
      "resolved": "0"
    }
    """
    Then the response code should be 200
    And the response should contain json like:
    """
    {
      "data": {
        "violation": {
          "id":1,
          "violator_id":2,
          "offered_by":null,
          "processed_by":null,
          "related_violations":null,
          "comment":"Hello",
          "resolved":0,
          "title":"Wrong information provided to the customer",
          "created_at":"@regex:/\\d{4}-\\d{2}-\\d{2}T\\d{2}:\\d{2}:\\d{2}\\+\\d{2}:\\d{2}/",
          "violationable_type":null,
          "violationable_id":null,
          "rule_id":1,
          "relations":null
        }
      },
      "errors":[],
      "alerts":[],
      "status":200
    }
    """
