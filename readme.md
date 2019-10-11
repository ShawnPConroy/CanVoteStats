# Canadian Voting Statistics

A few scripts to visualize election data. Slowly adding less traditional charts.

See it in action: http://demo.partialsolution.ca/canvotestats/

# Install

* Place the files in a folder.
* Import the sql/ data to a database
* Rename config.php.default to config.php
* Edit config.php to your liking
* To to the folder location's URI

# Contributing

This app is generally designed and a fronter-loader MVC application. The routing
and bootstrap files are in `php/`, database code in `models/`, HTML is generated
by `views/`. This is all controlled by `controllers/` and the template file is
just `templates/boilerplate/index.php`.