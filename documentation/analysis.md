

Analyse des solutions existantes
================================
----------
Nagios
------
Nagios est un outil de monitoring très utilisé et très répandu. il possède une assez grande communauté francophone

 - Son installation est assez fastidieuse et nécessite la configuration de nombreux fichiers


|Avantages|Inconvénients|
| ------------ | ------------ |
|        Plugin dans tous les langages        |      Peu complet sans plugin|
|        Très paramétrable        |      L'interface est dépassée|
|     Communauté existante    |      Installation fastidieuse|
|        Interface WEB     |      Nombreux fichiers à configurer|

Parmi les fonctionnalités que proposent Nagios, on retrouve :

 - la surveillance des services (SMTP, POP3, HTTP, FTP, ...)
 - la surveillance des ressources d'une machine (la charge du processeur, l'espace disque, ...)
 - la possibilité de développer ses propres plugins
 - la hiérarchisation des équipements composant le réseau
 - la notification par email
 - la journalisation des évènements

Il existe deux modes d'utilisation : enregistrement dans une base de donnée ou dans des fichiers text.

![Nagios interface](https://dvas0004.files.wordpress.com/2010/03/nagios_interface1.png)
----------
Centreon
--------
Basé sur Nagios, amélioration de l'interface et des fonctionnalités

|Avantages (par rapport à Nagios)|
| ------------ |
|Interface à jour|
|Simplification de la configuration|

![Centreon interface](https://www.supinfo.com/articles/resources/214698/2361/1.png)
----------
Zabbix
------
Concurrent de Nagios et MRTG

|Avantages|Inconvénients|
| ------------ | ------------ |
| |Temps de formation |
| | Interface complexe|

![Zabbix interface](https://www.zabbix.com/documentation/3.0/_media/manual/web_interface/dashboard.png?w=600&tok=ae0cb1)
----------
Xymon
-----
Xymon est un logiciel libre de monitoring basé sur le logiciel "Big Brother", son utilisation implique l'installation du client sur les machines à surveiller.

|Avantages|Inconvénients|
| ------------ | ------------ |
|Interface WEB|Interface dépassée|
|Facile à installer||
|Agent multiplateforme||

![Xymon interface](https://camo.githubusercontent.com/71bf5f9a571fbe68c83711a37bd334a0de837a15/68747470733a2f2f7261772e6769746875622e636f6d2f6d617263696e64756c616b2f76616772616e742d78796d6f6e2d7475746f7269616c2f6d61737465722f73637265656e73686f74732f78796d6f6e2e706e67)
----------
## Conclusion ##
Selon les avantages et inconvénients listés, la plupart des solutions existantes sont :
 - Trop complexe à configurer
 - Une interface compliquée ou dépassée

----------


Différentes sources :
---------------------

* http://igm.univ-mlv.fr/~dr/XPOSE2010/supervision/nagios.html
* https://www.supinfo.com/articles/single/3124-comparaison-outils-supervison
