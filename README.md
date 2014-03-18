Issue tracking sample application
=================================

The aim of this project is to show how [predaddy](https://github.com/szjani/predaddy) can be configured and used.

Installation
------------

1. Install the application and its dependencies

   ```
   git clone https://github.com/szjani/predaddy-issuetracker-sample.git
   cd predaddy-issuetracker-sample
   composer update
   ```
2. Modify base.php: You can modify the configuration in this file. It uses MySQL and APC, you might have change them.
3. Load the sql dump from `src/resources/mysql_init.sql`
4. Modify log4php configuration file which can be found here `src/resources/log4php.xml`
5. Document root in webserver must be the following: `src/hu/szjani/presentation/web/public`

Screenshot
----------

![alt text](https://raw.github.com/szjani/predaddy-issuetracker-sample/master/issuetracker.png "Screenshot")
