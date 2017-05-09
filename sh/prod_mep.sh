DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $DIR
./mep.sh master
cd ../web/
cp .htaccess.prod .htaccess