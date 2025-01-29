<?php
/*
 * BugzillaPHP - PHP class interface to Bugzilla (version 3.2 and above).
 * Copyright 2009 Scott Teglasi <steglasi@subpacket.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * Bugzilla Connector class.
 *
 * @author Scott Teglasi <steglasi@subpacket.com>
 * @version 0.1
 * @copyright 2009 Scott Teglasi <steglasi@subpacket.com>
 */
class BugzillaConnector {
    private $cookies;
    private $bugzillaUrl;
    private $buglistPath = '/buglist.cgi';
    private $postbugPath = '/post_bug.cgi';
    private $showbugPath = '/show_bug.cgi';
    private $processbugPath = '/process_bug.cgi';

    private $configPath  = '/config.cgi?ctype=rdf';
    private $xmlrpcPath  = '/xmlrpc.cgi';

    private $fieldList;

    /**
     * List of bugzilla products with associated components, classifications, etc.
     *
     * @var array
     */
    private $products;

    /**
     * List of all classifications
     *
     * @var array
     */
    private $classifications;

    /**
     * List of all available components
     *
     * @var array
     */
    private $components;


    public function __construct($bugzillaUrl, $cookies = array()) {
        // Let's set this puppy up.
        $this->bugzillaUrl = $bugzillaUrl;
        if (!empty($cookies)) {
            $this->cookies = $cookies;
        }

    }
    /**
     * Perform a bugzilla login.  Upon success, returns true.
     * NOTE: Bugzilla depends upon cookies to be sent to it in order
     * to perpetuate a logged in "session".  To retrieve the cookies
     * sent back, use getCookies() and store it in your own PHP session.
     * To set cookies sent to bugzilla use setCookies().
     *
     * @param string $username
     * @param string $password
     * @param boolean $rememberMe
     * @return int
     */
    public function login($username, $password, $rememberMe = false) {
        $response = $this->xmlrpcRequest('User.login',array('login'=>$username,'password'=>$password,'rememberMe'=>$rememberMe));
        $userId = intval($response['id']);
        if ($userId > 0) {
            return $userId;
        }
        return false;
    }

    /**
     * Perform a bugzilla "logout".
     *
     * @return boolean
     */
    public function logout() {
        $this->xmlrpcRequest('User.logout',array());
        return true;
    }


    /**
     * Perform a bugzilla search, based on parameters passed in via a BugzillaSearchParameters
     * object.  Returns an array of BugzillaBug objects with the list of bugs available.
     *
     * @param BugzillaSearchParameters $params
     * @return array of BugzillaBug objects
     */
    public function search(BugzillaSearchParameters $params) {
        // Perform the query.
        $response = $this->sendRequest($this->bugzillaUrl . $this->buglistPath . '?' . $params->toString() . '&ctype=rdf');
        // TODO: Put a decent non-error-barfing method of checking for errors.
        // Ex: when logins are required and noone's logged in.

        $responseXml = simplexml_load_string($response);

        if (!$responseXml instanceof SimpleXMLElement) {
            // If we can't parse the XML response sent back, then
            // for all intents and purposes, the search failed.
            return false;
        }
        // Get the list of bugs returned.
        $result = $responseXml->xpath('//bz:id');

        foreach ($result as $bug) {
            // Extract bug id.
            $bugIds[] = (int)$bug[0];
        }
        // Retrieve all the bugs in the list.
        return $this->getBugs($bugIds);


    }

    /**
     * Retrieve the list of bug ids given.
     *
     * @param array $idList
     * @return array array of BugzillaBug objects.
     */
    public function getBugs($idList) {
        // Perform the request to get info on all the bugs.
        $url = $this->bugzillaUrl . $this->showbugPath . '?id=' . join('&id=',$idList) . '&ctype=xml';
        $response = $this->sendRequest($url,'GET');
        $responseXml = simplexml_load_string($response);
        $bugXml = $responseXml->xpath('//bug');
        foreach ($bugXml as $item) {
            $bug = new BugzillaBug();
            // Convert long_desc into an array for the bug to hold.

            $itemArray = (array)$item;
            unset($itemArray['long_desc']);
            foreach ($item->long_desc as $key=>$desc) {
                $itemArray['long_desc'][] = (array)$desc;
            }
            //print_r($item->long_desc[0]);

            $bug->fromArray((array)$itemArray);
            $buglist[] = $bug;
            unset($bug);
        }
        return $buglist;
    }

    /**
     * Convenience function to retrieve a single bug.
     *
     * @param unknown_type $id
     * @return BugzillaBug
     */
    public function getBug($id) {
        $buglist = $this->getBugs(array($id));
        return $buglist[0];
    }

