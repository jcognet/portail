DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $DIR
./mep.sh master
cp ../web/
cp .htaccess.prod .htaccess