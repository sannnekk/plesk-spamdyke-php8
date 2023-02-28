#!/bin/bash

PATH="/sbin:$PATH"
export PATH

SPAMDYKEDETAILS=( `whereis spamdyke` )

for sddata in ${SPAMDYKEDETAILS[@]} 
 do
   if [[ "$sddata" = *".conf" ]]; then
     SPAMDYKE_CONF=${sddata////\\/}
   fi
   if [[ "$sddata" = *"bin"* ]]; then
     SPAMDYKE_BIN=${sddata////\\/}
   fi
done

OLD="\/var\/qmail\/bin\/qmail-smtpd";
NEWPLAIN=" $SPAMDYKE_BIN \-f $SPAMDYKE_CONF \/var\/qmail\/bin\/qmail-smtpd"
NEWSSL=" $SPAMDYKE_BIN \-f $SPAMDYKE_CONF \-\-tls-level=smtps \/var\/qmail\/bin\/qmail-smtpd"

DPATH="/etc/xinetd.d/s*_psa"
BPATH="/etc/xinetd.d/backup"
TFILE="/tmp/out.tmp.$$"
touch $TFILE
TODAY=`date +%Y-%m-%d-%H:%M`
RESTARTQMAIL="false"
[ ! -d $BPATH ] && mkdir -p $BPATH || :
for f in $DPATH
do
  if [ -f $f -a -r $f ]; then
  
    ISSPAMDYKE=`egrep "spamdyke" $f`
   
 
    if [ -n "$ISSPAMDYKE" ]; then
      echo "[$TODAY] ALLES OK - Spamdyke in $f vorhanden" >> /var/log/spamdyke.conf.log
      continue
    fi

    NEW=$NEWPLAIN

    if [[ "$f" = *"smtps"* ]] ;then 
      NEW=$NEWSSL
    fi
    
    echo "[$TODAY] ERROR SPAMDYKE FEHLT - Spamdyke wieder in $f integriert" >> /var/log/spamdyke.conf.log
    RESTARTQMAIL="true"
    /bin/cp -f $f $BPATH
    sed "s/$OLD/$NEW/g" "$f" > $TFILE && mv $TFILE "$f"
  else
   echo "Error: Cannot read $f"
  fi
done
/bin/rm $TFILE

if [ "$RESTARTQMAIL" = "true" ]; then
/etc/init.d/qmail restart
/etc/init.d/xinetd restart
fi
