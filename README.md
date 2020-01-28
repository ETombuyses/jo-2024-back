#This repository can be used to generate the JO_2024 databse

if you just want to import the database, use the jo2024_full_database.sql file directly.

If you want to know how to recreate it, keep reading.

##1: check database connection informations
Modify the PDO connection informations if necessary in the database_data_fill.php


##2: start mysql in the terminal

use the following commands :

>mysql.server start
>
>mysql -u root -p 
>
>create database jo_2024
>
>use jo_2024

##3 create the databse structure

copy paste the content of the structure_sql.text into your terminal. 
If it doesn't work properly, copy paste one portion of code at a time (one table at a time)

You successfully created the databse structure.

##4 fill the database with data

open a new terminal. Then use "cd" to go into the folder "database_scripts" of this repository.

Once you are in the folder, run the following command: 

>php database_data_fill.php

The database should now be filled with data and no errors appeared in the terminal.
