## RM2
Very basic application for recipes. Nothing interesting indeed.

University related project.

## Run on localhost
Install libs with composer

    cd RecipeManager2
    php composer.phar install

Edit app/config/parameters.yml

Create database and schema

    php app/console doctrine:database:create
    php app/console doctrine:schema:create

Load fixtures(optional)

    php app/console doctrine:fixtures:load

Run with PHP 5.4 internal server

    php app/console server:run