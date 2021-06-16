#Lab2view Test


##Installation

`npm install -g laravel-echo-server maildev`

`php -r "copy('.env.example', '.env');"`

`composer install`

`php artisan key:generate`

// optional

`npm install`

`nom run dev`

##Execution

Ensure that your redis server is up

Open your 4 instances of terminal in project root and execute fellow commands on each 

`php artisan serve`

`laravel-echo-server start`

`maildev`

// This is to make queue work

`php artisan queue:work --daemon`

// Optional if you want to keep it work even if the terminal is closed

`nohup php artisan queue:work --daemon &`
