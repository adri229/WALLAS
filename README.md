WALLAS
=====
####Wallet Assistant

Wallas (acronym for Wallet Assistant) is a web application of accounting that helps users keep track of their revenues and spendings, visualize the relationship between revenues and spendings, see instantly what your current balance or know what is spending more money. 

The web application features a simple interface with which the user can enter, edit or delete your revenues, spendings, stocks and types of expenses, you can also assign these types of spendings to the spendings. In addition, you can display these data based on filters by date ranges, searches by name and sort data for properties. 

Main pages displays three graphs available to the application to provide information of interest to the user. These graphics will be generated depending on the range of dates that the user decides.


### Installation
```
sudo apt-get update

sudo apt-get install apache2

sudo apt-get install mysql-server

/*For install MySQL, enter this credentials:
  Username:root
  Password: root
*/

sudo add-apt-repository ppa:ondrej/php

sudo apt-get update

sudo apt-get install php7.0

sudo apt-get install php7.0-mysql

sudo apt-get install git

sudo a2enmod rewrite

cd /etc/apache2/

/*Edit the next file */
sudo nano apache2.conf

/*Acceder a la sección: */
<Directory /var/www/>
  Options Indexes FollowSymLinks
  AllowOverride None
  Require all granted
</Directory>

/*And change the line AllowOverride None for AllowOverride all*/
<Directory /var/www/>
  Options Indexes FollowSymLinks
  AllowOverride all
  Require all granted
</Directory>

/* Save the changes (Ctrl + o) and exit(Ctrl + x)*/
sudo service apache2 restart

cd /var/www/html/

sudo rm index.html

sudo git clone https://github.com/adri229/wallas.git

cd wallas/database/

mysql -u root –p

/*Enter the password*/
SHOW VARIABLES LIKE 'validate_password%';

/*If policy is medium, run next line*/
SET GLOBAL validate_password_policy=LOW;

/*Rerunning the previous line to verify that the password policies of MySQL were changed*/

source database.sql;

exit;
```