    /**
     * Create a bug in bugzilla based on the BugzillaBug object passed in.
     *
     * @param BugzillaBug $bug
     * @return boolean
     */
    public function createBug(BugzillaBug $bug) {

        // Let's prepare this thing for submission to bugzilla.
        // Convert the bug into an array for passing to 'postvars'.
        $bugVars = $bug->toArray();

        $response = $this->sendRequest($this->bugzillaUrl . $this->postbugPath,'POST',null,$bugVars);
        // The response will be HTML... Which is annoying..
        // So, until I decide to write some DOM code to pick out any errors that might
        // occur, we'll just assume all was well.
        // TODO: Write something to check for errors, etc.
        return true;

        //return $response;

    }

    /**
     * Update an existing bug.
     *
     * @param BugzillaBug $bug
     * @return boolean
     */
    public function updateBug(BugzillaBug $bug) {
        // If we don't have an existing bug_id to work with, then don't bother.
        if (empty($bug->bug_id)) {
            return false;
        }
        // Now, on to business.
        $postvars = $bug->toArray();

        // Since process_bug.cgi uses the field "id" instead of "bug_id"
        // to identify the bug, change that field in the array.
        $postvars['id'] = $postvars['bug_id'];
        unset($postvars['bug_id']);

        // Rip out the delta_ts to avoid mid-air collision errors.
        // TODO: Figure out how to do proper mid-air collision checks and handle them.
        unset($postvars['delta_ts']);

        // Apparently bugzilla needs to know the length of descriptions.
        $postvars['longdesclength'] = count($postvars['long_desc']);

        // Send the request.
        $response = $this->sendRequest($this->bugzillaUrl . $this->processbugPath,'POST',null,$postvars);

        // TODO: Write something to check for errors, etc.
        //return $response;
        return true;
    }

    /**
     * Perform an XMLRPC request to bugzilla.
     *
     * @param string $method
     * @param array $params
     * @return mixed
     */
    private function xmlrpcRequest($method, $params)
    {
        $request = xmlrpc_encode_request($method,$params);
        $response = $this->sendRequest($this->bugzillaUrl . $this->xmlrpcPath,'POST',$request);
        return xmlrpc_decode($response);
    }

    /**
     * Send a request to bugzilla.
     *
     * @param string $url
     * @param string $requestType GET or POST
     * @param string $body
     * @param array $postvars
     * @return string
     */
    private function sendRequest($url, $requestType = 'GET', $body = '', $postvars = '') {
        if ($this->cookies) {
            $header = $this->cookiesToHeader() . "\n";
        } else {
            //$header = "";
        }
        if ($postvars) {
            $header .= 'Content-type: application/x-www-form-urlencoded';
        } else {
            $header .= 'Content-type: text/xml';
        }

        if (!empty($postvars)) {
            // Process them.
            $body = http_build_query($postvars);
        }

        $context = stream_context_create(array('http' => array(
              'method' => $requestType,
              'header' => $header,
              'content' => $body
        )));

        $response = file_get_contents($url, false, $context);

        // Grab any cookies that were sent in         this request and stash 'em in the session.
        foreach ($http_response_header as $item) {
            if (substr($item,0,11) == 'Set-Cookie:') {
                // Got a cookie.  Save it!
                $cookieList[] = substr($item,12);
            }
        }
        // Save cookies.
        if (is_array($cookieList)) {
            $this->saveCookies($cookieList);
        }

        return $response;
    }

    /**
     * Sets the cookie list to be sent to bugzilla in subsequent requests.
     *
     * @param array $cookieList
     */
    public function setCookies($cookieList) {
        $this->cookies = $cookieList;
    }

    /**
     * Returns an array of cookies used by bugzilla.
     *
     * @return array
     */
    public function getCookies() {
    	return $this->cookies;
    }

    /**
     * Converts the cookie array into a proper HTTP header.
     *
     * @return string
     */
    private function cookiesToHeader() {
        // Convert the cookie array into a cookie header.
        $header = false;
        if (is_array($this->cookies)) {
            $header = 'Cookie: $Version=0; ';
            foreach ($this->cookies as $cookie) {
                $header .= $cookie['name'] . '=' . $cookie['value'] . '; ';
                $header .= '$Path=' . $cookie['path'] . '; ';
            }
        }
        return $header;
    }

    private function saveCookies($cookieHeaders) {
        foreach ($cookieHeaders as $cookie) {
            // Get rid of Set-cookie.
            $cookie = str_replace('Set-Cookie: ','',$cookie);
            $cookieParts = explode(";",$cookie);
            // first one should be the cookie name and value.
            $cookieParams['name'] = substr($cookieParts[0],0,strpos($cookieParts[0],'='));
            $cookieParams['value'] = substr($cookieParts[0],strpos($cookieParts[0],'=')+1);

            foreach ($cookieParts as $piece) {
                $keyval = explode('=',$piece);

                switch ($keyval[0]) {
                    case "Path":
                        $cookieParams['path'] = $keyval[1];
                    case "Expires":
                        $cookieParams['expires'] = $keyval[1];
                }
            }
            $cookieList[] = $cookieParams;
            unset($cookieParams);
            unset($cookieParts);
            unset($piece);
            unset($keyval);

        }
        $this->cookies = $cookieList;
    }

