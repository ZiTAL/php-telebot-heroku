php-telebot-heroku
==================
PHP Telegram BOT for Heroku

EDIT: **config.json** with **Heroku APP ID** and **TELEGRAM API KEY**

EDIT: **lib/telegram.php** to write your commands

Get Telegram API Key: https://core.telegram.org/bots#botfather

GNU/Linux Instructions:
=======================

Create an account in Heroku:
https://www.heroku.com

Create a new app in Heroku:
https://dashboard.heroku.com/new

Install Heroku
```
su
wget -O- https://toolbelt.heroku.com/install-ubuntu.sh | sh
exit
```

Download project:
```
mkdir -p /home/projects
cd /home/projects
git clone https://github.com/ZiTAL/php-telebot-heroku.git
cd /home/projects/php-telebot-heroku
rm -rf .git
```

Connect the project to Heroku's git:
```
heroku login
heroku create
git init
```
Edit **config.json** with **HEROKU APP ID** and **TELEGRAM API KEY**
Upload changes to Heroku and Deploy the application:
```
git add .
git commit -m "my first commit"
heroku git:remote -a HEROKU_APP_ID
git push heroku master
```

License
=======
GPLv3
