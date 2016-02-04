REST API based on Symfony 2
===========================

[![Total Downloads](https://poser.pugx.org/staegi/symfony-rest-api/downloads)](https://packagist.org/packages/staegi/symfony-rest-api) [![Latest Stable Version](https://poser.pugx.org/staegi/symfony-rest-api/version)](https://packagist.org/packages/staegi/symfony-rest-api) [![License](https://poser.pugx.org/staegi/symfony-rest-api/license)](https://packagist.org/packages/staegi/symfony-rest-api)

This is a nice start point to create an application which provides a REST interface to your database.

#Requirements

* git
* MySQL
* PHP 5.4 or higher
* Apache 2 or Nginx
* Composer
    
#Installation

Create project with vendors:

    curl -sS https://getcomposer.org/installer | php
    composer.phar create-project staegi/symfony-rest-api
    cd symfony-rest-api
    
Create development database:
    
    php bin/console doctrine:database:create -e dev
    php bin/console doctrine:schema:create -e dev

Load fixtures to create first client and user:

    php bin/console doctrine:fixtures:load

###Preconfigured vendor modules 

* [FOSOAuthServerBundle](https://packagist.org/packages/friendsofsymfony/oauth-server-bundle): Provides token and user authorization with OAuth2
* [FOSUserBundle](https://packagist.org/packages/friendsofsymfony/user-bundle): Provides user and group management 
* [FOSRestBundle](https://packagist.org/packages/friendsofsymfony/rest-bundle): Provides various tools to rapidly develop RESTful API's with Symfony
* [NelmioApiDocBundle](https://packagist.org/packages/nelmio/api-doc-bundle): Generates documentation for your REST API from annotations
* [NelmioCorsBundle](https://packagist.org/packages/nelmio/cors-bundle): Adds CORS (Cross-Origin Resource Sharing) headers support in your Symfony2 application
* [JMSSerializerBundle](https://packagist.org/packages/jms/serializer-bundle): Allows you to easily serialize, and deserialize data of any complexity
* [LiipMonitorBundle](https://packagist.org/packages/liip/monitor-bundle): Provides an interface for monitoring 
* [DoctrineBundle](https://packagist.org/packages/doctrine/doctrine-bundle): Provides an ORM database layer
* [DoctrineFixturesBundle](https://packagist.org/packages/doctrine/doctrine-fixtures-bundle): Provides fixtures to load data for initial installation and acceptance tests 

#Documenttation

The API documentation is shown under `api.your-domain.com/doc`. 

#Monitoring

The monitoring is running under `api.your-domain.com/monitor/`

#Unit tests

Create test database:

    php bin/console doctrine:database:create -e test
    
Run the unit tests with:

    bin/phpunit

For the acceptance test you need a test database. Set `test_database_name`, `test_database_user` and `test_database_password` in your `parameters.yml`.
