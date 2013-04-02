<?php

namespace Pan100\MoodLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pan100\MoodLogBundle\Entity\Day;

class DayController extends Controller
{
	public function json_postAction(Request $request) {
		//testing. Just return the inputted json		
		$data = array();
		$data['moodMin'] = $request->request->get('moodMin');
		$data['moodMax'] = $request->request->get('moodMax');
		$data['sleepHours'] = $request->request->get('sleepHours');
		$data['diaryText'] = $request->request->get('diaryText');
		$triggers = array();
		foreach ($request->request->get('trigger') as $trigger) {
			$triggers[] = $trigger;
		}
		$data['triggers'] = $triggers;

		$response = new Response(json_encode($data));  
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