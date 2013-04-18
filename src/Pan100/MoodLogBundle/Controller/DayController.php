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


class DayController extends Controller
{
	public function json_postAction(Request $request) {
//TODO if the user has the role_patient, do the saving, else check if the request contains values username and password. Attempt to authenticate the given username and password. If not authentic, give 403 response.
		if($this->getUser()->hasRole('ROLE_PATIENT')) {
			$user = $this->getUser();
		}
		else if($request->request->get('username')) {
			//check the username and password. If not correct give 403 forbidden
			
		}
        //DEBUG LINE
        $logger = $this->get('logger');

        $em = $this->getDoctrine()->getManager();

		//get the date from the form, create a new datetime object and set the date
		$dateFromReq = $request->request->get('date');
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
		$day->setSleepHours($request->request->get('sleepHours'));
		$day->setMoodLow($request->request->get('moodMin') + 50);
		$day->setMoodHigh($request->request->get('moodMax') + 50);
		//add medications if any
			
		$mednames = $request->request->get('medicine_name');
		$medmgs = $request->request->get('medicine_mg');
		if(!empty($mednames)) {
			$logger->info("mednames is not empty");
			foreach ($mednames as $medkey => $medname) {
				if($medname != "") {
					$day->addMedication($this->handleMedicine($medname, $medmgs[$medkey]));					
				}
			}				
		}

		//add triggers
		if($request->request->get('trigger') != "") {
			foreach ($request->request->get('trigger')as $triggertext) {
				//todo check if one exists first
				$day->addTrigger($this->handleTrigger($triggertext));
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