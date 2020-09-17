Multishop Schema 
===============

This directory contains all the database schema files.
The loading sequence of each schema file is important, please refer to `multishop-console/commands/DatabaseCommand.php`

REQUIREMENT
------------
* Support database `mysql`

HOW TO CREATE SCHEMA
--------------------------

You need to run `multishop-console` command `DatabaseCommand` to create schema.

        php console database schema 

