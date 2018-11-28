# Quickcard Payment Library for Laravel 5

<img src="http://www.darwinbiler.com/assets/unstable.svg" class="img-responsive"> <img src="http://www.darwinbiler.com/assets/license.svg" class="img-responsive">

Just install the package, add .env file and its ready to use.

**Introduction**
>This is the library required already [composer](https://getcomposer.org/) installed in your machine, Locally or Globally.

**Requirements**
>*PHP version 7.'\*'*\
*cURL Extension*\
*Laravel >= 5.2*

**Installation**
>composer require quickcard/checkout:dev-master

**Service Provider**
>Quickcard\Checkout\CheckoutServiceProvider

**License**
>This project is open-sourced software licensed under the MIT license

**Sample Project**\
http://sample-project-link.com/repository

**Documentation**
- Open Terminal
- Go to your project directory using 'cd' "path/to/your/project/my-project"
- Paste command "composer require quickcard/checkout:dev-master", it will add the package in vendor directory
- Open config/app.php add Service Provider in providers array
- Open .env file add constants Mode, CLIENT_ID, and SECRET_KEY in it
- Open your controller add "use Quickcard\Checkout\Payment;" or you can make your Facade and "use your-facade-alias"