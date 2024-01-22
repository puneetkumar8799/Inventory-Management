# inventory_management
This is an inventory management system in which two roles are assigned admin and client. CLients are the one who have there project listed in the market. It has a special feature which 
will send alert to the clients if certain project is out of stock. Display it in gui and also send an email alert as well.

You need to follow these steps to setup you project
First you need to add the import the database named as automotive_inventory
After that go to the main folder of inventory_management to install composer 

if donot have composer installed you these commands to install it

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

If found any issue direclty download it from here
https://getcomposer.org/download/

After that do to the crons folder and run npm install to install dependecies which will run a schedular to run you email script

You also need to change the db configurations in the config.php file and also in sendAlert.php

if found any issue contact me as naveed.ahmed6453@gmail.com

I have attached the demo pictures below
![productlist](https://github.com/malikdesigner/inventory_management/assets/105503471/19b30bf2-7ce4-4f93-a985-20ef838d9169)
![marketplace](https://github.com/malikdesigner/inventory_management/assets/105503471/9e9eab68-1917-4c70-b9e6-5809d344c9fa)
![checkout](https://github.com/malikdesigner/inventory_management/assets/105503471/5f944199-46e4-4f63-b8e5-3fec9ce46102)
![addProduct](https://github.com/malikdesigner/inventory_management/assets/105503471/bc37cdd6-92fe-4fb8-a305-5611a77a7422)
