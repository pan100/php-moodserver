<?php

namespace Pan100\MoodLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
	public function indexAction() {
		//should it be "remembered" not "fully"?
		if($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			//render the logged in view(s)

			if($this->getUser()->hasRole('ROLE_MEDIC')) {
				//get the patients associated with this medic user
				$patients = $this->getUser()->getHasAccessTo();
				$response = $this->render('Pan100MoodLogBundle:Front:index.html.twig', array('patients' => $patients));
			}
			else {
				$response = $this->redirect($this->generateUrl('_report_fromFirst'));
			}
		}
		else {
			//redirect to the login controller
			$response = $this->redirect($this->generateUrl('fos_user_security_login'));
		}
		return $response;
		
	}
}