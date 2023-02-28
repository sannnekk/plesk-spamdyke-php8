#!/bin/bash

dataarray=($1)

if [ "${dataarray[0]}" = "readconf" ]; then
mysql -uadmin -p`cat /etc/psa/.psa.shadow` -e "SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));" 2> /dev/null
cat ${dataarray[1]}
fi

if [ "${dataarray[0]}" = "logrotates" ]; then
ls -la ${dataarray[1]}*
fi

if [ "${dataarray[0]}" = "readlog" ]; then
${dataarray[1]} ${dataarray[2]} | grep ' spamdyke' | grep -v ERROR | grep -E ${dataarray[3]}
fi

if [ "${dataarray[0]}" = "readlists" ]; then
${dataarray[1]} ${dataarray[2]}
fi

if [ "${dataarray[0]}" = "setgl" ]; then
mkdir ${dataarray[1]}/${dataarray[2]}
chown qmaild:nofiles ${dataarray[1]}/${dataarray[2]}
chmod 777 ${dataarray[1]}/${dataarray[2]}
fi

if [ "${dataarray[0]}" = "delgl" ]; then
rm ${dataarray[1]}/${dataarray[2]} -r -f
fi

if [ "${dataarray[0]}" = "writeconf" ]; then
echo "$2" > ${dataarray[1]}
fi

if [ "${dataarray[0]}" = "writespamdykeconf" ]; then
echo "$2" > ${dataarray[1]}
fi

if [ "${dataarray[0]}" = "killallspamdyke" ]; then
killall spamdyke
fi

if [ "${dataarray[0]}" = "spamdykeversion" ]; then
/usr/local/bin/spamdyke -v &> "spamdyke.ver"
cat spamdyke.ver
fi

if [ "${dataarray[0]}" = "reports_on" ]; then
touch /etc/cron.daily/scp_reports
chmod 755 /etc/cron.daily/scp_reports
chown root:root /etc/cron.daily/scp_reports
echo "#!/bin/sh" > /etc/cron.daily/scp_reports;
echo " " >> /etc/cron.daily/scp_reports;
echo "#Daily SCP report" >> /etc/cron.daily/scp_reports;
echo "cd $2" >> /etc/cron.daily/scp_reports; 
echo "$3" >> /etc/cron.daily/scp_reports;
fi

if [ "${dataarray[0]}" = "expires_on" ]; then

if [ ! -f /etc/cron.daily/scp_expires ]; then

touch /etc/cron.daily/scp_expires
chmod 755 /etc/cron.daily/scp_expires
chown root:root /etc/cron.daily/scp_expires
echo "#!/bin/sh" > /etc/cron.daily/scp_expires;
echo " " >> /etc/cron.daily/scp_expires;
echo "#Daily SCP expire" >> /etc/cron.daily/scp_expires;
echo "cd $2" >> /etc/cron.daily/scp_expires; 
echo "$3" >> /etc/cron.daily/scp_expires;

fi

fi

if [ "${dataarray[0]}" = "reports_off" ]; then
rm /etc/cron.daily/scp_reports
fi

if [ "${dataarray[0]}" = "touch" ]; then
touch ${dataarray[1]}
fi

if [ "${dataarray[0]}" = "mkdir" ]; then
mkdir ${dataarray[1]} -p
fi

if [ "${dataarray[0]}" = "delete" ]; then
rm ${dataarray[1]}
fi

if [ "${dataarray[0]}" = "writeconfigdir" ]; then
echo "$2" > ${dataarray[1]}
fi

if [ "${dataarray[0]}" = "spamdyke5config" ]; then
perl -p -i -e 's/^reject-missing-sender-mx/reject-sender=no-mx/g' ${dataarray[1]}
perl -p -i -e 's/^rejection-text-missing-sender-mx/rejection-text-sender-no-mx/g' ${dataarray[1]}
perl -p -i -e 's/^reject-identical-sender-recipient/reject-recipient=same-as-sender/g' ${dataarray[1]}
perl -p -i -e 's/^rejection-text-identical-sender-recipient/rejection-text-recipient-same-as-sender/g' ${dataarray[1]}
perl -p -i -e 's/^local-domains-file/qmail-rcpthosts-file/g' ${dataarray[1]}
perl -p -i -e 's/^local-domains-file/qmail-rcpthosts-file/g' ${dataarray[1]}
perl -p -i -e 's/^local-domains-entry//g' ${dataarray[1]}
touch "_s.5.0.0"
fi

if [ "${dataarray[0]}" = "readmailprops" ]; then
  /usr/local/psa/bin/mail -i ${dataarray[1]}
  if [ "`/usr/local/psa/admin/bin/spammng --status`" = "is running" ]; then 
      /usr/local/psa/bin/spamassassin -i ${dataarray[1]}
  fi
fi

if [ "${dataarray[0]}" = "togglespamassassin" ]; then
  /usr/local/psa/bin/spamassassin -u ${dataarray[1]} -status ${dataarray[2]} -personal-conf ${dataarray[2]}
fi

if [ "${dataarray[0]}" = "spamdykeWatchdog_on" ]; then
touch /etc/cron.d/checkspamdyke
chmod 755 /etc/cron.d/checkspamdyke
chown root:root /etc/cron.d/checkspamdyke
echo "*/30 * * * * root $2/$3 2>&1 >/dev/null" > /etc/cron.d/checkspamdyke;
fi

if [ "${dataarray[0]}" = "spamdykeWatchdog_off" ]; then
rm /etc/cron.d/checkspamdyke
fi

if [ "${dataarray[0]}" = "analyze" ]; then

FINDENTRY=""
COMMAND=""

DASDATUM=`echo ${dataarray[4]} | sed "s/_/ /g"`

for i in `ls -1 ${dataarray[1]}*`; do
  if [[ "$i" = *".gz" ]]; then
    FIRSTTAG=`zcat $i | grep "$DASDATUM" | grep "to: ${dataarray[2]}" | grep "from: ${dataarray[3]}"`
    COMMAND="zcat"
  else
    FIRSTTAG=`cat $i | grep "$DASDATUM" | grep "to: ${dataarray[2]}" | grep "from: ${dataarray[3]}"`
    COMMAND="cat"
  fi
if [ -n "$FIRSTTAG" ]; then
    break;
fi
done

if [[ "$FIRSTTAG" == *ALLOWED* ]]; then
    PATTERN=`echo $FIRSTTAG | cut -d':' -f 11 | cut -d'_' -f 3`
    PATTERNII=$PATTERN
    count=1
    while [ $count -le 10 ]
    do
          PATTERNI=$((PATTERN+count))
          count=$[$count+1]  
          PATTERNII="$PATTERNII.|$PATTERNI"
    done

    SECONDTAG=`$COMMAND $i | grep -E "$PATTERNII." | grep "starting delivery" | grep ${dataarray[2]}`

    PATTERN2=`echo $SECONDTAG | cut -d' ' -f 9 | sed "s/://g"`

    echo $FIRSTTAG
    $COMMAND $i | grep -E "$PATTERNII." | grep "delivery $PATTERN2"
else
    echo $FIRSTTAG
fi
    
fi




