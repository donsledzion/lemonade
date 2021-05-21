# lemonade
Recruitment task for intership at Lemonmind.com

To run the application it had to be:
1) copied into php-enabled server directory
2) file "__empty__dbconfig.php" had to be renamed to "dbconfig.php" and filled with database hostname, base_name and credentials
3) in MySql database need to be created tables: file "/sql/database_creation.sql" consists SQL code to create and initially fill database,
4) server need to allow sending e-mails and uploading files
5) temproary database for testing is availble on my server. Credentials will be sent in e-mail
6) After initial setup application can be run from "index.html" file

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
Application completion progress:

1) Html + JS front-end form includes all requested content except "drag and drop files" field.
  1a) Files have to be selected trough traditional selection,
  1b) Front-end field doesn't care about file format. Not supported files are just ignored while uploading. Unfortunatelly, due to lack of time, I didn't complete to report error        reponse about it.
2) List of availble planes are automatically delivered from database, wich stores info about plane's name, deadweight (actually it's maximum single cargo weight), and contact e-mail,
2a) When plane selection occures all cargo weight fields are updated with maximum value.
2b) When plane is changed to the one that has lower weight limit, and cargo weight was set to higher value it's automatically "cut" to new limit.
3) Shipping Date field is front-end limited with "today" as a minimum value and prevents from selecting weekends. 
3a) Selecting weekends is prevented on back-end. I guess I've forgot to prevent selecting "previous dates" on back-end validation. Function validateShipping() need to be updated.
4) Fields "shipping from" and "shipping destination" are only validated (back-end) not to be empty.
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
6) As well as "shipping from" and "shippig destination" fields, "cargo's name" also requires only not to be empty.
7) As said above - cargo weight is front end checked to be:
  7a) not higher than plane type allows,
  7b) negative or zero value.
8) Cargo type is only back-end check to be selected.
9) Button "Add Cargo" adds new cago form. It shoud be front-end prevented from adding new cargos before completing previous, but due to lack of time I didn't menage to make it.
10) Data is stored in two separate forms - one consists files, the other all other data.
11) Sending data takes place in two stages:
  11a) first: data except files is sent to api and validated, when validation succeeds, e-mail is sent on proper recipient, then it goes to next stage:
  11b) second: files are uploaded:
      11*) - api.php/Docs/$id endpoint counts amount of selected filest. $id is value returned from first request - it's database ID of inserted shipping,
      11*) - all filest except specified extensions are ignored (I didn't make it on time to prepare messaging of "not supported files")
      11*) - files are sent to server into specified directory - it's set up to upload files to '/attachments/' directory
      12*) - uploaded filest have hashed (md5) names to prevent overwriting existing files
      13*) - info about filest are inserted to database: original name, hashed name, id of shipping related with files
  11c) Ech... It's humiliating, but... Files are only on server and database. I stuck for too much time with handling "api => front-edn" responses and didn't make it to complete attaching files to e'mail. ( You can notice time of commiting repository and editing this file...)
 12) I misunderstood task at first and I thought that application was supposed to store shipping data in database - not only message.
 13) However messages are not stored in database - all data about them is. From now - creation of e-mail, including attachments, is pretty easy.

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Summary:
Most of tasks are completed.
I've fail at:
- sending filest within e-mail
- making "drag and drop" file field
- storing credentials in dotEnv file,
Of course I'm not proud of visual appearance of front and as well as orginising code.
But, in my defence :) it was my first expirience with both: JS (except some simple slider made with "step-by-step" tutorial) and REST api.

Despite that it may not appear so - I've leard a lot while working on this task.

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  



