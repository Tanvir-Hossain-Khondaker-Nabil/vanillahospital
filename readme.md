This project uses the Laravel 5.7 framework. Actually this is starter Laravel 5.7 project. It contains user management system, including register, login, forgotten password, change password and other related functionalities. It also contains Basic admin panel and user roles implementation.
The project has also Access Control List implementation.
The project uses several modules:

This is a Accounting Software.

# Installation
First clone the project. Than run
    
    composer update 
    OR 
    composer install
    
Depending on your OS this command may be in different format.

## Configuration
Than you can create your .env file as it is in [Laravel 5 documentation](http://laravel.com/docs/master) or can use this sample:
    
    APP_ENV=local
    APP_DEBUG=true
    APP_KEY=your_key_here 

    DB_HOST=db_host
    DB_DATABASE=database_name
    DB_USERNAME=database_user
    DB_PASSWORD=database_password

    CACHE_DRIVER=array
    SESSION_DRIVER=file
    
    MAIL_FROM_NAME="Company Name"
    MAIL_FROM_ADDRESS=info@domain.com
    MAIL_FROM_NOREPLY=noreply@domain.com
    INFO_MAIL_ADDRESS=info@domain.com

Put your database host, username and password. ```EMAIL_ADDRESS``` is the application mailing service address. ```EMAIL_PASSWORD``` is the password for the mailbox. I am using this way of configuration due to the mail.php config file commit. I do not want to distribute my email and password ;).

For more details about the .env file, check [Laravel's documentation](http://laravel.com/docs/master) or just Google about it. There is a plenty of info out there.

## Run API Key Generate
    
    php artisan key:generate
   
   
## Run the migrations
First create your database and set the proper driver in the ```config/database.php``` file.
Use the Laravel's artisan console with the common commands to run the migrations. First cd to the project directory and depending from your OS run 
    
    php artisan migrate
   
    
    
## Add some dummy data
This project has seeders which provide the initial and some dummy data necessary for the project to run.
Use: 
    
    php artisan db:seed
     OR 
    php artisan migrate:refresh -seed
    
to run the migrations.


## For Autoload php files

    composer dump-autoload
    
    
## For Clear Cache and Storage
    
        php artisan config:clear
        php artisan cache:clear
    
    
## Your first login
You can login Using:

Email/username: admin@domain.com
password: admin

Email/username: superadmin@domain.com
password: superadmin

Email/username: developer@domain.com
password: developer

Email/username: master-member@domain.com
password: 123456

## About the user management
There are 5 roles in this application - super-admin, admin, developer master-member and user. Admin role can not be deleted or edited. All other roles can be edited. 
Users can be deleted if they do not have something that relates to them. If you have only one active admin user, he can not be deleted or deactivated.

## Settings (Admin panel)
There is a settings module in the admin panel. You can define your site name from here. This is the name that will be shown at the navigation tab in your browser. Also you can define the locales. Use standart 2 characters locale codes. The fallback locale is used as default if the user has no choose a language from the language menu.

## License

The Laravel framework is not open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
