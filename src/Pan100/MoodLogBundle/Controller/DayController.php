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
        //DEBUG LINE
        $logger = $this->get('logger');

		$day = new Day();
		//get the date from the form, create a new datetime object and set the date
		$dateFromReq = $request->request->get('date');
        $logger->info("date is given as " . $dateFromReq);
        $format = 'd.m.Y';
        $date = \DateTime::createFromFormat($format, $dateFromReq);
        $logger->info("converted to DateTime and timestamp is " . $date->getTimestamp());
		$day->setDate($date);

		$day->setSleepHours($request->request->get('sleepHours'));
		$day->setMoodLow($request->request->get('moodMin'));
		$day->setMoodHigh($request->request->get('moodMax'));


		//add medications if any
		if($request->request->has('medicine_name') &&  $request->request->has('medicine_mg')) {
			$mednames = $request->request->get('medicine_name');
			$medmgs = $request->request->get('medicine_mg');
			foreach ($mednames as $medkey => $medname) {
				$medObj = new Medication();
				$medObj->setName($medname);
				$medObj->setAmountMg($medmgs[$medkey]);
				$day->addMedication($medObj);
			}
		}

		//add triggers
		foreach ($request->request->get('trigger')as $triggertext) {
			$triggerObj = new Trigger();
			$triggerObj->setTriggertext($triggertext);
			$day->addTrigger($triggerObj);
		}
		//add diary text
		$day->setDiaryText($request->request->get('diaryText'));
		//validate

		//persist

		$encoders = array(new JsonEncoder());
		$normalizers = array(new GetSetMethodNormalizer());
		$serializer = new Serializer($normalizers, $encoders);

		$response = new Response($serializer->serialize($day, 'json'));  
    	return $response;
	}


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
}