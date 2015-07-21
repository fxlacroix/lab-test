#!/bin/bash

php app/console doctrine:database:drop --connection=resume --force
php app/console doctrine:database:create --connection=resume 
php app/console doctrine:schema:update --em=resume --force
php app/console doctrine:fixtures:load --em=resume -n
