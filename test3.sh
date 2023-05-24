trap 'killall test.sh ; exit 1' 2 3
 
./test1.sh &
sleep 10
 
./test1.sh &
sleep 10

./test1.sh &
sleep 10

./test1.sh &
sleep 3600
trap 2 3
