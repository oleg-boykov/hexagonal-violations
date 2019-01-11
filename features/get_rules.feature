Feature: As quality manager I can get rules list

  Scenario: Get rules list
    Given I am authenticated as user "1"
    When I send a GET request to "/violations/rules"
    Then the response code should be 200
    Then the response should contain json:
    """
    {
      "data":{
        "support_rules":[
          {
            "id":1,
            "title":"Wrong information provided to the customer",
            "days":30,
            "critical":2,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":2,
            "title":"No notes to myself were left, describing unresolved issues",
            "days":30,
            "critical":2,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":3,
            "title":"Rudeness",
            "days":30,
            "critical":1,
            "fine_percent":200,
            "working_hours":null
          },
          {
            "id":4,
            "title":"Late or absent on the shift without a reasonable excuse",
            "days":30,
            "critical":2,
            "fine_percent":200,
            "working_hours":null
          },
          {
            "id":5,
            "title":"More than one chat was missed during the shift",
            "days":30,
            "critical":2,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":6,
            "title":"Information was not forwarded to the writer",
            "days":30,
            "critical":2,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":7,
            "title":"No Support Agents online in Chat",
            "days":30,
            "critical":2,
            "fine_percent":200,
            "working_hours":null
          },
          {
            "id":8,
            "title":"Message is not answered for more than 15 minutes",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":1
          },
          {
            "id":9,
            "title":"Revision message that contradicts initial instructions was approved to the writer",
            "days":30,
            "critical":2,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":10,
            "title":"Personal\/confidential information was not deleted",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":1
          },
          {
            "id":11,
            "title":"Unnecessary submission of a clarification message",
            "days":30,
            "critical":2,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":12,
            "title":"A writer was not informed why he was reassigned",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":1
          },
          {
            "id":13,
            "title":"Price\/deadline was not adjusted for a new writer",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":2
          },
          {
            "id":14,
            "title":"Email was left without reply",
            "days":30,
            "critical":2,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":15,
            "title":"No actions to prevent order cancellation",
            "days":30,
            "critical":1,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":16,
            "title":"Reason for cancellation was not clarified with the client",
            "days":30,
            "critical":1,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":17,
            "title":"Reason for cancellation did not match real situation",
            "days":30,
            "critical":2,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":18,
            "title":"Credit was not offered; standard template was not followed",
            "days":30,
            "critical":1,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":19,
            "title":"The writer was not asked to stop working",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":2
          },
          {
            "id":20,
            "title":"The payment was not saved as credit per client\u2019s request",
            "days":30,
            "critical":2,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":21,
            "title":"Refund request was not submitted when it was promised",
            "days":30,
            "critical":2,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":22,
            "title":"Wfp\/inquiry order not processed at all",
            "days":30,
            "critical":2,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":23,
            "title":"Wfp\/inquiry order not processed on time",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":1
          },
          {
            "id":24,
            "title":"Client was not informed about unsuccessful payment attempt",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":2
          },
          {
            "id":25,
            "title":"Client was not provided with guidelines on using the site",
            "days":30,
            "critical":1,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":26,
            "title":"Client was not offered a discount he is eligible for (price appeared to be too high for him)",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":2
          },
          {
            "id":27,
            "title":"Wrong dispute submission (policy contradiction)",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":2
          },
          {
            "id":28,
            "title":"No actions to avoid a dispute were undertaken",
            "days":30,
            "critical":2,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":29,
            "title":"Inappropriate dispute submission (no explanation)",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":2
          },
          {
            "id":30,
            "title":"The dispute caused by a Support agent",
            "days":30,
            "critical":1,
            "fine_percent":null,
            "working_hours":0
          },
          {
            "id":31,
            "title":"Wrong solution offered on Approved order (policy)",
            "days":30,
            "critical":2,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":32,
            "title":"Support\u2019s offer contradicts the CEM\u2019s offer",
            "days":30,
            "critical":2,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":33,
            "title":"The order was not submitted to CEM if the client asks for it",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":1
          },
          {
            "id":34,
            "title":"Lack of assistance",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":1
          },
          {
            "id":155,
            "title":"Not following the verification procedure",
            "days":30,
            "critical":2,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":156,
            "title":"Wrong\/plagiarized file was delivered",
            "days":30,
            "critical":2,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":617,
            "title":"Unreasonable writer\u2019s price increase",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":2
          },
          {
            "id":735,
            "title":"Reminder set for wrong time\/Unnecessary reminder was set",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":1
          },
          {
            "id":750,
            "title":"Order was not published for WM",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":1
          },
          {
            "id":765,
            "title":"Order published with wrong details",
            "days":30,
            "critical":2,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":791,
            "title":"Unreasonably low writer\u2019s price",
            "days":30,
            "critical":2,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":1011,
            "title":"Credit was wrongly applied",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":1
          },
          {
            "id":1145,
            "title":"WFP\/Inquiry wrongly processed",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":1
          },
          {
            "id":1417,
            "title":"Wrong grammar\/spelling in a message to the customer",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":1
          },
          {
            "id":1504,
            "title":"Unreasonable fine to writer",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":1
          },
          {
            "id":1613,
            "title":"(CEM) Client's email was not processed within 3 days",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":1
          },
          {
            "id":1614,
            "title":"(CEM) Reviews were left after the CEM shift",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":1
          },
          {
            "id":1615,
            "title":"(CEM) Inappropriate refund offer",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":1
          },
          {
            "id":1946,
            "title":"Support Quality Check Failure",
            "days":30,
            "critical":2,
            "fine_percent":null,
            "working_hours":2
          },
          {
            "id":1947,
            "title":"Contradiction to Manager\u2019s Offer",
            "days":30,
            "critical":2,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":1948,
            "title":" Unjustified bonus request ",
            "days":30,
            "critical":2,
            "fine_percent":100,
            "working_hours":null
          },
          {
            "id":1949,
            "title":"Corporate values violation",
            "days":90,
            "critical":2,
            "fine_percent":200,
            "working_hours":8
          },
          {
            "id":1950,
            "title":"Supervisor\u2019s failure",
            "days":30,
            "critical":0,
            "fine_percent":null,
            "working_hours":0
          }
        ]
      },
      "errors":[],
      "alerts":[],
      "status":200
    }
    """

  Scenario: Cannot get rules if not authenticated
    When I send a GET request to "/violations/rules"
    Then the response code should be 401
