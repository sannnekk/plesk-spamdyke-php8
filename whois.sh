#!/bin/bash
arr=($1)

for place in ${arr[*]}; do
    whois $place | grep ountry
done

