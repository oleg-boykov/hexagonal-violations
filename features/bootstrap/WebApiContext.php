<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Webmozart\Assert\Assert;

/**
 * Provides web API description definitions.
 *
 * @todo refactor
 */
class WebApiContext implements Context
{
    /**
     * @var string
     */
    private $authorization;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var array
     */
    private $headers = array();

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ResponseInterface
     */
    private $response;

    private $placeHolders = array();

    private $secret;
    private $alg;

    /**
     * WebApiContext constructor.
     *
     * @param string $url
     */
    public function __construct(string $url, string $secret, string $alg)
    {
        $this->secret = $secret;
        $this->alg = $alg;
        $this->client = new GuzzleHttp\Client(['base_uri' => $url, 'cookies' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Adds Basic Authentication header to next request.
     *
     * @Given /^I am authenticated as user "([^"]*)"$/
     */
    public function iAmAuthenticatedAs($uid)
    {
        $this->removeHeader('Authorization');
        $this->authorization = \Firebase\JWT\JWT::encode(['uid' => $uid], $this->secret, $this->alg);
        $this->addHeader('Authorization', 'Bearer ' . $this->authorization);
    }

    /**
     * Sets a HTTP Header.
     *
     * @param string $name  header name
     * @param string $value header value
     *
     * @Given /^I set header "([^"]*)" with value "([^"]*)"$/
     */
    public function iSetHeaderWithValue($name, $value)
    {
        $this->addHeader($name, $value);
    }

    /**
     * Sends HTTP request to specific relative URL.
     *
     * @param string $method request method
     * @param string $url    relative url
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)"$/
     */
    public function iSendARequest($method, $url)
    {
        $url = $this->prepareUrl($url);

        if (version_compare(ClientInterface::VERSION, '6.0', '>=')) {
            $this->request = new Request($method, $url, $this->headers);
        } else {
            $this->request = $this->getClient()->createRequest($method, $url);
            if (!empty($this->headers)) {
                $this->request->addHeaders($this->headers);
            }
        }

        $this->sendRequest();
    }

    /**
     * Sends HTTP request to specific URL with field values from Table.
     *
     * @param string    $method request method
     * @param string    $url    relative url
     * @param TableNode $post   table of post values
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" with values:$/
     */
    public function iSendARequestWithValues($method, $url, TableNode $post)
    {
        $url = $this->prepareUrl($url);
        $fields = array();

        foreach ($post->getRowsHash() as $key => $val) {
            $fields[$key] = $this->replacePlaceHolder($val);
        }

        $bodyOption = array(
            'body' => json_encode($fields),
        );

        if (version_compare(ClientInterface::VERSION, '6.0', '>=')) {
            $this->request = new Request($method, $url, $this->headers, $bodyOption['body']);
        } else {
            $this->request = $this->getClient()->createRequest($method, $url, $bodyOption);
            if (!empty($this->headers)) {
                $this->request->addHeaders($this->headers);
            }
        }

        $this->sendRequest();
    }

    /**
     * Sends HTTP request to specific URL with raw body from PyString.
     *
     * @param string       $method request method
     * @param string       $url    relative url
     * @param PyStringNode $string request body
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" with body:$/
     */
    public function iSendARequestWithBody($method, $url, PyStringNode $string)
    {
        $url = $this->prepareUrl($url);
        $string = $this->replacePlaceHolder(trim($string));

        if (version_compare(ClientInterface::VERSION, '6.0', '>=')) {
            $this->request = new Request($method, $url, $this->headers, $string);
        } else {
            $this->request = $this->getClient()->createRequest(
                $method,
                $url,
                array(
                    'headers' => $this->getHeaders(),
                    'body' => $string,
                )
            );
        }

        $this->sendRequest();
    }

    /**
     * Sends HTTP request to specific URL with form data from PyString.
     *
     * @param string       $method request method
     * @param string       $url    relative url
     * @param PyStringNode $body   request body
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" with form data:$/
     */
    public function iSendARequestWithFormData($method, $url, PyStringNode $body)
    {
        $url = $this->prepareUrl($url);
        $body = $this->replacePlaceHolder(trim($body));

        $fields = array();
        parse_str(implode('&', explode("\n", $body)), $fields);

        if (version_compare(ClientInterface::VERSION, '6.0', '>=')) {
            $this->request = new Request($method, $url, ['Content-Type' => 'application/x-www-form-urlencoded'], http_build_query($fields, null, '&'));
        } else {
            $this->request = $this->getClient()->createRequest($method, $url);
            /** @var \GuzzleHttp\Post\PostBodyInterface $requestBody */
            $requestBody = $this->request->getBody();
            foreach ($fields as $key => $value) {
                $requestBody->setField($key, $value);
            }
        }

        $this->sendRequest();
    }

    /**
     * Checks that response has specific status code.
     *
     * @param string $code status code
     *
     * @Then /^(?:the )?response code should be (\d+)$/
     */
    public function theResponseCodeShouldBe($code)
    {
        $expected = intval($code);
        $actual = intval($this->response->getStatusCode());
        Assert::same($expected, $actual);
    }

    /**
     * Checks that response body contains specific text.
     *
     * @param string $text
     *
     * @Then /^(?:the )?response should contain "([^"]*)"$/
     */
    public function theResponseShouldContain($text)
    {
        $expectedRegexp = '/'.preg_quote($text).'/i';
        $actual = (string) $this->response->getBody();
        Assert::regex($actual, $expectedRegexp);
    }

    /**
     * @Given the response should contain json like:
     */
    public function theResponseShouldContainJsonLike(PyStringNode $jsonString)
    {
        $etalon = json_decode($this->replacePlaceHolder($jsonString->getRaw()), true);
        $actual = json_decode($this->response->getBody(), true);
        if (null === $etalon) {
            throw new \RuntimeException(
                sprintf("Can not convert etalon to json:\n %s", $this->replacePlaceHolder($jsonString->getRaw()))
            );
        }
        if (null === $actual) {
            throw new \RuntimeException(
                sprintf("Can not convert actual to json:\n %s", $this->replacePlaceHolder((string) $this->response->getBody()))
            );
        }
        $this->check($etalon, $actual);
    }


    /**
     * Checks that response body doesn't contains specific text.
     *
     * @param string $text
     *
     * @Then /^(?:the )?response should not contain "([^"]*)"$/
     */
    public function theResponseShouldNotContain($text)
    {
        $expectedRegexp = '/' . preg_quote($text) . '/';
        $actual = (string) $this->response->getBody();
        $result = preg_match($expectedRegexp, $actual);
        Assert::eq($result, 0);
    }

    /**
     * Checks that response body contains JSON from PyString.
     *
     * Do not check that the response body /only/ contains the JSON from PyString,
     *
     * @param PyStringNode $jsonString
     *
     * @throws \RuntimeException
     *
     * @Then /^(?:the )?response should contain json:$/
     */
    public function theResponseShouldContainJson(PyStringNode $jsonString)
    {
        $etalon = json_decode($this->replacePlaceHolder($jsonString->getRaw()), true);
        $actual = json_decode($this->response->getBody(), true);

        if (null === $etalon) {
            throw new \RuntimeException(
                sprintf("Can not convert etalon to json:\n %s", $this->replacePlaceHolder($jsonString->getRaw()))
            );
        }

        if (null === $actual) {
            throw new \RuntimeException(
                sprintf("Can not convert actual to json:\n %s", $this->replacePlaceHolder((string) $this->response->getBody()))
            );
        }

        Assert::greaterThanEq(
            count($etalon),
            count($actual),
            sprintf(
                "%s is not equals to\n %s",
                print_r($etalon, true),
                print_r($actual, true)
            )
        );
        foreach ($etalon as $key => $needle) {
            Assert::keyExists($actual, $key);
            Assert::eq(
                $etalon[$key],
                $actual[$key],
                sprintf(
                    "%s is not equals to\n %s",
                    print_r($etalon[$key], true),
                    print_r($actual[$key], true)
                )
            );
        }
    }

    /**
     * Prints last response body.
     *
     * @Then print response
     */
    public function printResponse()
    {
        $request = $this->request;
        $response = $this->response;

        echo sprintf(
            "%s %s => %d:\n%s",
            $request->getMethod(),
            (string) ($request instanceof RequestInterface ? $request->getUri() : $request->getUrl()),
            $response->getStatusCode(),
            (string) $response->getBody()
        );
    }

    /**
     * Prepare URL by replacing placeholders and trimming slashes.
     *
     * @param string $url
     *
     * @return string
     */
    private function prepareUrl($url)
    {
        return ltrim($this->replacePlaceHolder($url), '/');
    }

    /**
     * Sets place holder for replacement.
     *
     * you can specify placeholders, which will
     * be replaced in URL, request or response body.
     *
     * @param string $key   token name
     * @param string $value replace value
     */
    public function setPlaceHolder($key, $value)
    {
        $this->placeHolders[$key] = $value;
    }

    /**
     * Replaces placeholders in provided text.
     *
     * @param string $string
     *
     * @return string
     */
    protected function replacePlaceHolder($string)
    {
        foreach ($this->placeHolders as $key => $val) {
            $string = str_replace($key, $val, $string);
        }

        return $string;
    }

    /**
     * Returns headers, that will be used to send requests.
     *
     * @return array
     */
    protected function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Adds header
     *
     * @param string $name
     * @param string $value
     */
    protected function addHeader($name, $value)
    {
        if (isset($this->headers[$name])) {
            if (!is_array($this->headers[$name])) {
                $this->headers[$name] = array($this->headers[$name]);
            }

            $this->headers[$name][] = $value;
        } else {
            $this->headers[$name] = $value;
        }
    }

    /**
     * Removes a header identified by $headerName
     *
     * @param string $headerName
     */
    protected function removeHeader($headerName)
    {
        if (array_key_exists($headerName, $this->headers)) {
            unset($this->headers[$headerName]);
        }
    }

    private function sendRequest()
    {
        try {
            $this->response = $this->getClient()->send($this->request);

        } catch (RequestException $e) {
            $this->response = $e->getResponse();

            if (null === $this->response) {
                throw $e;
            }
        }
    }

    private function getClient()
    {
        if (null === $this->client) {
            throw new \RuntimeException('Client has not been set in WebApiContext');
        }

        return $this->client;
    }

    /**
     * @param array $array
     */
    protected static function ksortRecursive(array &$array): void
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                self::ksortRecursive($value);
            }
        }
        ksort($array);
    }

    /**
     * @param mixed $etalon
     * @param mixed $actual
     */
    protected static function check($etalon, $actual, $key = null)
    {
        // check for custom types like @regex:, @type:, etc
        if (is_string($etalon)) {
            self::checkString($etalon, $actual);
            return;
        }
        if (!is_array($etalon)) {
            Assert::same(
                $etalon,
                $actual,
                sprintf(
                    "[%s] %s is not equals to\n %s",
                    print_r($key, true),
                    print_r($etalon, true),
                    print_r($actual, true)
                )
            );
            return;
        }
        // associative array can be checked by keys
        if (self::isAssociativeArray($etalon)) {
            foreach ($etalon as $key => $value) {
                Assert::keyExists($actual, $key);
                self::check($value, $actual[$key], $key);
                continue;
            }
            return;
        }
        // check for sequential keys array
        foreach ($etalon as $key => $value) {
            $same = false;
            foreach ($actual as $actualValue) {
                /**
                 * This check with try ignore rows order
                 * Example:
                 * [
                 *   {id: 1, name: School}
                 *   {id: 2, name: University}
                 * ]
                 * AND
                 * [
                 *   {id: 2, name: University}
                 *   {id: 1, name: School}
                 * ]
                 * Will NOT fail!
                 */
                try {
                    self::check($value, $actualValue, $key);
                    // if one row in array is equal to actual - will NOT fail
                    $same = true;
                    break;
                } catch (\Exception $e) {
                    // if all checks fail then $same = false, and it will fail
                    continue;
                }
            }
            if (!$same) {
                throw new \InvalidArgumentException(
                    sprintf(
                        "%s is not equals to\n %s",
                        print_r($etalon[$key], true),
                        print_r($actual[$key], true)
                    )
                );
            }
        }
    }

    /**
     * @param array $array
     *
     * @return bool
     */
    protected static function isAssociativeArray(array $array): bool
    {
        if ([] === $array) {
            return false;
        }
        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * @param mixed $etalon
     * @param mixed $actual
     *
     * @return bool
     */
    protected static function checkString(string $etalon, $actual): void
    {
        if (0 === strpos($etalon, '@regex:')) {
            Assert::regex(
                $actual,
                str_replace('@regex:', '', $etalon),
                sprintf(
                    'The value "%s" doesn\'t match to the regex: %s',
                    $actual,
                    $etalon
                )
            );
            return;
        }
        if (0 === strpos($etalon, '@type:')) {
            $type = str_replace('@type:', '', $etalon);
            call_user_func(
                [Assert::class, $type],
                $actual,
                sprintf(
                    'The value "%s"  has wrong type: %s',
                    $actual,
                    $actual
                )
            );
            return;
        }
        Assert::same($actual, $etalon);
    }
}