DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
echo $DIR
cd $DIR
cd ..
echo "Pull de la branche master"
# Branche master
git checkout dev
# Pull
git pull
echo "Mise à jour de la base de données"
# Mise à jour de la base de données
php bin/console doctrine:schema:update --dump-sql
php bin/console doctrine:schema:update --force
# Assets
echo "Gestion des Assets"
php bin/console assets:install --env=prod
#bin/console assetic:dump --env=prod
# Cache
echo "Cache"
php bin/console cache:clear --env=prod
chmod 777 -R .
