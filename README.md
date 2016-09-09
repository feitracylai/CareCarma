CareCarma Web Client Setup
------------------------------------------------------------------------------------------------------------------------
Steps:
- 1. Install git & set it as environment variable (https://git-scm.com/downloads) 
- 2. Install XAMPP and start Apache Server
- 3. run "git clone https://github.com/feitracylai/CareCarma.git" in C:\xampp\htdocs\ directory
- 4. localhost/carecarma will show homepage of CareCarma website
- 5. Install composer & set it as environment variable (https://getcomposer.org/doc/00-intro.md)
- 6. configure composer with following commands in C:\xampp\htdocs\CareCarma\carecarma directory via cmd  - (https://www.humhub.org/docs/guide-admin-installation.html)
	- composer global require "fxp/composer-asset-plugin:~1.1.1"
	- composer update
- 7.Enable "INTL_ICU_VERSION'
	- Open C:\xampp\php\php.ini to edit.
	- Remove semicolon from line ";extension=php_intl.dll"
	- Restart Apache server in XAMPP
- 8. Start Apache Server
- 9. localhost/carecarma -> Login will show set up page  (including Database Configuration) for CareCarma
	- Hostname : 127.0.0.1
	- Username : root
	- Password : <blank>
	- DatabaseName: carecarma  (create a new database in localhost/phpmyadmin)
- 10. change theme to carecarma in system administration menu