    public function getFieldValues($fieldName) {

    }

    public function getClassifications() {
        return $this->classifications;
    }

    public function getProducts($classification = '') {
        if (empty($this->products)) {
            $this->getBugzillaConfig();
        }
        if (empty($classification)) {
            return $this->products;
        } else {
            // TODO: Whip through products, find the ones that match the given classification.
        }

    }


    /**
     * Retrieves all fields w/values, products, classifications, components, etc.
     *
     */
    public function getBugzillaConfig() {
        $url = $this->bugzillaUrl . $this->configPath;
        $response = $this->sendRequest($url);
        $dom = DOMDocument::loadXML($response);

        foreach ($dom->getElementsByTagName('Seq') as $item) {
            // Assume this is an item and process it.
            switch ($item->parentNode->nodeName) {
                case 'bz:products':
                    // Go through the list.
                    foreach ($item->childNodes as $liElement) {
                        unset($product);
                        if ($liElement->nodeName == 'li') {
                            // Get the name

                            $product['name'] = $liElement->getElementsByTagName('name')->item(0)->nodeValue;
                            // Get all components.

                            $components = $liElement->getElementsByTagName('components');
                            unset($componentUrl);
                            foreach ($components as $component) {
                                $componentUrl = $component->childNodes->item(1)->childNodes->item(1)->attributes->getNamedItem('resource')->value;
                                // Extract the component name from the URL.
                                $start =  strpos($componentUrl,'name=')+5;
                                $length = strpos($componentUrl,'&',$start) - $start;

                                $componentName = urldecode(substr($componentUrl,$start,$length));
                                //$componentInfo['url'] = $componentUrl;
                                $product['components'][] = $componentName;
                            }

                            // Get all versions.
                            $versions = $liElement->getElementsByTagName('versions');
                            unset($versionInfo);
                            unset($versionUrl);

                            foreach ($versions as $version) {
                                $versionUrl = $version->childNodes->item(1)->childNodes->item(1)->attributes->getNamedItem('resource')->value;
                                // Extract the component name from the URL.
                                $start =  strpos($versionUrl,'name=')+5;
                                //$length = strpos($versionUrl,'&',$start) - $start;
                                $versionName = urldecode(substr($versionUrl,$start));
                                //$versionInfo['url'] = $versionUrl;
                                $product['versions'][] = $versionName;
                                unset($versionInfo);
                            }

                            $this->products[] = $product;
                        }
                    }
                    break;
                case 'bz:fields':
                    break;
                case 'bz:components':
                    break;
                case 'bz:versions':
                    break;
                default:
                    foreach ($item->childNodes as $liElement) {
                        if ($liElement->nodeName == 'li') {
                            $this->fieldList[substr($item->parentNode->nodeName,3)][] = $liElement->nodeValue;
                        }
                    }
            }
        }
        return true;

    }

}

class BugzillaSearchParameters {


    private $fields = array('bug_id',
                            'alias',
                            'opendate',
                            'changeddate',
                            'bug_severity',
                            'priority',
                            'rep_platform',
                            'assigned_to',
                            'reporter',
                            'qa_contact',
                            'bug_status',
                            'resolution',
                            'short_short_desc',
                            'short_desc',
                            'status_whiteboard',
                            'component',
                            'product',
                            'classification',
                            'version',
                            'op_sys',
                            'target_milestone',
                            'votes',
                            'keywords',
                            'estimated_time',
                            'remaining_time',
                            'actual_time',
                            'percentage_complete',
                            'relevance',
                            'deadline');

    private $operators = array( 'equals',
                                'notequals',
                                'anyexact',
                                'substring',
                                'casesubstring',
                                'notsubstring',
                                'anywordssubstr',
                                'allwordssubstr',
                                'nowordssubstr',
                                'regexp',
                                'notregexp',
                                'lessthan',
                                'greaterthan',
                                'anywords',
                                'allwords',
                                'nowords',
                                'changedbefore',
                                'changedafter',
                                'changedfrom',
                                'changedto',
                                'changedby',
                                'matches');
    /**
     * If we're running a named query, this should be set.  When this variable is set,
     * all other parameters are tossed out the nearest window, ledge or door. :D
     *
     * @var string
     */
    private $namedQuery;

    private $booleanCharts;

    private $customFields = array();

