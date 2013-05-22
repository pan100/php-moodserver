<?php

namespace Pan100\MoodLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pan100\MoodLogBundle\Entity\Day;
use Pan100\MoodLogBundle\Entity\Medication;
use Pan100\MoodLogBundle\Entity\Trigger;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;


/*
**
**
** Tests with curl: 
** curl -X POST -H "Contcation/json" -d '{"username":"ola_nordmann","password":"passord"}' localhost/path/web/app_dev.php/json/day -v
** curl -X POST -H "Contcation/json" -d '{"username":"ola_nordmann","password":"passord", "date":"06.05.2013"}' localhost/path/web/app_dev.php/json/day -v
*/

class DayController extends Controller
{
	public function json_postAction(Request $request) {
		//DEBUG LINE
        $logger = $this->get('logger');
		
        $params = array();
		$content = $this->get("request")->getContent();
		if (!empty($content))
		{
    		$params = json_decode($content, true); // 2nd param to get as array
    		if($params == null) {
    			return new Response("Feil i forespørsel", 400);
    		}
		}
		else return new Response("Feil i forespørsel", 400);

		// if the user has the role_patient, do the saving, else check if the request contains values username and password. Attempt to authenticate the given username and password. If not authentic, give 403 response.
		$securityContext = $this->container->get('security.context');
		if( $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
    		// authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
    		if($this->getUser()->hasRole('ROLE_PATIENT')) {
				$user = $this->getUser();
			}
		}
		
		else  {
			$username = $params["username"];

			//check the username and password. If not correct give 403 forbidden
			$logger->info("username:" . $username);
			$userManager = $this->container->get('fos_user.user_manager');
			$user = $userManager->findUserByUsername($username);

			$notAuthenticatedMessage = "feil brukernavn eller passord";
			if(!$user) {
				return new Response($notAuthenticatedMessage, 403);
			}
			$password = $params["password"];
			$encoder_service = $this->get('security.encoder_factory');
			$encoder = $encoder_service->getEncoder($user);
			$encoded_pass = $encoder->encodePassword($password, $user->getSalt());
			$logger->info("encoded pass:" . $encoded_pass);
			if($encoded_pass != $user->getPassword()) {
				return new Response($notAuthenticatedMessage, 403);
			}
		}

        $em = $this->getDoctrine()->getManager();

        if(!array_key_exists("date", $params)) {
        	return new Response("ingen dato gitt", 400);
        }

		//get the date from the form, create a new datetime object and set the date
		$dateFromReq = $params["date"];
        $logger->info("date is given as " . $dateFromReq);
        $format = 'd.m.Y';
        $date = \DateTime::createFromFormat($format, $dateFromReq);
        $logger->info("converted to DateTime and timestamp is " . $date->getTimestamp());
       	//TODO if the day with the given date exists, do not add a new day but update the old one
		$repository = $this->getDoctrine()->getRepository('Pan100MoodLogBundle:Day');
		$day = $repository->findOneBy(array('date' => $date, 'user_id' => $user));
		$dayExists =true;
		if($day == null) {
			$day = new Day();
			$dayExists = false;	
		}
		$day->setDate($date);
		if(array_key_exists("sleepHours", $params)) {
			$day->setSleepHours($params["sleepHours"]);
		}
		if(array_key_exists("moodMin", $params)) {
			$day->setMoodLow($params["moodMin"] + 50);
		}
		if(array_key_exists("moodMax", $params)) {
			$day->setMoodLow($params["moodMax"] + 50);
		}
		//add medications if any
		if(array_key_exists("medicine_name", $params)) {
			$mednames = $params["medicine_name"];
			$medmgs = $params["medicine_mg"];
			if(!empty($mednames)) {
				$logger->info("mednames is not empty");
				foreach ($mednames as $medkey => $medname) {
					if($medname != "") {
						$day->addMedication($this->handleMedicine($medname, $medmgs[$medkey]));					
					}
				}				
			}
		}
		if(array_key_exists("trigger", $params)) {
			//add triggers
			if($request->request->get('trigger') != "") {
				foreach ($request->request->get('trigger')as $triggertext) {
					//todo check if one exists first
					$day->addTrigger($this->handleTrigger($triggertext));
				}			
			}
		}
		$day->setUserId($user);
		//add diary text
		$day->setDiaryText($request->request->get('diaryText'));
		//validate
		$validator = $this->get('validator');
		$errors = $validator->validate($day);
    	if (count($errors) > 0) {
        	return new Response(print_r($errors, true), 400);
    	} else {
			//TODO - attempt persisting or return new response with errors.

			 $encoders = array(new JsonEncoder());
			$normalizers = array(new GetSetMethodNormalizer());
			$serializer = new Serializer($normalizers, $encoders);
			if(!$dayExists) {
				$em->persist($day);
			}
		    $em->flush();
			// create a JSON-response with a 200 status code
			$response = new Response(200);
			$response->headers->set('Content-Type', 'application/json');

			return $response;
    	}
	}


	//FOR NOW THIS METHOD (and its route) IS NOT IN USE - considering removing
	public function postAction(Request $request) {
		if($this->getUser()->hasRole('ROLE_PATIENT')) {
			//render the form
			$day = new Day();
			$day->setDate(new \DateTime);
			$day->setSleepHours(8);

	        $form = $this->createFormBuilder($day)
            ->add('date', 'date')->add('sleepHours', 'integer')
            ->getForm();

	        return $this->render('Pan100MoodLogBundle:Day:post.html.twig', array(
	            'form' => $form->createView()));
			//handle submissions, validate the data and create a new date object or update an existing one			
		}
		throw new \Exception('You are not a patient and cannot access this URI');
	}

	private function handleMedicine($name, $mg) {
		$em = $this->getDoctrine()->getManager();
		//todo check if one exists first
		$repository = $this->getDoctrine()->getRepository('Pan100MoodLogBundle:Medication');
		$medObj = $repository->findOneBy(array('name' => $name, 'amount_mg' => $mg));
		if($medObj == null) {
			$medObj = new Medication();
			$medObj->setName($name);
			$medObj->setAmountMg($mg);
			//TODO validation
			$em->persist($medObj);			
		}
		return $medObj;
	}
	private function handleTrigger($triggertext) {
		$em = $this->getDoctrine()->getManager();
		$repository = $this->getDoctrine()->getRepository('Pan100MoodLogBundle:Trigger');
		$triggerObj = $repository->findOneBy(array('triggertext' => $triggertext));
		if($triggerObj == null) {
			$triggerObj = new Trigger();
			$triggerObj->setTriggertext($triggertext);
			$em->persist($triggerObj);
		}
				//todo validation
		return $triggerObj;
	}
}