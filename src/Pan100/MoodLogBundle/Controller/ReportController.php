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
        $MoodLow = array();
        $moodHigh = array();

        foreach ($days as $day) {
            $MoodLow[] = $day->getMoodLow();
            $moodHigh[] = $day->getMoodHigh();
        }
        // Chart
        $series = array(
            array("name" => "Data Serie Name",    "data" => $MoodLow)
        );

        $ob = new Highchart();
        $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->title->text('Chart Title');
        $ob->xAxis->title(array('text'  => "Horizontal axis title"));
        $ob->yAxis->title(array('text'  => "Vertical axis title"));
        $ob->series($series);

        return $this->render('Pan100MoodLogBundle:Report:charttest.html.twig', array(
            'chart' => $ob
        ));
    }
}