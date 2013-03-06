<?php

namespace Pan100\MoodLogBundle\Controller;


use Pan100\MoodLogBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReportController extends Controller
{
	public function indexAction() {

		if($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

			$loggedInUser = $this->getUser();

			//render the logged in view(s)
			$response = $this->render('Pan100MoodLogBundle:Report:index.html.twig');
		}
		else {
			//redirect to the login controller
			$response = $this->redirect($this->generateUrl('fos_user_security_login'));
		}
		return $response;
		
	}
}