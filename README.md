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

Avec la CLI 
symfony server:start
Ou
symfony serve

Ensuite pour les lignes de commande dans le terminal : 
Sans la CLI 
Php bin/console … 

Avec la CLI 
Symfony console …

--------------------------------------------------------
Allez à la racine du projet : cd Bibliotheque

Installez toutes les dépendances nécessaire: composer install


Assurez vous que votre base de donnée correspond à la variable DATABASE_URL du fichier .env en ayant :
nom de la bdd : projetbibliotheque2,
id utilisateur pour la bdd : root,
mot de passe utilisateur pour la bdd: .

Supprimez les fichiers de migration présent dans ./Bibliotheque/migrations/Version2024...

Mettez à jour la bdd :
Symfony console make:migration 
Symfony console doctrine:migration:migrate

Un message d'avertissement apparaitra : Appuyez sur entrée

Vérifier que votre base de donnée soit bien mise à jour

Si c'est le cas lanez les fixtures :
php bin/console doctrine:fixtures:load
Vous aurez a nouveau un message d'avertissement : entrez yes et validez

Allez rentrer manuellement ces informations dans votre bdd :
Entité Plan 
Champ - name : Abonnement mensuel aux services de la bibliotheque
Champ - stripe_id : price_1P9BmNEU5sEBpGsdwg5Hk1hR
Champ - payment_link : https://buy.stripe.com/test_aEU5oj82Z50SahW8ww 

ps : Pour se projet la gestion de notre base de donnée se fait via phpMyAdmin, si vous utilisez un autre services veuillez vous réferer à sa docummentation officielle pour accomplir les actions ci-dessus

Lancez le serveur de symfony : symfony server:start
---------------------------------------------------
Utilisation de Stripe

Installez ngrok en suivant les instruction du site : https://ngrok.com/docs/getting-started/

Une fois l'installation effectué lancez la commande :
ngrok http liens-d'écoute-de-votre-serveur-symfony (ex : http://127.0.0.1:8000)

Lorsque ngrok ce sera lancé copiez la valeur du champ forwarding (ex : Forwarding            https://d57f-176-130-116-50.ngrok-free.app) et envoyez là au gestionnaire de la fonctionnalité de paiement stripe 

Il reviendra vers vous avec :
- la valeur du STRIPE_WEBHOOK_SECRET dans ./Bibliotheque/.env que vous devrez coller à la place de celle déjà présente

ATTENTION ! Faites bien attention à ce qu'il n'y a pas d'espace une fois que vous aurez collé la valeur du STRIPE_WEBHOOK_SECRET, il faut que ce soit comme ça "whsec_98uQ5o7EmKM55hm5wBbyGcN54BNM4BFD" et non comme ça "whsec_98uQ5o7EmKM55hm5wBbyGcN54BNM4BFD   "

Pour vous abonnez rendez-vous directement à la page : Notre bibliotheque

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


La configuration est terminé, vous pouvez maintenant essayer notre site !










