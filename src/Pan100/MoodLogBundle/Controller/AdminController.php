<?php

namespace Pan100\MoodLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
	public function indexAction(Request $request) {

		return $this->render('Pan100MoodLogBundle:Admin:index.html.twig');
	}
}