
Remote Weight Sensing Through ESP8266 : Technical Doc 
Prepared By : Virtual Cybertrons  Pvt . Ltd - August , 13 2021
Purpose of this document
Part 1: Providing the necessary inputs to deploy and make changes to code for the remote weight sensing tool. 
Part 2: Providing Steps to connect the device to Wifi Network.

Server Side Setup
The tool needs a remote server to push data. This can be any POST or GET api coded in any tech stack capable of receiving the data. For this particular prototype we have used core php hosted on a linux VM.
To start, please set up a VM on any of the cloud providers like AWS, DO, GCP, etc. The following references can help setting up either Nginx or Apache. 

Install Linux, Apache, MySQL, PHP (LAMP) stack on Ubuntu 20.04
   OR
 Install Linux, Nginx, MySQL, PHP (LEMP stack) on Ubuntu 20.04 

Clone/Download code from github to root of your server

https://github.com/virtualCybertrons/Server-Code-ESP-Weigh-Machine

Make changes to the code as per your configuration 

Go to the following  files and change these four lines below //mysql installation with the details of your mysql installation; you may keep the dbname ‘iot’ or if changing it then change it accordingly in db .sql 
Files
Post-data-api.php
Chart.php
Post-data/a.php
Post-data/index.php
Lines to change 
$servername = "localhost"; 
$dbname = "iot";
$username = "";
$password = "";

Upload file “db.sql” to your mysql 
This reference can help you importing the DB file in case of issues Click Here For Help
Once you visit the ip of your server in the browser, It should show a Graph page.








Firmware Setup On Device 
Download the Arduino IDE from : https://www.arduino.cc/en/software

Clone/Download code from github
https://github.com/virtualCybertrons/Firmware-ESP-Weigh-Machine


Make changes to the code as per your configuration 
Open the downloaded file in Arduino IDE, make necessary setup as guided in this reference  and proceed with the following changes as per your configuration.
Go to line no 144 that is   http.begin("http://yoururl.com/post-data/");              //Specify request destination
Update the line with your FQDN for the web software and add /post-data as above to the url
Now connect the device to your computer's usb cable and click “upload” in arduino

Wifi Connection Setup On Device 
After successful setup of both Server and Device,  Connect the device to the Powersource.
The green Light indicates the connection status. If it's on the device is connected to the  server, in case it’s not : 


The Device will create its own wifi network.
Use your mobile or system to connect to the wifi network created by the Device.
After connecting try visiting any URL and the page will redirect you to the network Configuration Page. 
Select/Type in your desired Wifi Network SSID and provide the Password.
Click on Save/Submit and Wait For the Green Light to turn ON.

Thankyou for giving your time , for any help, suggestions or updates please mail us at : info@cybertrons.in

