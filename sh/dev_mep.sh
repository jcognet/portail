DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $DIR
DATE=$(date +"%Y_%_m_%d_%H_%M_%S")
mkdir ../backup/
./mep.sh dev > ../backup/"$DATE"_mep.txt
value=`cat ../backup/"$DATE"_mep.txt`
echo "$value"
# Envoi du mail
cd ..
bin/console mail:fichier "Mise en production -- dév" backup/"$DATE"_mep.txt
