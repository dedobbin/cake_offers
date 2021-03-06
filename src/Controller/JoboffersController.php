<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\Entity;

class JoboffersController extends ApiController
{
    public function initialize(){
        parent::initialize();
        $this->restrictedActions = [$this->request->params['controller']=>['getown', 'delete', 'create', 'edit']];
    }

    /**
     * Created a new job offer owned by user associated with current session.
     * 
     * Post data keys: title, content
     * 
     * Responds with error JSON on database error, or expected post data is missing.
     * Otherwise responds with success JSON.
     */
    public function create(){
        $userId = $session = $this->getRequest()->getSession()->read('user_id');
        $postData = $this->request->getData();

        if (!$this->validateForm(['title', 'content'], $postData)){
            $this->jsonError("Information missing");
            return;
        }
        $this->encode($postData);

        if ($this->JobOffers->save(new Entity ([
            'title'     => $postData['title'],
            'content'   => $postData['content'],
            'user_id'   => $userId])))
            $this->jsonSuccess("Job offer created");
        else
            $this->jsonError("Something went wrong");
    }

    /**
     * Allows a user to change information regarding a job offer.
     * Created a new job offer owned by user associated with current session.
     * 
     * Post data keys: title, content
     * @param int $id is passed through URL
     * 
     * Responds with error JSON on database error, or expected post data is missing.
     * Otherwise responds with success JSON.
     */
    public function edit (int $id = null){
        if (empty($id)){
            $this->jsonError("No ID given");
            return;
        }

        $userId = $session = $this->getRequest()->getSession()->read('user_id');
        $postData = $this->request->getData();


        $offer = $this->Joboffers->find('all')
        -> where(['deleted'=> 0, 'user_id'=>$userId, 'id'=>$id])
        ->first();
        if (empty($offer)){
            $this->jsonError("Could not find job offer");
            return;
        }

        if (!$this->validateForm(['title', 'content'], $postData)){
            $this->jsonError("Information missing");
            return;
        }
        $this->encode($postData);
     
        $offer->content = $postData['content'];
        $offer->title = $postData['title'];
        if ($this->JobOffers->save($offer))
            $this->jsonSuccess("Job offer updated");
        else
            $this->jsonError("Something went wrong");
    }

    /**
     * Allows a user to delete a job offer.
     * 
     * @param int $id is passed through URL
     * 
     * Responds with error JSON if job offer without ID does not exist,
     * or is not owned by user associated with current session.
     * Otherwise responds with success JSON.
     */
    public function delete(int $id = null){
        if (empty($id)){
            $this->jsonError("No ID given");
            return;
        }
        $userId = $this->request->getSession()->read('user_id');
        $offer = $this->Joboffers->find('all')
        ->where(['deleted'=> 0, 'user_id'=>$userId])
        ->first();
        if (empty($offer)){
            $this->jsonError("Could not find job offer");
            return;
        }
        $offer->deleted = 1;
        if ($this->JobOffers->save($offer))
            $this->jsonSuccess("Job offer deleted");
        else
            $this->jsonError("Something went wrong");
    }

    /**
     * Returns all job offers created by a user.
     * 
     * Responds with success JSON, if no job offers are owned by user, even when there are no job offers.
     * Responds with error JSON if no $id is given.
     * 
     * @param int $id is passed through URL
     */
    public function getFromUser($userId = NULL){
        if (empty($userId) || !ctype_digit($userId)){
            $this->jsonError("No user ID given");
            return;
        }
        $offers = $this->Joboffers->find('all', ['order'=>['created'=>'asc']])
        ->select(['id', 'content', 'title'])
        ->where(['user_id' => $userId, 'deleted'=> 0]);
        $this->jsonSuccess("Success", $offers);
        return;
    }

    /**
     * Gets all job offers created by user associated with current session.
     * 
     * Responds with success JSON, if no job offers are owned by user, even when there are no job offers.
     */
    public function getOwn(){
        //Already used restrictedActions to check if user was logged in, no need to do it again here
        $userId = $session = $this->getRequest()->getSession()->read('user_id');
        $offers = $this->Joboffers->find('all', ['order'=>['created'=>'asc']])
        ->select(['id', 'content', 'title'])
        ->where(['user_id'=>$userId, 'deleted'=> 0]);
        $this->jsonSuccess("Success", $offers);
        return;
    }

    /**
     * Gets all job offers.
     * 
     * @param int $id is passed through URL. If is set, only gets job offer with that ID
     * Otherwise ignored.
     * 
     * Responds with success JSON containing users and their job offers
     */
    public function getAll($id = NULL){
        //TODO: problem with the 'contains' approach is that we group per user now, not very dynamic
        $users = $this->Users->find('all')
        ->contain(['JobOffers' => function($q) use ($id){ 
            $q->where(['Joboffers.deleted' => 0])
            ->select(['Joboffers.title', 'Joboffers.content', 'Joboffers.user_id']);
            //If ID was given, filter on ID, otherwise ignore.
            if (!empty($id))
                $q->where(['Joboffers.id'=>$id]);
            return $q;
        }])
        -> select(['id', 'username'])
        -> where(['users.deleted'=> 0]);
        $this->jsonSuccess("Success", $users);
        return;
    }
}
