Installation du Projet

Avoir installé les éléments suivants : 
Composer
Git
Version PHP >= 8.1  

Sur Windows installer scoop, dans le power shell lancer: 

Invoke-Expression (New-Object System.Net.WebClient).DownloadString('https://get.scoop.sh’)  
OU
Set-ExecutionPolicy RemoteSigned -Scope CurrentUser
irm get.scoop.sh | iex

Installer la Cli avec scoop :
scoop install symfony-cli

Création d’un projet symfony 6.4 avec composer
composer create-project symfony/skeleton:"6.4.*" monProjet


Création d’un projet avec la Cli en version Long Time Supported 
-- webapp installe symfony avec tous les packages de bases 
Symfony new Monprojet --version=lts --webapp

Avec la CLI 
symfony server:start
Ou
symfony serve

Ensuite pour les lignes de commande dans le terminal : 
Sans la CLI 
Php bin/console … 

Avec la CLI 
Symfony console …

Mettre à jour la bdd :
Symfony console make:migration 
Symfony console doctrine:migration:migrate


Lancer les fixtures :
php bin/console doctrine:fixtures:load


---------------------------------------------------
Utilisation de Stripe

Dans l'entité plan, ajouter manuellement (pas de fixtures pour ça) dans les champs :
- stripe_id : price_1P9BmNEU5sEBpGsdwg5Hk1hR
- payment_link : https://buy.stripe.com/test_aEU5oj82Z50SahW8ww

Lors du réglement de l'abonnement mettre impérativement dans les champs :
- E-mail : celui renseigné lors de l'inscription
- numéro carte bancaire : 4242424242424242
- Les autres champs peuvent-être rempli selon votre bon vouloir

Les controllers relatifs à l'abonnement sont :
- AccountController.php
- WebhookController.php

Les entités relatifs à l'abonnement sont :
- Plan.php
- Subscription.php
- Invoice.php
- Users.php










