DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $DIR
git checkout ../web/.htaccess
./mep.sh master
cd ../web/
cp .htaccess.prod .htaccess