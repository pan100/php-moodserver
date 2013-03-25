Worklog for MoodLogBundle Project
==================================

The log file is organized by dates and not versions. The result will be a fully working prototype or perhaps we
can call it a beta version 1.

25.03.2012

-Worked with templates and renamed routes for the templates to reference easier

24.03.2012

-Added this log file
-created triggers through console command mood:mockdata on a random basis.
-removed doctrine fixtures bundle as I don't know how to use it with FOSUserBundle
-changed name of table "trigger" for storing entity Trigger, as it was a reserved word in MySQL. Now it
	is called "mtrigger" and it works.
-removed AcmeDemoBundle
-put triggers into twig template charttest