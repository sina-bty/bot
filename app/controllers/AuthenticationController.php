<?php

use Phalcon\Mvc\Controller;

class AuthenticationController extends Controller
{

    public function indexAction()
    {
    	$users = User::find();

    	foreach ($users as $user) {
    		print_r($user->uid);
    		print_r($user->uname);
    		print_r($user->uemail);
    		print_r($user->upassword);	
    	}
    }

    public function signinAction()
    {
    	if ($this->request->isPost()) {
    		
    		// getPost data
    		$email 	  = $this->request->getPost('email');
    		$password = $this->request->getPost('password');

    		// find the user
    		$user = User::findFirstByUemail($email);
    		if ($user) {
    			// user found!
    			if ($this->security->checkHash($password, $user->upassword)) {
    				// password is valid
    			} else {
    				// password is wrong
    			}
    		} else {
    			// user not found!
    		}
    		
    	} else {
    		# code...
    	}
    	
    }

    public function signupAction()
    {
    	if ($this->request->isPost()) {
    		
    		// getPost data
    		$name 	  = $this->request->getPost('name');
    		$email 	  = $this->request->getPost('email');
    		$password = $this->request->getPost('password');

    		// new user
    		$user = new User();

    		// set new user
    		$user->uname  	 = $name;
    		$user->uemail 	 = $email;
    		$user->upassword = $this->security->hash($password);

    		// create new user
    		$success = $user->create();

    		if ($success) {
    			// user created successfully
    		} else {
    			// cant create user!
    		}

    	} else {
    		# code...
    	}
    }

}