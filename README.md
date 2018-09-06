# Petits comptes entre agris

Projet du hackathon [HackTaFerme](http://www.hacktaferme.com/).

## Présentation

L'application qui simplifie la gestion de l'entraide entre agriculteurs.

![Logo](https://github.com/ter-informatique/HackTaFerme-PetitsComptesEntreAgris/raw/master/public/images/logo-pwa-512.png)

https://hacktaferme.wizi.farm/

## OS utilisé pour héberger la solution

Linux

## Technologies utilisées

* PHP 7.2 
  * composer
* NodeJS
  * yarn
* Symfony 4.1
* MariaDB

## Éléments de paramétrage

Configurer les paramètres dans un fichier `.env` (voir `.env.dist`).

Le seul paramètre important est celui de la base de données : `DATABASE_URL`.

## Commandes (lancer / stopper / autre...)

Puis faire pour construire le projet :

* `make install`
* `make db`

Pour importer les données API-AGRO : `bin/console app:load-machinism-costs`

Pour lancer un serveur web :

* `bin/console server:run`
