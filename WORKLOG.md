Worklog for MoodLogBundle Project
==================================

The log file is organized by dates and not versions. The result will be a  fully working prototype or perhaps we
can call it a beta version 1.

27.03.2012

-Removed static path to trigger icon for the getObObjectFrom() method and used helper from assetic.
-added mood levels to day reports
-added triggers to graph labels and plotted them in the graph
-added access control for reports based on who the user has access to, but must be tested more

26.03.2012

-inserted a marker into the graph. Will create tooltips later
-sleep into bars in the chart. The sleep yAxis is not put on the opposite side, will fix later if there is time.
-fixed bug where the first day was not shown in the graph
-fixed bug that ticks were aligned and min max values were therefore ignored (on yAxis)

25.03.2012

-Worked with templates and renamed routes for the templates to reference easier
-set min and max range of graph from -50 to 50
-added medicines to Accordion
-added some icons to use in the report
-symlinks to assets with command console assets:install web --symlink
-cleaning of chartcontroller

24.03.2012

-Added this log file
-created triggers through console command mood:mockdata on a random basis.
-removed doctrine fixtures bundle as I don't know how to use it with FOSUserBundle
-changed name of table "trigger" for storing entity Trigger, as it was a reserved word in MySQL. Now it
	is called "mtrigger" and it works.
-removed AcmeDemoBundle
-put triggers into twig template charttest