CareCarma Web Client Setup in Windows System with XAMPP
------------------------------------------------------------------------------------------------------------------------
Steps:
- 1. Download CareCarma-live.zip from \Wufei in Google drive 
- 2. Install XAMPP and start Apache Server and MySQL
- 3. Extract CareCarma-live.zip in C:\xampp\htdocs\ directory
- 4. localhost/carecarma will show homepage of CareCarma website
- 5. Install composer & set it as environment variable (https://getcomposer.org/doc/00-intro.md) 
- 6. configure composer with following commands in C:\xampp\htdocs\CareCarma\carecarma directory via cmd  - (https://www.humhub.org/docs/guide-admin-installation.html) : <br>
   composer global require "fxp/composer-asset-plugin:~1.1.1" <br>
   composer update
  - (option)access tokens will be generated in github account:
    - a. Login your github account
    - b. Go to setting page (https://github.com/settings/profile)
    - c. Click "Personal access tokens" in "Develop settings" session
    - d. Click "Generate new token" and input your password of this github account
    - e. Give a name in "Token description" and click "Generate token"
    - f. Copy the token and paste it 
- 7. Enable "INTL_ICU_VERSION'
  - Open C:\xampp\php\php.ini to edit
  - Remove semicolon from line ";extension=php_intl.dll"
  - Restart Apache server in XAMPP<br>
	still not work? try: https://github.com/humhub/humhub/commit/3e0a507ceaeeaa04045c8f87e1f9ebcf21ebb996
- 8. Change the file upload size
    - Open C:\xampp\php\php.ini to edit.
    - Change "post_max_size" equal to "10M"
    - Change "upload_max_filesize" equal to "10M"
- 9. Start Apache Server
- 10. Delete codes below in \carecarma\themes\CareCarma-4cacc6\views\layouts\head.php (line 9)<br>
	$userId = Yii::$app->user->id;<br>
    $user = \humhub\modules\user\models\User::findOne(['id' => $userId]);
- 11. localhost/carecarma -> Login will show set up page  (including Database Configuration) for CareCarma
  - Hostname : 127.0.0.1
  - Username : root
  - Password : <blank>
  - DatabaseName: carecarma  (create a new database in localhost/phpmyadmin)
- 12. Add codes below in \carecarma\themes\CareCarma-4cacc6\views\layouts\head.php (line 9)<br>
	$userId = Yii::$app->user->id;<br>
    $user = \humhub\modules\user\models\User::findOne(['id' => $userId]);
- 13. (Option) Configure Mailing Server
    - Go into Administration => Mailing => Server Settings (/carecarma/index.php?r=admin%2Fsetting%2Fmailing-server)
    - Add information and Save
    - If have "OpenSSL Error: SSL3_GET_SERVER_CERTIFICATE: certificate verify failed" when system send e-mails, add follow code in carecarma/protected/vendor/swiftmailer/lib/classes/Swift/Transport/StreamBuffer.php line 266 inside function "_establishSocketConnection()": <br>
       $options['ssl']['verify_peer'] = FALSE; <br>
       $options['ssl']['verify_peer_name'] = FALSE;
	
If you have some errors, please search in https://github.com/humhub/humhub/issues first.<br><br>

- Refernce of code:
  - http://www.yiiframework.com/doc-2.0/guide-README.html
  - https://www.humhub.org/docs/guide-README.html


CareCarma Web Client Setup in AWS Elastic Beanstalk (PHP platform)
------------------------------------------------------------------------------------------------------------------------
Steps:
- 1. Upload CareCarma-live.zip to Running Version and go into CareCarma folder (ex. /var/app/current/CareCarma)
- 2. Change the file upload size
    - Open /etc/php.ini to edit.
    - Change "post_max_size" equal to "10M"
    - Change "upload_max_filesize" equal to "10M"
    - Restart App Server in AWS Elastic Beanstalk dashboard
- 3. Configure composer in root with following commands in CareCarma (/var/app/current/CareCarma) directory via cmd  - (https://www.humhub.org/docs/guide-admin-installation.html) <br>
      composer.phar global require "fxp/composer-asset-plugin:~1.1.1" <br>
      composer.phar update
  - (option)access tokens will be generated in github account:
    - a. Login your github account
    - b. Go to setting page (https://github.com/settings/profile)
    - c. Click "Personal access tokens" in "Develop settings" session
    - d. Click "Generate new token" and input your password of this github account
    - e. Give a name in "Token description" and click "Generate token"
    - f. Copy the token and paste it 

- 4. Delete codes below in \carecarma\themes\CareCarma-4cacc6\views\layouts\head.php (line 9)<br>
	$userId = Yii::$app->user->id;<br>
    $user = \humhub\modules\user\models\User::findOne(['id' => $userId]);
- 5. Make sure the required PHP extensions (http://docs.humhub.org/admin-requirements.html)
- 6. Use browser to open the website (ex. http://carecarmas-env.us-east-1.elasticbeanstalk.com)/carecarma => Login will show set up page  (including Database Configuration) for CareCarma
  - Hostname : (ex. carecarmar.c81l6jttxgta.us-east-1.rds.amazonaws.com:3306)
  - Username : (ex. root)
  - Password : (ex. 2439Lona)
  - DatabaseName: (ex. carecarma)
- 7. Add codes below in \carecarma\themes\CareCarma-4cacc6\views\layouts\head.php (line 9)<br>
	$userId = Yii::$app->user->id;<br>
    $user = \humhub\modules\user\models\User::findOne(['id' => $userId]);
- 8. Add cron jobs in root with following command <br>
      crontab -e
    - Add "30 * * * * php /var/app/current/CareCarma/carecarma/protected/yii cron/hourly >/dev/null 2>&1
           00 22 * * * php /var/app/current/CareCarma/carecarma/protected/yii cron/daily >/dev/null 2>&1"
      Please make sure the folder is the same with the folder displayed in /carecarma/index.php?r=admin%2Fsetting%2Fcronjob
    - Save the crontab file and exit
    - Start/Restart cron jobs :<br>
      service crond restart
- 9. (option) If you want to keep users' uploaded images and files in the old version, please copy the whole files in /carecarma/uploads 
    - Folder "file" save the files and images, which users uploaded in Modules
    - Folder "profile_image" save the users' profile image
- 10. (option) Install, configure and enable modules on /carecarma/index.php?r=admin%2Fmodule%2Flist <br>
       Don't update Tasks, Mail and Custom Pages <br>
       Use follow steps to debug module "Files":
  - Add code in /modules/cfiles/controllers/MoveController.php <br>
            $rootFolder = $this->getRootFolder();   <line 46> <br>
            'rootFolder' => $rootFolder,    <line 52> <br>
            'rootFolder' => $rootFolder,    <line 87>
  - In /modules/cfiles/views/move/modal_move.php  <line 45><br>
            Replace id = "0" to "$rootFolder->id" 
- 11. (option) Calculate steps and heart rate data: 
    - Copy following files from Google drive /Wufei/python:
        - heartrate.py
        - mainHeartScheduler.py
        - mainSensorScheduler.py
        - sensor.py
    - Paste it in the web server (ex. /home/ec2-user)
    - Check the hardware id of the devices you want to keep tracking
    - Replace the array to your hardware_id array in heartrate.py (line 816) and sensor.py (line 991)
    - Add rows with your hardware_id in table "LastTimeReadHeart" and table "LastTimeReadSteps"<br>
      Make sure "datetime" is yesterday midnight (23:59:59) in the device's timezone, and "time" is 13 digit timestamp of "datetime" <br>
      You can check in https://www.epochconverter.com/
    - Use "screen" to run
        - Add one screen and run heart rate part: <br>
            screen -r <br>
            python mainHeartScheduler.py <br>
            ctrl+a+d
        - Add other screen to run steps part: <br>
          screen -r <br>
          python mainSensorScheduler.py <br>
          ctrl+a+d
        - The code will run at midnight in EST everyday.
       
	
If you have some errors, please search in https://github.com/humhub/humhub/issues first.<br><br>

- Refernce of code:
  - http://www.yiiframework.com/doc-2.0/guide-README.html
  - https://www.humhub.org/docs/guide-README.html