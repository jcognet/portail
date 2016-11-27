DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $DIR
DATE=$(date +"%Y_%_m_%d_%H_%M_%S")
./mep.sh dev > ../backup/"$DATE"_mep.txt
value=`cat ../backup/"$DATE"_mep.txt`
echo "$value"
