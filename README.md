# Twilio Demo Application: Rate talks from an event - build for the SuperMondays lightning talk event, October 2011

## Usage

1. Setup a database using the schema in private/db.php
2. Add talks to the DB
3. Update private/config.php to include your DB credentials
4. Update private/controllers/smtwilio/smtwilio.controller.php to include the URL to the application, your twilio SID and your twilio Auth token
5. Export the twilio PHP library (https://github.com/twilio/twilio-php) into the libraries/external/twilio folder
6. Upload public files to your web server, put the private folder just outside web root - if the folders are not named public and private, update the path from public/index.php 