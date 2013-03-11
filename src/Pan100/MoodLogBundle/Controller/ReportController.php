<?php

namespace Pan100\MoodLogBundle\Controller;


use Pan100\MoodLogBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ob\HighchartsBundle\Highcharts\Highchart;


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

        //DEBUG LINE
        $logger = $this->get('logger');

        //get the data on the user
        $days = $this->getUser()->getDays();

        //create an array consisting of 7 last days TODO - Do this as a query
        $daysLastWeek = array();
        for ($i=0; $i < 7 ; $i++) { 
            $date = new \DateTime();
            $date->sub(new \DateInterval('P' . $i . 'D'));
            $daysLastWeek[] = $date;
                $logger->info('P' . $i . 'D');
        }

        $mood = array();

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
                $mood[] = array(array($dayEntityToProcess->getMoodLow() -50, $dayEntityToProcess->getMoodHigh()));
            }
            else {
                $mood[] = array(array("null", "null"));
            }

            
        }
        // Chart
        $series = array(
            array("name" => "Humør",    "data" => $mood)
            );

        $ob = new Highchart();
        $ob->chart->renderTo('chart');  // The #id of the div where to render the chart
        $ob->chart->type("columnrange");


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
}