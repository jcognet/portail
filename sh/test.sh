echo "Déplcament racine du projet"
cd ..
echo "Copie de la base de données de développement"
bin/console clean:test --env=test
echo "Lancement des tests de l'application"
bin/phpunit.phar
