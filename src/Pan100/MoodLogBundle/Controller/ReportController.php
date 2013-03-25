<?php

namespace Pan100\MoodLogBundle\Controller;


use Pan100\MoodLogBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ob\HighchartsBundle\Highcharts\Highchart;

use Symfony\Component\HttpFoundation\Response;


class ReportController extends Controller
{

	public function indexAction() {

		if($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

			$days = $this->getUser()->getDays();

			//render the logged in view(s)
			$response = $this->render('Pan100MoodLogBundle:Report:index.html.twig', array('days' => $days));
		}
		else {
			//redirect to the login controller
			$response = $this->redirect($this->generateUrl('fos_user_security_login'));
		}
		return $response;
		
	}

	public function chartAction()
    {

        //get the data on the user
        $days = $this->getUser()->getDays();
        $moodLow = array();
        $moodHigh = array();

        foreach ($days as $day) {
            $moodLow[] = array('y' => $day->getMoodLow() -50, 'x'=>(integer)$day->getDate()->getTimestamp() . "000");
            $moodHigh[] = array('y'=>$day->getMoodHigh() -50, 'x'=>(integer)$day->getDate()->getTimestamp() . "000");
        }
        // Chart
        $series = array(
            array("name" => "Høy",    "data" => $moodHigh, "color" => "#FF0000"),
                   array("name" => "Lav",    "data" => $moodLow)
            );

        $ob = new Highchart();
        $ob->chart->renderTo('chart');  // The #id of the div where to render the chart
        //$ob->chart->type("arearange"); //TODO
        $ob->title->text('Humørsvingninger');
        $ob->subtitle->text('For ' . $days = $this->getUser()->getUsername());
        $ob->xAxis->type("datetime");
        $ob->xAxis->dateTimeLabelFormats(array('day' => "%e. %b"));
        $ob->xAxis->title(array('text' => "Dato"));
        $ob->yAxis->title(array('text'  => "Humør -50 til 50"));
        $ob->series($series);

        return $this->render('Pan100MoodLogBundle:Report:charttest.html.twig', array(
            'chart' => $ob
        ));
    }

    public function lastWeekAction() {
        $days = $this->getUser()->getDays();
        $days = array_reverse($days->toArray());
        $days = array_slice($days, 0,7);
        return $this->render('Pan100MoodLogBundle:Report:charttest.html.twig', array(
            'chart' => $this->getObObjectFrom(7), 'days' => $days
        ));        
    }

    public function fromFirstAction() {

        $em = $this->getDoctrine()->getManager();

        //DEBUG LINE
        $logger = $this->get('logger');

        //get the data on the user
        $days = $this->getUser()->getDays();

        //get the first day and find out how many days have passed
        $query = $em->createQuery('SELECT d FROM Pan100MoodLogBundle:Day d ORDER BY d.date ASC');
        $firstEntry = $query->setMaxResults(1);
        $firstEntry = $query->getSingleResult();
        $logger->info("first date is " . $firstEntry->getDate()->format('Y-m-d'));

        //return new Response("first date is " . $firstEntry->getDate()->format('Y-m-d'));

        //find out how many days have passed since the first day of logged data
        $interval = $firstEntry->getDate()->diff(new \DateTime());
        $logger->info($interval->format('%a days'));

        //return new Response("first date is " . $firstEntry->getDate()->format('Y-m-d')
        // . " and " . $interval->format('%R%a days') . " have passed");
        //generate the report and show it in the view
        return $this->render('Pan100MoodLogBundle:Report:charttest.html.twig', array(
            'chart' => $this->getObObjectFrom($interval->d), 'days' => array_reverse($days->toArray())
        )); 
    }

    private function getObObjectFrom($numberOfDaysBack) {
        $ob = new Highchart();
        $days = $this->getUser()->getDays();

        //create an array consisting of the number of days back
        $daysLastWeek = array();
        for ($i=1; $i < $numberOfDaysBack ; $i++) { 
            $date = new \DateTime();
            $date->sub(new \DateInterval('P' . $i . 'D'));
            $daysLastWeek[] = $date;
        }

        //reverse the array so that the timeline is shown correctly
        $daysLastWeek = array_reverse($daysLastWeek);

        $mood = array();
        $weekdayLabels = array();
        foreach ($daysLastWeek as $dayInWeek) {
            //figure out if this day has an entity, if not set null
            $hasDay = false;
            foreach ($days as $dayEntity) {
                if($dayInWeek->format('Y-m-d') == $dayEntity->getDate()->format('Y-m-d')) {
                    $hasDay = true;
                    $dayEntityToProcess = $dayEntity;
                }
            }
            if($hasDay) {
                $mood[] = array($dayEntityToProcess->getMoodLow() -50, $dayEntityToProcess->getMoodHigh() -50);
            }
            else {
                $mood[] = array(null, null);
            }
            //store the weekdays as a string for the x axis
            $weekdayLabels[] = $dayInWeek->format('Y-m-d');
        }
        // Chart
        $series = array(
            array("name" => "Humør",    "data" => $mood)
            );
        $ob->chart->renderTo('chart');  // The #id of the div where to render the chart
        $ob->chart->type("arearange");


        $ob->title->text('Humørsvingninger');

        $ob->subtitle->text('For ' . $days = $this->getUser()->getUsername());


        $ob->xAxis->categories($weekdayLabels);
        $ob->xAxis->title(array('text' => "Dato"));
        $ob->yAxis->max(50);
        $ob->yAxis->min(-50);
        $ob->yAxis->title(array('text'  => "Humør -50 til 50"));

        $ob->series($series);
        return $ob;
    }
}