# Récupération de la branche et controle de surface
BRANCHE=$1
echo "Branche de travail : $BRANCHE"
if ["$BRANCHE"!="dev" && "$BRANCHE"!="master"]
then
    echo "Branche non valide"
    exit
fi
# Récupération du répertoire de travail
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
echo $DIR
cd $DIR
cd ..
echo "Back up de la base de données"
bin/console admin:backup
echo "Pull de la branche $BRANCHE"
# Changement de branche
git checkout $BRANCHE
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
