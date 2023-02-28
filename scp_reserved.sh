#!/bin/bash

arr=($2)

typeset -i count
typeset -i counter

filname="$1_erg"
filname2="$1_pids"
count=${#arr[@]} 
counter=0
val=0
touch $filname
chgrp psaadm $filname
chmod 777 $filname
STRING=""
for place in ${arr[*]}; do
    whois $place |  grep -m1 ountry >> $filname &	
    #whois $place | grep -m1 -i ountry
    STRING=" $! $STRING"
done
echo $STRING > $filname2

sleep 10

allePids=(`cat $filname2`)
for place in ${allePids[*]}; do
    kill $place -9
done;
rm $filname2




