php-moodserver
==============

The server side of my master thesis app

This is a bundle for the Symfony2 framework. It enables bipolar patients to track their mood on a daily basis, and medical 
personel can gain access to reports on their patients. It includes an HTTP API consisting of only the opportunity to POST the 
tracked data, and an Android app is designed to give the patients notifications when it is desired that the data should be 
logged.

first to install run composer install. It will give an error message when attempting to create cache, this is because of parameters.yml. Go to app/config/parameters.yml.sampe, edit it and rename it to parameters.yml. Then you can build the cache.

After this you might want to test it and to create a patient with 14 days of random data plus a medical persona, run app/console mood:mockdata and press y. You then should have a patient with username ola_nordmann and password "passord" and a medical persona named drjones also with password "passord".

- you may have to run console assets:install web --symlink to include the bundle's public assets
- do not call your user "self", this is reserved in the routes and will show you your reports and they 
cannot be shared with other because of this.