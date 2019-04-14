<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\Entity;

class UsersController extends ApiController
{
    public function initialize(){
        parent::initialize();
        $this->restrictedActions =  [$this->request->params['controller']=>['logout', 'info']];
    }

    /**
     * Create a new user
     * 
     * Post data keys: username, password, password_retype, first_name, last_name, email_address
     * 
     * Responds with error JSON when password and retyped passwords don't match,
     * expected post data is missing, e-mail is invalid, database error happens,
     * username or e-mail is already used.
     * Otherwise responds with success JSON
     * 
     * TODO: check for weak passwords and warn user.
     * 
     */
    public function register(){
        $postData = $this->request->getData();
        $expectedFields = ['first_name', 'last_name', 'username', 'email_address', 'password', 'password_retype'];
        if (!$this->validateForm( $expectedFields, $postData)){
            $this->jsonError("Information missing");
            return;
        }

        if ($postData['password'] !== $postData['password_retype']){
            $this->jsonError("Passwords don't match");
            return;
        }

        if (!filter_var( $postData['email_address'], FILTER_VALIDATE_EMAIL)) {
            $this->jsonError("Invalid email address");
            return;
        }

        //We don't want to clean/alter passwords, this could lead to mistake in future
        unset($postData['password']);
        unset($postData['password_retype']);
        $this->clean($postData);

        $digest = password_hash ($this->request->getData('password'), PASSWORD_BCRYPT );
        $user = new Entity([
            'username'      => $postData['username'],
            'first_name'    => $postData['first_name'],
            'last_name'     => $postData['last_name'],
            'email_address' => $postData['email_address'],
            'password'      => $digest
        ]);
        // When username or e-mail are already in use, insert will fail (which is good)
        // TODO: let user know what went wrong on fail, but don't expose database error directly to client.
        if ($this->Users->save($user))
            $this->jsonSuccess("Registered !");
        else
            $this->jsonError("Something went wrong");
    }

    /**
     * Allows user to login. 
     * If password is correct and associated with username, user will be associated with session.
     * 
     * Post data keys: username, password
     * 
     * Responds with error JSON when password is not correct, or expected post data is missing.
     * Otherwise responds with success JSON
     */
    public function login(){
        $postData = $this->request->getData();
        if (!$this->validateForm( ['username', 'password'], $postData)){
            $this->jsonError("Information missing");
            return;
        }
        $user = $this->Users->find('all')
        ->where(['username'=>$postData['username'], 'deleted' => 0])
        ->first();

        if (empty($user) || !password_verify($postData['password'], $user['password'])){
            $this->jsonError("Could not verify");
            return;
        }
        $session = $this->getRequest()->getSession();
        $session->write('user_id', $user['id'] );
        $this->jsonSuccess("Logged in", ['username'=> $user['username']]);
    }

    /**
     * Destroys session
     * 
     * Responds with success JSON on success
     */
    public function logout(){
        $session = $this->getRequest()->getSession()->destroy();
        $this->jsonSuccess("Used logged out");
    }

    /**
     * Get user information of user associated with current session
     */
    public function info(){
        $user_id = $this->request->getSession()->read('user_id');
        $users = $this->Users->find()
        ->contain(['JobOffers' => function($q){ 
            $q->where(['Joboffers.deleted' => 0])
            ->select(['Joboffers.title', 'Joboffers.content', 'Joboffers.user_id']);
            return $q;
        }])
        -> select(['id', 'username', 'first_name', 'last_name', 'email_address'])
        -> where(['users.deleted'=> 0, 'id'=>$user_id]);
        $this->jsonSuccess("Success", $users);
        return;
    }
}
