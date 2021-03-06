<?php

namespace Pan100\MoodLogBundle\Controller;


use Pan100\MoodLogBundle\Entity\User;
use Pan100\MoodLogBundle\Entity\Trigger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Zend\Json\Expr;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

    public function lastWeekAction() {
        //TODO update with username - this function will show the logged in user no matter if it is a doctor or not
        $days = $this->getUser()->getDays();
        $days = array_reverse($days->toArray());
        $days = array_slice($days, 0,7);
        return $this->render('Pan100MoodLogBundle:Report:charttest.html.twig', array(
            'chart' => $this->getObObjectFrom(7, $this->getUser()), 'days' => $days
        ));        
    }

    public function fromFirstAction($username) {
        $userManager = $this->container->get('fos_user.user_manager');        
        if($username == "self") {
            $user = $this->getUser();
        }
        else {
            //check if the user has access or show 403 forbidden
            if(!$this->loggedInUserHasAccessTo($userManager->findUserByUsername($username))) {
                throw new HttpException(403, 'Du har ikke tilgang til denne brukerens data');
            }

            $user =$userManager->findUserByUsername($username);
        }

        //DEBUG LINE
        $logger = $this->get('logger');

        //get the data on the user with days that has no data
        $days = $user->getDaysWithNulls();
        if ($days == null) {
            //render a different template
            return $this->render('Pan100MoodLogBundle:Report:nodata.html.twig');
        }
        $daysArray = $days->toArray();
        //get the first day (the last in the array) and find out how many days have passed
        $firstEntry = $days->last();
        $lastEntry = $user->getDays()->first();
        $logger->info("first date is " . $firstEntry->getDate()->format('Y-m-d'));
        $firstDate = $firstEntry->getDate()->format('Y-m-d');
        $lastDate = $lastEntry->getDate()->format('Y-m-d');
        //return new Response("first date is " . $firstEntry->getDate()->format('Y-m-d'));

        //find out how many days have passed since the first day of logged data
        $interval = $firstEntry->getDate()->diff(new \DateTime());
        $logger->info($interval->format('%a days'));

        //return new Response("first date is " . $firstEntry->getDate()->format('Y-m-d')
        // . " and " . $interval->format('%R%a days') . " have passed");
        //fill in day gaps with null
        //create an array consisting of the number of days back


        //generate the report and show it in the view
        return $this->render('Pan100MoodLogBundle:Report:charttest.html.twig', array(
            //todo - make a new array where you put null values in the days array where there are no data
            'chart' => $this->getObObjectFrom($interval->d, $user, true),'firstdate'=>$firstDate, 'lastdate'=>$lastDate, 'days' => $daysArray, 'user' => $user
        )); 
    }
    //TODO rewrite this function to take advantage of User->getDaysWithNulls()
    private function getObObjectFrom($numberOfDaysBack, $user, $stopAtLast = false) {
        //DEBUG LINE
        $logger = $this->get('logger');

        $numberOfDaysBack +=2;

        $ob = new Highchart();
        $days = $user->getDaysWithNulls();

        $chartData = array();
        $chartAverages = array();
        $chartSleep = array();
        $weekdayLabels = array();


        foreach (array_reverse($days->toArray()) as $dayEntityToProcess) {

                if(($dayEntityToProcess->getMoodLow() !== null) && ($dayEntityToProcess->getMoodHigh() !== null)) {
                    $chartData[] = array($dayEntityToProcess->getMoodLow() -50, $dayEntityToProcess->getMoodHigh() -50);
                }
                else $chartData[] = array (null, null);

                if(!$dayEntityToProcess->getTriggers()->isEmpty()) {
                    $triggerTexts = array();
                    foreach($dayEntityToProcess->getTriggers() as $trigger) {
                        $triggerTexts[] = $trigger->getTriggertext();
                    }
                    $chartAverages[] = array(
                        'y' => (($dayEntityToProcess->getMoodLow() + $dayEntityToProcess->getMoodHigh()) /2) -50,
                        'marker' => array("symbol" => "url(". $this->container->get('templating.helper.assets')->getUrl('bundles/pan100moodlog/images/trigger.png') . ")"),
                        'triggers' => $triggerTexts
                        );
                }
                else {
                    if(($dayEntityToProcess->getMoodLow() !== null) && ($dayEntityToProcess->getMoodHigh() !== null)) {
                        $chartAverages[] = (($dayEntityToProcess->getMoodLow() + $dayEntityToProcess->getMoodHigh()) /2) -50;
                    }
                    else $chartAverages[] = null;
                     
                }
                if($dayEntityToProcess->getSleepHours() != null) {
                    $chartSleep[] = $dayEntityToProcess->getSleepHours();
                }
                else {
                    $chartSleep[] = null;
                }
            //store the weekdays as a string for the x axis
            $weekdayLabels[] = $dayEntityToProcess->getDate()->format('Y-m-d');
        }

        // Chart
        $series = array(
            array("name" => "Humør",    "data" => $chartData, "zIndex" => "1", "type"=> "arearange", 'color' => '#F02E47'),
            array("name" => "Snitt med triggere",    "data" => $chartAverages, "zIndex" => "2", "type" => "line" , 'color' => '#000000'),
            array("name" => "Søvn (natten før)",    "data" => $chartSleep, "type" => "column", "zIndex" => "0", "yAxis" => 1 , 'color' => '#55F26A')
            );
        $ob->chart->renderTo('chart');  // The #id of the div where to render the chart
        // $ob->chart->type("arearange");


        $ob->title->text('Humørsvingninger');

        $ob->subtitle->text('For ' . $user->getUsername());


        $ob->xAxis->categories($weekdayLabels);
        $ob->xAxis->title(array('text' => "Dato"));
        $ob->yAxis(array(
            array("max" => 50, "min" => -50, "title" => array('text'  => "Humør -50 til 50"), "alignTicks" => false, 'plotLines' => array(array('value' => 0, 'color'=> 'green', 'dashStyle' => 'shortdash', 'width'=> 5), array('value' => 12, 'color'=> 'yellow', 'dashStyle' => 'shortdash', 'width'=> 5), array('value' => -12, 'color'=> 'yellow', 'dashStyle' => 'shortdash', 'width'=> 5), array('value' => 25, 'color'=> 'orange', 'dashStyle' => 'shortdash', 'width'=> 5), array('value' => -25, 'color'=> 'orange', 'dashStyle' => 'shortdash', 'width'=> 5), array('value' => 37, 'color'=> 'red', 'dashStyle' => 'shortdash', 'width'=> 5), array('value' => -37, 'color'=> 'red', 'dashStyle' => 'shortdash', 'width'=> 5))),
            array("max" => 24, "min" => -0, "title" => array('text'  => "Timer søvn natten før", "opposite" => true), "alignTicks" => false)
            ));
        $formatter = new Expr('
            function() {
                var tooltip = this.x + "<br/>" + this.y + "<br/>";
                if(this.point.triggers) {
                    tooltip += "triggere: ";
                    for(var i =0; i <= this.point.triggers.length -1; i++) {
                        tooltip +=  this.point.triggers[i];
                        if(i != this.point.triggers.length -1) {
                            tooltip += ", "
                        }
                    }
                    
                }

                return tooltip;
            }
            ');
        $ob->tooltip->formatter($formatter);

        $ob->series($series);
        return $ob;
    }
    
    private function loggedInUserHasAccessTo($user) {
        $loggedInUser = $this->getUser();
        if($loggedInUser->getHasAccessTo()->contains($user)) {
            return true;
        }
        return false;
    }
}