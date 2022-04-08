<?php /** @noinspection PhpMissingParentConstructorInspection */

namespace frontend\controllers;

//use frontend\models\Crm;
//use frontend\models\mts_crm;
//use frontend\models\post_mts_crm;
//use frontend\models\Sprwhelement;
//use frontend\models\Sprwhtop;
//use Yii;
//use yii\filters\VerbFilter;
//use yii\helpers\ArrayHelper;
//use yii\helpers\Html;
use yii\web\Controller;
//use yii\web\HttpException;



/**
 * OTRS Integration Block
 *
 * @author Howard Miller
 * @version  See version in block_otrs.php
 * @copyright Copyright (c) 2011 E-Learn Design Limited
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package block_otrs
 */
class CrmProbaController extends Controller
{
    static $user='Serg';


    /**
     * CrmProbaController constructor.
     */
    function __construct()
    {
        global $CFG;

        // user id for agent
        $this->agentid = 1;

        $params = array(
            'location' => '',   //get_config('block_otrs', 'rpcurl'),
            'uri' => 'Core',
            'trace' => 1,
            'login' => '',  //get_config('block_otrs', 'rpcuser'),
            'password' => '',   //get_config('block_otrs', 'rpcpassword'),
            'style' => SOAP_RPC,
            'use' => SOAP_ENCODED
        );

        // check for proxy
        if (!empty($CFG->proxyhost)) {
            $params['proxy_host'] = $CFG->proxyhost;
        }
        if (!empty($CFG->proxyport)) {
            $params['proxy_port'] = $CFG->proxyport;
        }

        $this->_client = new SoapClient(null, $params);
    }


    private function dispatch($object, $method, $params)
    {

        // construct array of params for call
        $p = array();
        $p[] = ''; //get_config('block_otrs', 'rpcuser');
        $p[] = ''; //get_config('block_otrs', 'rpcpassword');
        $p[] = $object;
        $p[] = $method;

        // remainder by unpacking pairs in $params
        foreach ($params as $key => $value) {
            $p[] = $key;
            $p[] = $value;
        }

        // do soap call
        try {
            $result = $this->_client->__soapCall('Dispatch', $p);
        } catch (SoapFault $e) {
            echo 'OTRS Soap call failed ' . $e;
            //echo '<br /><pre>'.$this->_client->__getLastResponse().'</pre>';
            $result = false;
        }

        // another check (sigh!)
        if (is_soap_fault($result)) {
            echo 'OTRS Soap call returned fault - ' . $result->faultstring;
            $result = false;
        }

        // and again
        if (empty($result)) {
            //    echo 'OTRS Soap call returned no data (check OTRS logs) ';
            $result = false;
        }

//        debugging("OTRS RPC SOAP Request: " . s($this->_client->__getLastRequest()), DEBUG_DEVELOPER);
//        debugging("OTRS RPC SOAP Response: " . s($this->_client->__getLastResponse()), DEBUG_DEVELOPER);

        return $result;
    }

    //
    // CUSTOMER USER OBJECT
    //

    /**
     * Add custom fields
     * @param $params
     * @param $profile
     * @return bool
     */
    private function addCustomFields(&$params, $profile)
    {
        global $CFG;

        // if profile is null then nothing to do
        if (empty($profile)) {
            return false;
        }

        // if settings empty then ditto
        if (empty($CFG->block_otrs_userfields)) {
            return false;
        }

        // turn block_otrs_userfields into assoc array
        $pairs = explode(',', $CFG->block_otrs_userfields);
        $fields = array();
        foreach ($pairs as $pair) {
            $mapping = explode('=', trim($pair));
            $data = new stdClass();
            $data->local = $mapping[0];
            $data->otrs = $mapping[1];
            $fields[] = $data;
        }

        // add to params
        foreach ($fields as $field) {
            $local = $field->local;
            if (!empty($profile->$local)) {
                $params[$field->otrs] = $profile->$local;
            }
        }

        return true;
    }


