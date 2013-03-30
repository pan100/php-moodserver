<?php

namespace Pan100\MoodLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DayController extends Controller
{
	public function indexAction(Request $request, $date) {
		
	}

	public function postAction(Request $request, $date) {
		if($request->getRequestFormat() == 'html') {
			if($this->getUser->hasRole('ROLE_PATIENT')) {
				//render the form
				
				//handle submissions, validate the data and create a new date object or update an existing one			
			}
		}
		elseif($request->getRequestFormat() == 'json') {
			//check if the request is containing a username and password field, if not report that the request was incomplete
			//if authentication is ok, validate the data and create a new date object or update an existing one
		}
		else {
			//return a bad request code
		}

	}

	public function deleteAction(Request $request, $date) {

	}

}