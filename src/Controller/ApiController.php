<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\ForbiddenException;
/**
*  Parent for controllers that return JSON and accept AJAX requests.
*  Pages served from PagesController demonstrate API, 
*  but could be consumed by other clients.
**/
class ApiController extends AppController
{
    public function initialize(){
        parent::initialize();
        $this->JobOffers = TableRegistry::get('JobOffers');
        $this->Users = TableRegistry::get('Users');
    }

    public function beforeFilter(Event $event){
        $this->autoRender = false;
        $this->response->type('json');
        //When in production, only respond to ajax requests
        //if (!($this->request->is('ajax') || !env("DEBUG"))){
        $allow = env('DEBUG', false)!== 'false' ||  $this->request->is('ajax');
        if (!$allow){
            throw new ForbiddenException();
        }
        parent::beforeFilter($event);
    }

    /**
     * Support function that created JSON response for successful actions.
     * 
     * @param string $message Message detailing success.
     * @param int $errorCode Optional HTTP code, defaults to 200 (ok)
     *
     * @return Object Cake response object of JSON type
     */
    protected function jsonSuccess(string $message="", $data = []){
        $responseJson = json_encode([
            "message"       => $message,
            "data"          => $data
        ]);
        $this->response->type('json');
        $this->response->body($responseJson);
        return $this->response;
    }

    /**
     * Support function that created JSON response for failed actions.
     * 
     * @param string $message Message describing what went wrong.
     * @param int $errorCode Optional HTTP error code, defaults to 500 (server error)
     *
     * @return Object Cake response object of JSON type
     */
    protected function jsonError (string $message = "", int $errorCode = 500){
        $responseJson = json_encode([
            "message"       => $message, 
        ]);
        $this->response->statusCode($errorCode);
        $this->response->type('json');
        $this->response->body($responseJson);
        return $this->response;
    }

    /**
    * Check if all expected fields are present and contain information.
    *
    * @param array $expectedFields Names of expected fields
    * @param array $data Associative array using expectedField values as key, 
    * value being information entered in field by user
    * @param array $output Optional output parameter that will contain names of all fields 
    * with missing information
    *
    * @return bool Do all expected fields contain information? 
    */
    protected function validateForm(array $expectedFields, array $data, array &$output = []){
        $missingFields = [];
        foreach ($expectedFields as $field){
            if (!isset($data[$field]) || empty($data[$field])){
                $missingFields[] = $field;
            } 
        }
        $output = $missingFields;
        return count($output) == 0;
    }

    protected function clean(array &$input){
        $input = array_map(function ($item){
            return htmlspecialchars($item);
        }, $input);
    }
}
