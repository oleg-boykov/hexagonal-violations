Feature: As quality manager I can get suggestions

  Scenario: Get suggestion list - check api format
    Given I am authenticated as user "1"
    And There are suggestions:
      | id | violator_id | rule_id | victim_type | victim_id | status      | comment | created_at          |
      | 1  | 2           | 1       | null        | null      | unprocessed | Hello   | 2017-01-01 00:00:00 |
    When I send a GET request to "/violation_suggestions?perPage=1&page=1"
    Then print response
    Then the response code should be 200
    And the response should contain json:
    """
    {
      "data":{
        "data":{
          "page":1,
          "total":1,
          "violations":[
            {
              "id":1,
              "violator": "2 - John Snow",
              "violator_id":2,
              "violator_full_name": null,
              "offered_by": "1 - Unknown",
              "prossed_by":null,
              "related_violations":null,
              "comment":"Hello",
              "status":"unprocessed",
              "title":"Wrong information provided to the customer",
              "created_at":"2017-01-01T00:00:00+00:00",
              "violationable_type":null,
              "violationable_id":null,
              "rule_id":1,
              "relations":null
            }
          ]
        }
      },
      "errors":[],
      "alerts":[],
      "status":200
    }
    """

  Scenario: Get suggestions list
    Given I am authenticated as user "1"
    And There are suggestions:
      | id | violator_id | rule_id | victim_type | victim_id | status      | comment |
      | 1  | 2           | 1       | null        | null      | unprocessed | Hello   |
      | 2  | 2           | 2       | null        | null      | accepted    |         |
      | 3  | 2           | 3       | null        | null      | rejected    |         |
      | 4  | 2           | 4       | null        | null      | deleted     |         |
      | 5  | 2           | 5       | null        | null      | unprocessed |         |
    When I send a GET request to "/violation_suggestions?perPage=2&page=1"
    Then the response should contain json like:
    """
    {
      "data":{
        "data":{
          "page":1,
          "total":5,
          "violations":[
            {
              "id":"@type:integer",
              "violator_id":2,
              "offered_by": "1 - Unknown",
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
            },
            {
              "id":"@type:integer",
              "violator_id":2,
              "offered_by": "1 - Unknown",
              "prossed_by":null,
              "related_violations":null,
              "comment":"",
              "status":"accepted",
              "title":"No notes to myself were left, describing unresolved issues",
              "created_at":"@regex:/\\d{4}-\\d{2}-\\d{2}T\\d{2}:\\d{2}:\\d{2}\\+\\d{2}:\\d{2}/",
              "violationable_type":null,
              "violationable_id":null,
              "rule_id":2,
              "relations":null
            }
          ]
        }
      },
      "errors":[],
      "alerts":[],
      "status":200
    }
    """
    When I send a GET request to "/violation_suggestions?perPage=3&page=2"
    When the response should contain json like:
    """
    {
      "data":{
        "data":{
          "page":2,
          "total":5,
          "violations":[
            {
              "id":"@type:integer",
              "violator_id":2,
              "offered_by": "1 - Unknown",
              "prossed_by":null,
              "related_violations":null,
              "comment":"",
              "status":"deleted",
              "title":"Late or absent on the shift without a reasonable excuse",
              "created_at":"@regex:/\\d{4}-\\d{2}-\\d{2}T\\d{2}:\\d{2}:\\d{2}\\+\\d{2}:\\d{2}/",
              "violationable_type":null,
              "violationable_id":null,
              "rule_id":4,
              "relations":null
            },
            {
              "id":"@type:integer",
              "violator_id":2,
              "offered_by": "1 - Unknown",
              "prossed_by":null,
              "related_violations":null,
              "comment":"",
              "status":"unprocessed",
              "title":"More than one chat was missed during the shift",
              "created_at":"@regex:/\\d{4}-\\d{2}-\\d{2}T\\d{2}:\\d{2}:\\d{2}\\+\\d{2}:\\d{2}/",
              "violationable_type":null,
              "violationable_id":null,
              "rule_id":5,
              "relations":null
            }
          ]
        }
      },
      "errors":[],
      "alerts":[],
      "status":200
    }
    """

  Scenario: Get suggestion list with filters
    Given I am authenticated as user "1"
    And There are suggestions:
      | id | violator_id | rule_id | victim_type | victim_id | status      | comment | created_at          |
      | 1  | 2           | 1       | null        | null      | unprocessed | Hello   | null                |
      | 2  | 2           | 2       | null        | null      | accepted    |         | null                |
      | 3  | 2           | 3       | null        | null      | accepted    |         | null                |
      | 4  | 2           | 4       | null        | null      | accepted    |         | null                |
      | 5  | 2           | 5       | null        | null      | accepted    |         | null                |
      | 6  | 1           | 2       | null        | null      | accepted    |         | null                |
      | 7  | 1           | 3       | null        | null      | accepted    |         | 2017-01-01 00:00:00 |
    When I send a GET request to "/violation_suggestions?perPage=3&page=1&filters[rule]=1"
    Then the response should contain json like:
    """
    {
      "data":{
        "data":{
          "page":1,
          "total":1,
          "violations":[
            {
              "id":"@type:integer",
              "violator_id":2,
              "offered_by": "1 - Unknown",
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
          ]
        }
      },
      "errors":[],
      "alerts":[],
      "status":200
    }
    """
    When I send a GET request to "/violation_suggestions?perPage=3&page=1&filters[rule]=2&filters[violator]=1"
    Then the response should contain json like:
    """
    {
      "data":{
        "data":{
          "page":1,
          "total":1,
          "violations":[
            {
              "id":"@type:integer",
              "violator_id":1,
              "offered_by": "1 - Unknown",
              "prossed_by":null,
              "related_violations":null,
              "comment":"",
              "status":"accepted",
              "title":"No notes to myself were left, describing unresolved issues",
              "created_at":"@regex:/\\d{4}-\\d{2}-\\d{2}T\\d{2}:\\d{2}:\\d{2}\\+\\d{2}:\\d{2}/",
              "violationable_type":null,
              "violationable_id":null,
              "rule_id":2,
              "relations":null
            }
          ]
        }
      },
      "errors":[],
      "alerts":[],
      "status":200
    }
    """
    When I send a GET request to "/violation_suggestions?perPage=3&page=1&filters[rule]=3&filters[end_date]=1483236000"
    Then the response should contain json like:
    """
    {
      "data":{
        "data":{
          "page":1,
          "total":1,
          "violations":[
            {
              "id":7,
              "violator_id":1,
              "offered_by": "1 - Unknown",
              "prossed_by":null,
              "related_violations":null,
              "comment":"",
              "status":"accepted",
              "title":"Rudeness",
              "created_at":"2017-01-01T00:00:00+00:00",
              "violationable_type":null,
              "violationable_id":null,
              "rule_id":3,
              "relations":null
            }
          ]
        }
      },
      "errors":[],
      "alerts":[],
      "status":200
    }
    """
    When I send a GET request to "/violation_suggestions?perPage=3&page=1&filters[rule]=3&filters[start_date]=1483236000"
    Then the response should contain json like:
    """
    {
      "data":{
        "data":{
          "page":1,
          "total":1,
          "violations":[
            {
              "id":3,
              "violator_id":2,
              "offered_by": "1 - Unknown",
              "prossed_by":null,
              "related_violations":null,
              "comment":"",
              "status":"accepted",
              "title":"Rudeness",
              "created_at":"@regex:/\\d{4}-\\d{2}-\\d{2}T\\d{2}:\\d{2}:\\d{2}\\+\\d{2}:\\d{2}/",
              "violationable_type":null,
              "violationable_id":null,
              "rule_id":3,
              "relations":null
            }
          ]
        }
      },
      "errors":[],
      "alerts":[],
      "status":200
    }
    """
