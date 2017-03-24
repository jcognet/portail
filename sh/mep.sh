# Récupération de la branche et controle de surface
BRANCHE=$1
echo "Branche de travail : $BRANCHE"
if [ "$BRANCHE" != "dev" ] && [ "$BRANCHE" != "master" ]
then
    echo "Branche non valide"
    exit
fi
# Recupération de l'environnement
ENV="prod"
if [ "$BRANCHE" == "dev" ] 
then
    ENV="dev"
fi
echo "Environnement Symfony : $ENV"
# Récupération du répertoire de travail
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
echo $DIR
cd $DIR
cd ..
echo "Back up de la base de données"
/usr/local/php7.0/bin/php bin/console admin:backup:create --env=$ENV
echo "Pull de la branche $BRANCHE"
# Changement de branche
git checkout $BRANCHE
# Pull
git pull
echo "Mise à jour de la base de données"
# Mise à jour de la base de données
/usr/local/php7.0/bin/php bin/console doctrine:schema:update --dump-sql --env=$ENV
/usr/local/php7.0/bin/php bin/console doctrine:schema:update --force --env=$ENV
# Assets
echo "Gestion des Assets"
/usr/local/php7.0/bin/php bin/console assets:install --env=$ENV
#bin/console assetic:dump --env=prod
# Cache
echo "Cache"
/usr/local/php7.0/bin/php bin/console cache:clear --env=$ENV
