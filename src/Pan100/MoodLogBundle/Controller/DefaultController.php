<?php

namespace Pan100\MoodLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Pan100MoodLogBundle:Default:index.html.twig', array('name' => $name));
    }
}
