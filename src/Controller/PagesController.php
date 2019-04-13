<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Network\Exception\ForbiddenException;

/**
 * Returns simple page used to demonstrate API 
 */
class PagesController extends AppController
{
    public function initialize(){
        parent::initialize();
        $this->restrictedActions =  [$this->request->params['controller']=>['edit', 'create']];
    }

    public function index(){
        $this -> render('index');
    }

    public function jobOffers(){
        $this -> render('joboffers');
    }

    public function register(){
        $this -> render('register');
    }

    public function login(){
        $this -> render('login');
    }

    public function edit(){
        $this -> render('edit');
    }

    public function create(){
        $this -> render('create');
    }

}
?>