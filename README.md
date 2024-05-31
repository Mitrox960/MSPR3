Backend :
Créer BDD MySQL et la nommer "arosaje"
Configurer le fichier .env pour accéder à la bdd
php artisan migrate
php artisan serve --host=0.0.0.0

Frontend (une fois le back lancé) :
Récupérer l'adresse IP de la machine sur la quelle il est lancé, et la mettre dans le .env
npx expo start (à la racine du projet front)

Si fonctionne pas : npm install expo et refaire npx expo start






Scannez le QR Code depuis votre téléphone android avec l'application "Expo GO"

Note : Le téléphone doit être sur le même réseau que le serveur front et back, et votre serveur de bdd doit être lancé (WAMP par exemple)

Plan de sauvegarde de la BDD :


Sauvegarde de la structure de la bdd et des données :
mysqldump -u root -p arosaje > backup.sql

A la création d'une nouvelle BDD vide, importer la sauvegarde :
mysql -u root -p arosaje < backup.sql
