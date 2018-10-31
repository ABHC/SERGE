#!/bin/bash
#Change to Serge buffer directory
DIR=$(locate SERGE | grep /SERGE$)
cd $DIR || { echo "FATAL ERROR : cd command fail to go to SERGE directory"; exit 1; }
#Fetch all changes
git fetch --all --tags --prune
git pull
#Switch to the latest stable version
latestStable=$(git tag | grep "\-stable" | sort -V -r | cut -d$'\n' -f1)
git checkout $latestStable
#Synchronize changes with Serge operating directory
rsync -a --exclude='logs' --exclude='.git' --exclude='database_demo' --exclude='Graylog-install.sh' --exclude='README.txt' --exclude='Serge-install.sh' --exclude='extensions_tables' --exclude='web/js/piwik/piwik.js' $DIR /var/www/Serge/ || { echo 'FATAL ERROR in rsync action for ~/stableRepository/SERGE/'; exit 1; }
#Change owner
chown -R Serge:Serge /var/www/Serge/ || { echo 'FATAL ERROR in chown action for /var/www/Serge/'; exit 1; }
chown -R www-data:www-data /var/www/Serge/web/ || { echo 'FATAL ERROR in chown action for /var/www/Serge/web/'; exit 1; }
echo "Update to stable version success !"
