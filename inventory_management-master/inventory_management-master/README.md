# inventory_management
The proposed Inventory management system has two types of user’s admin and client. Admin users have access to all features, but the client will only have access to their product pages. We have have the over view of each page one by one. In this study have created a special feature where we have added a backend script which will check the database every 30 minutes if the stocks are under count 5 it will send the email to client about their stock refilling. 

You need to follow these steps to setup the project
First you need to import the database, named as automotive_inventory.

After that go to the main folder of inventory_management to install composer 

if you dont have composer installed then you can direclty download it from here
https://getcomposer.org/download/

After that go to the crons folder and run npm install in the terminal to install dependecies which will run a schedular to run your email script

You also need to change the db configurations in the config.php file and also in your sendAlert.php file.

I have attached a Demonstration video of the website.





https://github.com/puneetkumar8799/Inventory-Management/assets/114031348/12eb019e-8353-40d4-94c2-c19579db6271

