Multishop Console - Open Source Ecommerce Platform  
=======================================

REQUIREMENTS
------------
* multishop-schema
* multishop-kernel


HOW TO RUN CONSOLE COMMAND
--------------------------

To see how to use a command, we can execute
 `php console help <command-name>` or
 `./console help <command-name>`
 

And to execute a command, we can use the following command format:
 `php console <command-name> [parameters...]` or
 `./console <command-name> [parameters...]`


SETUP SCHEDULED CONSOLE COMMAND USING CRONJOB
------------------------------------------

### Steps

1. in terminal : crontab -e 
2. press i to go into vim's insert mode 
3. type your cron job Ex : 30 * * * * php /base_path/console/console <command-name> [parameters...]
4. press esc to exit vim's insert mode 
5. type ZZ ( must be capital letters ) or :x
6. verify by using crontab -l 

The cronTab, by default, will send an email notification whenever a scheduled task is executed. In many circumstances, though, this just isn't needed. We can easily suppress this functionality, though, by redirecting the standard output of this command to the 'black hole' or /dev/null device.

[More crontab help](http://code.tutsplus.com/tutorials/managing-cron-jobs-with-php--net-19428)


### Example 1

The crontab comprises five entries indicating the schedule time, and also the name and path of the program to be run. Use a space or a tab between each entry:

minute(0-59) hour(0-23) day_of_month(1-31) month(1-12) day_of_week(0-7) /path/script.sh

You can replace a field value with "*". So:

        0 10 * * * /path/script.sh 
is the same as 

        0 10 1-31 1-12 0-7 /path/script.sh 

The script concerned would run at 10 each morning.


### Example 2

Run *InventoryCommand* `scan` every day 3am 

        0 3 * * * php /base_path/console/console inventory scan >/dev/null

Run *NotificationCommand* every 5 minutes

        #!/bin/bash
        */5 * * * * php /base_path/console/console notification >/dev/null

Run *NotificationCommand* every 5 hours

        0 */5 * * * php /base_path/console/console notification >/dev/null

Run *DatabaseCommand* `backup` every day 2am 

        0 2 * * * php /base_path/console/console database backup --email=[email_address] >/dev/null

Run *FileCommand* `backup` a particular directory every Saturday at 3:30am

        30 3 * * 6 php /base_path/console/console file backup --dir=[/path/to/target/dir] >/dev/null

Run *FileCommand* `purge` a particular directory every first day of month

        0 0 1 * * php /base_path/console/console file purge --dir=[/path/to/target/dir] --days=[days] >/dev/null

Run *ElasticSearchCommand* `backup` repository every first day of month

        0 0 1 * * php /base_path/console/console elasticsearch backup --snapshot=[snapshot] >/dev/null

Run *SubscriptionCommand* `freeTrial` every day 4am

        0 4 * * * php /base_path/console/console subscription freetrial >/dev/null

Run *SubscriptionCommand* `housekeeping` every day Wednesday 4am

        0 4 * * 3 php /base_path/console/console subscription housekeeping >/dev/null


### Actual crontab example

        #!/bin/bash
        0 2 * * 6 php /path/console database backup --db=[db_name] --email=[email_address] >/dev/null
        */5 * * * * php /path/console notification >/dev/null
        0 0 30 * * php /path/console/console file purge --dir=/home/multishop/beta_prod/merchant/www/uploads --days=30 >/dev/null
        0 3 * * 5 php /path/console file backup --dir=/home/multishop/beta_prod/customer/www/files >/dev/null
        #0 3 * * 5 php /path/console file backup --dir=/home/multishop/beta_prod/common/modules/wcm/content >/dev/null
        0 3 * * 6 php /path/console elasticsearch backup --snapshot=beta_prod_snapshot >/dev/null
        0 3 * * * php /path/console inventory scan >/dev/null
        0 4 * * * php /path/console subscription freetrial >/dev/null
        0 4 * * 3 php /path/console subscription housekeeping >/dev/null

