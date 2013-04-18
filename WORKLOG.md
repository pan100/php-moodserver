Worklog for MoodLogBundle Project
==================================

The log file is organized by dates and not versions. The result will be a  fully working prototype or perhaps we
can call it a beta version 1.

18.04.2012
- Changed colors in graph
- Fixed medication logging, did not work properly
- The form now takes input from form and persists it. Much more testing must be done here, and I have not made the posting through JSON with validation.

09.04.2012

- The form for logging data is now validated somehow.

05.04.2012

-the form that is set up to get data from the user now has a controller that accepts its post and just returns the day object as json. Will validate and persist later
-changes to README.md and added file parameters.yml.sample. 

04.04.2012

I was sick last night and still feel a bit groggy, but I have done some minor changes today:
- The graph also includes today.

02.04.2012

- Started writing a controller method for taking post data in json and echoing it back in json. Seems to work, I
will continue with implementing the functionality by validating the input and creating or updating a Day entity.

01.04.2012

- The report now shows empty days as well. Before only the graph did
- The report had a severe bug. I am only testing with one user. The first day was determined based on the set of all
day entities in the collection of days irrespective of what user had logged it. So it would have shown reports based on
the first date of the first user who started logging!
- Major refactoring was done and more is to be done. Namely, the User entity now has a function called "getDaysWithNulls()"
which returns all the days logged but with empty days where nothing is logged.

29.03.2012

- Sorting is done when fetching days based on user (User entity getDays has a sortBy annotation)
- medics can now log in and see a list of their patients

28.03.2012

-started working on an admin panel to add users and connect patients and medical staff but stopped this work due to poor
planning. Will take it up later.

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