    /**
     * Create a new user
     * param object $user moodle user object
     * @param $user
     * @param $profile
     * @param $notes
     * @return bool
     */
    public function CustomerUserAdd($user, $profile, $notes)
    {
        $object = 'CustomerUserObject';
        $method = 'CustomerUserAdd';
        $params = array(
            'Source' => 'CustomerUser',
            'UserFirstname' => $user->firstname,
            'UserLastname' => $user->lastname,
            'UserCustomerID' => $user->id,
            'UserLogin' => $user->username,
            'UserEmail' => $user->email,
            'ValidID' => 1,
            'UserID' => $this->agentid,
            'moodle_url' => self::fullname($user),
            'moodleID' => $user->id,
            'notes' => $notes,
        );
        self::addCustomFields($params, $profile);
        $UserLogin = $this->dispatch($object, $method, $params);
        return $UserLogin;
    }


    /**
     * Update existing customer user
     * param object $user moodle user object
     * @param $user
     * @param $profile
     * @param $olduser
     * @param $notes
     * @return bool
     */
    public function CustomerUserUpdate($user, $profile, $olduser, $notes)
    {
        $object = 'CustomerUserObject';
        $method = 'CustomerUserUpdate';
        $params = array(
            'Source' => 'CustomerUser',
            'ID' => $user->username,
            'UserFirstname' => $user->firstname,
            'UserLastname' => $user->lastname,
            'UserCustomerID' => $user->id,
            'UserLogin' => $user->username,
            'UserEmail' => $user->email,
            'ValidID' => 1,
            'UserID' => $this->agentid,
            'moodle_url' => self::fullname($user),
            'moodleID' => $user->id,
            'notes' => $notes,
        );

        //  Change userlogins for existing accounts
        if (!empty($olduser)) {
            $params['ID'] = $olduser;
        }
        self::addCustomFields($params, $profile);
        $this->dispatch($object, $method, $params);
        return true;
    }


    /**
     * Search for users
     * Search search token
     * @param $Search
     * @return array
     */
    public function CustomerSearch($Search)
    {
        $object = 'CustomerUserObject';
        $method = 'CustomerSearch';
        $params = array(
            'Search' => $Search,
        );
        $List = $this->dispatch($object, $method, $params);
        return self::unserialise($List);
    }


    /**
     * Search for username
     * @param $Username
     * @return bool
     */
    public function CustomerIDs($Username)
    {
        $object = 'CustomerUserObject';
        $method = 'CustomerIDs';
        $params = array(
            'User' => $Username,
        );
        $ID = $this->dispatch($object, $method, $params);
        return $ID;
    }


    /**
     * Search for user data
     * @param $Username
     * @return array
     */
    public function CustomerUserDataGet($Username)
    {
        $object = 'CustomerUserObject';
        $method = 'CustomerUserDataGet';
        $params = array(
            'User' => $Username,
        );
        $Data = $this->dispatch($object, $method, $params);
        return self::unserialise($Data);
    }


    /**
     * Search for user by email
     * @param $Email
     * @return array
     */
    public function CustomerEmailSearch($Email)
    {
        $object = 'CustomerUserObject';
        $method = 'CustomerSearch';
        $params = array(
            'PostMasterSearch' => $Email,
        );
        $Data = $this->dispatch($object, $method, $params);
        return self::unserialise($Data);
    }


    /**
     * Convert OTRS list to associative array
     * @param $List
     * @return array
     */
    static function unserialise($List)
    {
        $assoc = array();
        $count = floor(count($List) / 2);
        for ($i = 1; $i <= $count; $i++) {
            $key = array_shift($List);
            $assoc[$key] = array_shift($List);
        }
        return $assoc;
    }


    /**
     * Make sure result is an array (one result can be integer)
     * @param $List
     * @return array
     */
    static function arrayize($List)
    {
        if (empty($List)) {
            return array();
        }
        if (!is_array($List)) {
            return array($List);
        } else {
            return $List;
        }
    }


    static function fullname($user)
    {
        return self::$user;
    }

}