    /**
     * Set a custom field parameter
     *
     * @param unknown_type $name
     * @param unknown_type $operator
     * @param unknown_type $value
     */
    public function addCustomField($name,$operator,$value) {
        $this->customFields[] = array('fieldName'=>$name,
                                      'operator'=>$operator,
                                      'value'=>$value);
        // FIXME: CHANGE THIS FUNCTIONALITY.
    }

    public function removeCustomField($name) {
        foreach ($this->customFields as $key=>$field) {
            if ($field['fieldName'] == $name) {
                unset($this->customFields[$key]);
            }
        }
        // FIXME: CHANGE THIS.
    }

    public function addItem($field, $operator, $value, $joinType = 'OR',$group = 0)
    {
        // Check the field and operators used.
        if (!in_array($field,$this->fields)) {
            throw new Exception($field . ' is not a valid field.');
        }
        if (!in_array($operator,$this->operators)) {
            throw new Exception($operator . ' is not a valid operator.');
        }
        $group = intval($group);
        if ($group < 0) {
            $group = 0;
        }
        if ($joinType != 'OR' && $joinType != 'AND') {
            throw new Exception('JoinType must be either AND or OR.');
        }
        // Proceed to add this item to the chart.
        // FIXME: Figure out how in the fuck to write this thing.
        $item = array('field'=>$field,'operator'=>$operator,'value'=>$value);
        if ($joinType == 'OR') {
            $currentOrGroup = count($this->cooleanCharts[$group]) - 1;
            if ($currentOrGroup < 0) {
                $currentOrGroup = 0;
            }
            $this->booleanCharts[$group][$currentOrGroup][] = $item;
        } elseif ($joinType == 'AND') {
            // Start a new AND group.
            $this->booleanCharts[$group][][] = $item;
        }

    }


    /**
     * Returns a querystring version of the parameters set in this object.
     *
     * @return string
     */
    public function toString() {
        $querystring = '';

        // Let's rock the rock rock.
        $chartGroup = 0;
        $chartAndGroup = 0;
        $chartOrGroup = 0;

        reset($this->booleanCharts);
        for ($chartGroup = 0;$chartGroup < count($this->booleanCharts); $chartGroup++) {
            for ($chartAndGroup = 0;$chartAndGroup < count($this->booleanCharts[$chartGroup]);$chartAndGroup++) {
                for ($chartOrGroup = 0;$chartOrGroup < count($this->booleanCharts[$chartGroup][$chartAndGroup]);$chartOrGroup++) {
                    $item = $this->booleanCharts[$chartGroup][$chartAndGroup][$chartOrGroup];

                    $querystring .= '&field' . $chartGroup . '-' . $chartAndGroup . '-'.$chartOrGroup. '=' . $item['field']
                                  . '&type' .  $chartGroup . '-' . $chartAndGroup . '-'.$chartOrGroup. '=' . $item['operator']
                                  . '&value' . $chartGroup . '-' . $chartAndGroup . '-'.$chartOrGroup. '=' . urlencode($item['value']);
                }
            }
        }
        return $querystring;
    }
}

class BugzillaBug {
    public $bug_id;
    public $creation_ts;
    public $short_desc;
    public $delta_ts;
    public $reporter_accessible;
    public $cclist_accessible;
    public $classification_id;
    public $classification;
    public $product;
    public $component;
    public $version;
    public $rep_platform = 'All';
    public $op_sys = 'All';
    public $bug_status;
    public $status_whiteboard;
    public $priority;
    public $bug_severity;
    public $target_milestone;
    public $everconfirmed;
    public $reporter = array('name'=>'','email'=>'');
    public $assigned_to = array('name'=>'','email'=>'');
    public $estimated_time;
    public $remaining_time;
    public $actual_time;
    public $deadline;
    public $qa_contact = array('name'=>'','email'=>'');
    public $long_desc = array(array('who'=>array('name'=>'','email'=>''),'bug_when'=>'','thetext'=>''));
    public $comment; // Used when submitting a bug.
    /**
     * Constructor
     *
     * @return BugzillaBug
     */
    public function __construct() {

    }

    /**
     * Convert the bug into an array of key-value pairs.
     *
     * @return array
     */
    public function toArray() {
        $reflect = new ReflectionClass('BugzillaBug');
        $properties = $reflect->getProperties();
        foreach ($properties as $property) {
            if (!empty($this->{$property->name})) {
                $data[$property->name] = $this->{$property->name};
            }
        }
        return $data;
    }

    public function toString() {

    }

    /**
     * Set all properties to values set in $data
     *
     * @param array $data
     */
    public function fromArray($data) {
        $reflect = new ReflectionClass('BugzillaBug');
        $properties = $reflect->getProperties();
        foreach ($properties as $property) {
            if (!empty($data[$property->name])) {
                $this->{$property->name} = $data[$property->name];
            }
        }
    }
}
