# PoliDroid Web-Based Consistency Tool

Installation Notes
=======
* Requires at least JDK7
* change upload_max_filesize and post_max_size to at least 100M in php.ini
* create storage/app/files/out directory with write permissions for webserver/user
* install cron job: * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
* install relevant FlowDroid scripts and classes to /opt/FlowDroid and /opt/PVDetector
* configure .env file
