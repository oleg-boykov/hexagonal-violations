Feature: As quality manager I can update suggestion

  Scenario: I can accept suggestion
    Given I am authenticated as user "1"
    And There are suggestions:
      | id | violator_id | rule_id | victim_type | victim_id | status      | comment | created_at          |
      | 1  | 2           | 1       | null        | null      | unprocessed | Hello   | 2017-01-01 00:00:00 |
    When I send a PUT request to "/violation_suggestions/1" with body:
    """
    {
      "user_id": 1,
      "status": "accepted"
    }
    """
    Then the response code should be 200
    Then the response should contain json like:
    """
    {
      "data":{
        "violation":{
          "id":"@type:integer",
          "violator_id":2,
          "offered_by":"1 - Unknown",
          "prossed_by":1,
          "related_violations":null,
          "comment":"Hello",
          "status":"accepted",
          "title":"Wrong information provided to the customer",
          "created_at":"@regex:/\\d{4}-\\d{2}-\\d{2}T\\d{2}:\\d{2}:\\d{2}\\+\\d{2}:\\d{2}/",
          "violationable_type":null,
          "violationable_id":null,
          "rule_id":1,
          "relations":null
        }
      },
      "status":200,
      "errors":[],
      "alerts":["Violation has been successfully updated"]
    }
    """

  Scenario: I can undo suggestion
    Given I am authenticated as user "1"
    And There are suggestions:
      | id | violator_id | rule_id | victim_type | victim_id | status   | comment | processed_by | created_at          |
      | 1  | 2           | 1       | null        | null      | accepted | Hello   | 1            | 2017-01-01 00:00:00 |
    When I send a PUT request to "/violation_suggestions/1" with body:
    """
    {
      "user_id": 1,
      "status": "unprocessed"
    }
    """
    Then the response code should be 200
    Then the response should contain json like:
    """
    {
      "data":{
        "violation":{
          "id":"@type:integer",
          "violator_id":2,
          "offered_by":"1 - Unknown",
          "prossed_by":null,
          "related_violations":null,
          "comment":"Hello",
          "status":"unprocessed",
          "title":"Wrong information provided to the customer",
          "created_at":"@regex:/\\d{4}-\\d{2}-\\d{2}T\\d{2}:\\d{2}:\\d{2}\\+\\d{2}:\\d{2}/",
          "violationable_type":null,
          "violationable_id":null,
          "rule_id":1,
          "relations":null
        }
      },
      "status":200,
      "errors":[],
      "alerts":["Violation has been successfully updated"]
    }
    """

  Scenario: I can reject suggestion
    Given I am authenticated as user "1"
    And There are suggestions:
      | id | violator_id | rule_id | victim_type | victim_id | status      | comment | created_at          |
      | 1  | 2           | 1       | null        | null      | unprocessed | Hello   | 2017-01-01 00:00:00 |
    When I send a PUT request to "/violation_suggestions/1" with body:
    """
    {
      "user_id": 1,
      "status": "rejected",
      "comment": "shit happens"
    }
    """
    Then the response code should be 200
    Then the response should contain json like:
    """
    {
      "data":{
        "violation":{
          "id":"@type:integer",
          "violator_id":2,
          "offered_by":"1 - Unknown",
          "prossed_by":1,
          "related_violations":null,
          "comment":"Hello",
          "status":"rejected",
          "title":"Wrong information provided to the customer",
          "created_at":"@regex:/\\d{4}-\\d{2}-\\d{2}T\\d{2}:\\d{2}:\\d{2}\\+\\d{2}:\\d{2}/",
          "violationable_type":null,
          "violationable_id":null,
          "rule_id":1,
          "relations":null
        }
      },
      "status":200,
      "errors":[],
      "alerts":["Violation has been successfully updated"]
    }
    """
