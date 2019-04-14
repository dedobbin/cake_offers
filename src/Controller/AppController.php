<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Network\Exception\UnauthorizedException;

/**
* Parent for all controllers in application
**/
class AppController extends Controller
{

    public function initialize(){
        parent::initialize();
        $helpers = array('Html', 'Javascript');
        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
    }

    /**
     * Checks every request for restriced actions. 
     * These are actions only logged in users can use.
     * All child controllers are responsible for setting own array of restricted actions in their initialize()
     * 
     * When user is trying to access restricted page, redirect him to login page.
     * When it's an ajax request, throw unauthorized exception.
     * 
     */
    public function beforeFilter(Event $event){
        $requestedController = $this->request->params['controller'];
        if (isset($this->restrictedActions) && !$this->isLoggedIn()){
            if (in_array($this->request->getParam('action'), $this->restrictedActions[$requestedController])){
                if ($this->request->is('ajax'))
                    throw new UnauthorizedException();
                return $this->redirect(
                    ['controller' => 'Pages', 'action' => 'login']
                );
            }
        }
    }

    /**
     * Support function to check if a user is logged in at the moment.
     * 
     * @return bool
     */
    protected function isLoggedIn(){
        return !empty($this->getRequest()->getSession()->read('user_id'));
    }
}
