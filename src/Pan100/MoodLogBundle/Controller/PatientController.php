<?php

namespace Pan100\MoodLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PatientController extends Controller
{
	public function indexAction() {
		return new Response('Hello world!');
	}
}