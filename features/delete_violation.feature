Feature: As quality manager I can delete violation

  Scenario: I can delete violation
    Given I am authenticated as user "1"
    And There are violations:
      | id | violator_id | rule_id | victim_type | victim_id | resolved | comment |
      | 1  | 2           | 1       | null        | null      | 0        | Hello   |
    When I send a DELETE request to "/violations/1"
    Then the response code should be 200
    And the response should contain json like:
    """
    {
      "data": {
        "violation": {
          "violation_id":1
        }
      },
      "errors":[],
      "alerts":[],
      "status":200
    }
    """
