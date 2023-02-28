#!/bin/sh

if [ "$1" = "0" ]; then
wget http://www.haggybear.de/download/scp2_update.zip
fi

if [ "$1" = "2" ]; then
unzip -o scp2_update.zip
chmod 755 *.php *.sh *.gif *.jpg *.png *.css *.js *.svg lang flags -R
chmod 777 stats/ EXPIRES/ *.txt *.ttf
chown root:psaadm *.php *.sh *.gif *.png *.jpg *.css *.js *.svg lang flags -R
fi

if [ "$1" = "2a" ]; then
echo -e "$2" > config.inc.php
rm config.new.txt
fi

if [ "$1" = "3" ]; then
chmod 755 *.php *.sh *.gif *.jpg *.png *.css *.js *.svg lang -R
chmod 777 EXPIRES/ stats/ default_rights/ *.txt *.ttf
chown root:psaadm *.php *.sh *.gif *.jpg *.png *.css *.js *.svg lang -R
rm scp_update.zip scp2_update.zip
fi

