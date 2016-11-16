# laravel-elasticsearch-example
An example of Laravel integration with Elasticsearch


#Installation

##Clone repository

#####`git clone https://github.com/gradosevic/laravel-elasticsearch-example elastic-example`

##Download composer packages

#####`composer install`

##Set configuration

- Open .env or create a copy from .env.example
- Change DB settings to match yours
- Set ELASTIC settings for your elastic search repository

##Run migrations

#####`php artisan migrate`

#App commands
These commands could be useful for testing:

#####`php artisan app --seed` - clears addresses table and seeds dummy addresses
#####`php artisan app --index` - sends data from database to Elasticsearch
#####`php artisan app --clear` - clears all data from addresses table and Elasticsearch
#####`phpunit` - runs PHP tests
