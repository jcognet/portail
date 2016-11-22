DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
echo $DIR
cd $DIR
cd ..
echo "Pull de la branche master"
# Branche master
git branch master
# Pull
git pull
echo "Mise à jour de la base de données"
# Mise à jour de la base de données
bin/console doctrine:schema:update --dump-sql
bin/console doctrine:schema:update --force
# Assets
echo "Gestion des Assets"
bin/console assets:install --env=prod
#bin/console assetic:dump --env=prod
# Cache
echo "Cache"
bin/console cache:clear --env=prod
