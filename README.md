# Readme - JO_2024 database

This repository was used to create de jo_2024 database.

## How to use it ?

If you just want to use the database, start mysql from terminal (mysql.server start) or connect to phpMyadmin.

### Terminal

Log into your mysql (replace <*username*> by your actual username): 

> mysql -u <*username*> -p 


Then run the following mysql commands : 

>CREATE DATABASE jo_2024;
>USE jo_2024;

if the database already exists, change it's name or delete the database if you are sure you want to overwrite it, then use :

>DROP DATABASE jo_2024;
>CREATE DATABASE jo_2024;
>USE jo_2024;

Once the database has been created, open a second terminal and go to this project root with the cd command.

Ex: 
> cd Documents/jo2024-bdd

You can now import the data of the project into your database by running : 

>mysql -u <*username*> -p < jo_2024_database.sql

Replace the <*username*> by your own username.





### phpMyadmin

create a new database named "jo_2024".
Then import the jo_2024_database.sql file to create its structure and import data.
That's it !




If you want to know how to recreate it, keep reading.

## 1: check database connection informations
Modify the PDO connection informations if necessary in the database_data_fill.php


## 2: start mysql in the terminal

use the following commands :

>mysql.server start
>
>mysql -u root -p 
>
>create database jo_2024
>
>use jo_2024

## 3 create the databse structure

copy paste the content of the structure_sql.text into your terminal. 
If it doesn't work properly, copy paste one portion of code at a time (one table at a time)

You successfully created the databse structure.

## 4 fill the database with data

open a new terminal. Then use "cd" to go into the folder "database_scripts" of this repository.

Once you are in the folder, run the following command: 

>php database_data_fill.php

The database should now be filled with data and no errors appeared in the terminal.
