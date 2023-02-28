#!/bin/bash

# greylist folder
greylist_path=$2

#go to greylist folder
cd $greylist_path


if [ "$1" = "create" ]; then
mkdir $3
chown qmaild:nofiles $greylist_path$3
chmod 777 $greylist_path$3
fi

if [ "$1" = "update" ]; then
mv $3 $4
fi

if [ "$1" = "delete" ]; then
rm $3 -r -f
fi

if [ "$1" = "create_rights" ]; then
touch $2/default_rights/$3
chmod 777 $2/default_rights/$3
echo "$4" > $2/default_rights/$3
fi

if [ "$1" = "update_rights" ]; then
mv $2/default_rights/$4 $2/default_rights/$5
thepath="${3/conf./}"
mv "$thepath"whitelist_ip_${4//./_} $thepath/whitelist_ip_${5//./_}
mv "$thepath"blacklist_ip_${4//./_} $thepath/blacklist_ip_${5//./_}
mv "$thepath"whitelist_senders_${4//./_} $thepath/whitelist_senders_${5//./_}
mv "$thepath"blacklist_senders_${4//./_} $thepath/blacklist_senders_${5//./_}
mv "$thepath"whitelist_recipient_${4//./_} $thepath/whitelist_recipient_${5//./_}
mv "$thepath"blacklist_recipient_${4//./_} $thepath/blacklist_recipient_${5//./_}
mv "$thepath"whitelist_rdns_${4//./_} $thepath/whitelist_rdns_${5//./_}
mv "$thepath"blacklist_rdns_${4//./_} $thepath/blacklist_rdns_${5//./_}
mv "$thepath"blacklist_keywords_${4//./_} $thepath/blacklist_keywords_${5//./_}

IN=$4

arr=$(echo $IN | tr "." "\n")
C=0
for x in $arr

do
    oldpath=$x/$oldpath
    if [ -z "$LAST0" ]; then
       LAST0=$x
    fi
    let "C += 1"  
done
oldpath="${oldpath//$LAST0\/}"

if [ "$C" -gt 2 ]; then
oldpath="conf.s/_recipient_/$oldpath"
else
oldpath="conf.d/_recipient_/$oldpath"
fi

IN=$5

arr=$(echo $IN | tr "." "\n")
C=0
for x in $arr

do
    newpath=$x/$newpath
    if [ -z "$LASTN" ]; then
       LASTN=$x
    fi
    let "C += 1"  
done
newpath="${newpath//$LASTN\/}"

if [ "$C" -gt 2 ]; then
newpath="conf.s/_recipient_/$newpath"
else
newpath="conf.d/_recipient_/$newpath"
fi


mkdir -p $thepath$newpath 
mv $thepath$oldpath$LAST0 $thepath$newpath$LASTN 

sed -i "s/${4//./_}/${5//./_}/g" "$thepath$newpath$LASTN"

touch $2/default_rights/UPDATE
chmod 777 $2/default_rights/UPDATE
echo "$4>$5" >> $2/default_rights/UPDATE

fi

if [ "$1" = "delete_rights" ]; then
rm $2/default_rights/$4
thepath="${3/conf./}"
rm "$thepath"whitelist_ip_${4//./_}
rm "$thepath"blacklist_ip_${4//./_}
rm "$thepath"whitelist_senders_${4//./_}
rm "$thepath"blacklist_senders_${4//./_}
rm "$thepath"whitelist_recipient_${4//./_}
rm "$thepath"blacklist_recipient_${4//./_}
rm "$thepath"whitelist_rdns_${4//./_}
rm "$thepath"blacklist_rdns_${4//./_}
rm "$thepath"blacklist_keywords_${4//./_}

IN=$4

arr=$(echo $IN | tr "." "\n")
C=0
for x in $arr

do
    newpath=$x/$newpath
    if [ -z "$LAST" ]; then
       LAST=$x
    fi
    let "C += 1"  
done
newpath="${newpath//$LAST\/}"

if [ "$C" -gt 2 ]; then
newpath="conf.s/_recipient_/$newpath"
else
newpath="conf.d/_recipient_/$newpath"
fi
    
rm $thepath$newpath$LAST

fi

exit