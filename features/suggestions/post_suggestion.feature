Feature: As quality manager I can post suggestion

  Scenario: Post suggestion - one violations - no fine
    Given I am authenticated as user "1"
    When I send a POST request to "/violation_suggestions" with body:
    """
    {
      "rule_id": 1,
      "violator_id": 2,
      "victim_id": 1,
      "victim_type": 1,
      "comment": "hello"
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
          "comment":"hello",
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
      "alerts":["Violation suggestion has been registered."]
    }
    """

  Scenario: I cannot post violation if I am not authenticated
    When I send a POST request to "/violation_suggestions" with body:
    """
    {
      "rule_id": 1,
      "violator_id": 2,
      "victim_id": 1,
      "victim_type": 1,
      "comment": "hello"
    }
    """
    Then the response code should be 401

  Scenario: Post violation - errors
    Given I am authenticated as user "1"
    When I send a POST request to "/violation_suggestions" with body:
    """
    {
      "rule_id": "banana",
      "violator_id": "banana",
      "victim_id": "banana",
      "victim_type": "banana",
      "comment": "hello"
    }
    """
    Then the response code should be 200
    Then the response should contain json:
    """
    {
      "errors":[
        "ViolatorId should be a valid number.",
        "RuleId should be a valid number.",
        "VictimId should be a valid number.",
        "VictimType should be a valid number."
      ],
      "data":[],
      "status":200,
      "alerts":[]
    }
    """
    When I send a POST request to "/violation_suggestions" with body:
    """
    {
      "rule_id": 0,
      "violator_id": 0,
      "victim_id": 0,
      "victim_type": 0,
      "comment": "hello"
    }
    """
    Then the response should contain json:
    """
    {
      "errors":[
        "Invalid violatorId",
        "Invalid ruleId",
        "Invalid victimId",
        "Invalid victimType"
      ],
      "data":[],
      "status":200,
      "alerts":[]
    }
    """
