# RetroShopSymfony
Pour Faker
symfony console doctrine:fixtures:load 

Installer bootstrap

 yarn add bootstrap

 yarn add @popperjs/core
 
 dans le assets/app.js
 mettre import 'bootstrap';

 dans assets/styles/app.scss
 mettre le @import '~bootstrap/scss/bootstrap'; (tout en haut)

pour yarn :
(1 fois c'est tout)
 npm i -g yarn   

 ensuite 

 yarn

 yarn run watch

 yarn add sass-loader@112.0.0 sass --dev

 Pour Pagination :
 composer require knplabs/knp-paginator-bundle



Installer bootstrap

 yarn add bootstrap

 yarn add @popperjs/core

 dans assets/styles/app.scss
 mettre le @import '~bootstrap/scss/bootstrap'; (tout en haut)
 yarn run watch


 // pour les erreur 

 https://symfony.com/doc/current/controller/error_pages.html

 modifié env /app_env



// vich uploader
https://github.com/dustin10/VichUploaderBundle/blob/master/docs/index.md

composer require vich/uploader-bundle

(si marche pas)
symfony composer install 

Charts(graphiques) :
composer require symfony/ux-chartjs
yarn install --force
yarn run watch
pour liip :

composer require liip/imagine-bundle

dans php.ini 
decommenter la ligne extension = gd
PUIS
symfony serve:stop

pour l'environement prod a chaque modif

symfony console cache:clear

