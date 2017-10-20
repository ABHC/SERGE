[1mdiff --git a/Graylog-install.sh b/Graylog-install.sh[m
[1mindex 9b0f918..db31dc4 100644[m
[1m--- a/Graylog-install.sh[m
[1m+++ b/Graylog-install.sh[m
[36m@@ -22,7 +22,7 @@[m [mUpdate_sys()[m
 {[m
 	apt-get -y update[m
 	apt-get -y upgrade[m
[31m-	echo -e "Mise à jour.......\033[32mDone\033[00m"[m
[32m+[m	[32mecho -e "Update.......\033[32mDone\033[00m"[m
 	sleep 4[m
 }[m
 [m
[36m@@ -47,7 +47,7 @@[m [mInstall_Apache2()[m
 	a2enmod headers[m
 	a2enmod proxy_wstunnel[m
 	systemctl restart apache2[m
[31m-	echo -e "Installation d'apache2.......\033[32mDone\033[00m"[m
[32m+[m	[32mecho -e "Installation of apache2.......\033[32mDone\033[00m"[m
 	sleep 4[m
 }[m
 [m
[36m@@ -55,7 +55,7 @@[m [mInstall_PHP()[m
 {[m
 	apt-get -y install php libapache2-mod-php php-mcrypt php-mysql php-cli[m
 	systemctl restart apache2[m
[31m-	echo -e "Installation de PHP.......\033[32mDone\033[00m"[m
[32m+[m	[32mecho -e "Installation of PHP.......\033[32mDone\033[00m"[m
 	sleep 4[m
 }[m
 [m
[36m@@ -79,7 +79,7 @@[m [mInstall_Mysql()[m
 [m
 	systemctl restart mysql[m
 [m
[31m-	echo -e "Installation de MySQL.......\033[32mDone\033[00m"[m
[32m+[m	[32mecho -e "Installation of MySQL.......\033[32mDone\033[00m"[m
 	sleep 4[m
 }[m
 [m
[36m@@ -265,7 +265,7 @@[m [mSecurity_app()[m
 		# Configuration letsencrypt cerbot[m
 		apt-get -y install python-letsencrypt-apache[m
 		letsencrypt --apache  --email $email -d esmweb.$domainName -d piwik.$domainName[m
[31m-		echo -e "Installation de let's encrypt.......\033[32mDone\033[00m"[m
[32m+[m		[32mecho -e "Installation of let's encrypt.......\033[32mDone\033[00m"[m
 		sleep 4[m
 [m
 		# Redirect http to https[m
[36m@@ -569,13 +569,13 @@[m [mSecurity_app()[m
 		sshport=""[m
 		while [ "$sshport" == "" ][m
 		do[m
[31m-			dialog --backtitle "Installation of monitoring server by Cairn Devices" --title "Choix d'un port pour la connexion SSH" \[m
[32m+[m			[32mdialog --backtitle "Installation of monitoring server by Cairn Devices" --title "Choosing a port for SSH connection" \[m
 			--inputbox "" 7 60 2> $FICHTMP[m
 			sshport=$(cat $FICHTMP)[m
 			test_port=$(netstat -paunt | grep :$sshport\ )[m
 			if [ "$test_port" != "" ][m
 			then[m
[31m-				echo -e "\033[31m / ! \ Attention ce port semble être utilisé / ! \ \033[0m"[m
[32m+[m				[32mecho -e "\033[31m / ! \ Warning this port seems to be used / ! \ \033[0m"[m
 				echo $test_port[m
 				sleep 4[m
 				sshport=""[m
[36m@@ -584,7 +584,7 @@[m [mSecurity_app()[m
 		ufw allow $sshport/tcp[m
 		sed -i "5 s/Port 22/Port $sshport/g" /etc/ssh/sshd_config[m
 		dialog --backtitle "Installation of monitoring server by Cairn Devices" --title "Changement port SSH" \[m
[31m-		--ok-label "Next" --msgbox "Le port SSH à été changé pour éviter les attaques automatiques. Veuillez en prendre note. \nNouveau port : $sshport   \nPour accéder au serveur en ssh : ssh -p$sshport $mainUser@$domainName" 9 66[m
[32m+[m		[32m--ok-label "Next" --msgbox "The SSH port has been changed to avoid automatic attacks. Please take note of it. \nNew port : $sshport   \nTo access the server in ssh : ssh -p$sshport $mainUser@$domainName" 9 66[m
 	}[m
 [m
 	Install_unattendedupgrades()[m
[36m@@ -924,7 +924,7 @@[m [mCleaning()[m
 	passnohash="0"[m
 	internalPass="0"[m
 	adminPass="0"[m
[31m-	echo -e "Cleaning .............\033[32mDone\033[00m"[m
[32m+[m	[32mecho -e "Cleaning.............\033[32mDone\033[00m"[m
 [m
 	usermod --expiredate 1 root[m
 	passwd -l root[m
[36m@@ -947,7 +947,7 @@[m [mDIALOG=${DIALOG=dialog}[m
 touch /tmp/dialogtmp && FICHTMP=/tmp/dialogtmp[m
 trap "rm -f $FICHTMP" 0 1 2 3 5 15[m
 $DIALOG --clear --backtitle "Installation of monitoring server by Cairn Devices" --title "Installation of monitoring server by Cairn Devices" \[m
[31m---menu "Bonjour, choisissez votre type d'installation :" 15 80 5 \[m
[32m+[m[32m--menu "Hello, choose your installation type :" 15 80 5 \[m
 "dedicated" "Dedicated installation" 2> $FICHTMP # TODO Mettre tout le dialog en anglais[m
 valret=$?[m
 choix=$(cat $FICHTMP)[m
[1mdiff --git a/Serge-install.sh b/Serge-install.sh[m
[1mindex 4c55cb5..4d3fcfe 100644[m
[1m--- a/Serge-install.sh[m
[1m+++ b/Serge-install.sh[m
[36m@@ -28,7 +28,7 @@[m [mUpdate_sys()[m
 {[m
 	apt-get -y update[m
 	apt-get -y upgrade[m
[31m-	echo -e "Mise à jour.......\033[32mDone\033[00m"[m
[32m+[m	[32mecho -e "Update.......\033[32mDone\033[00m"[m
 	sleep 4[m
 }[m
 [m
[36m@@ -54,7 +54,7 @@[m [mInstall_Apache2()[m
 	a2enmod headers[m
 	a2enmod proxy_wstunnel[m
 	systemctl restart apache2[m
[31m-	echo -e "Installation d'apache2.......\033[32mDone\033[00m"[m
[32m+[m	[32mecho -e "Installation of apache2.......\033[32mDone\033[00m"[m
 	sleep 4[m
 }[m
 [m
[36m@@ -78,7 +78,7 @@[m [mInstall_Mysql()[m
 [m
 	systemctl restart mysql[m
 [m
[31m-	echo -e "Installation de MySQL.......\033[32mDone\033[00m"[m
[32m+[m	[32mecho -e "Installation of MySQL.......\033[32mDone\033[00m"[m
 	sleep 4[m
 }[m
 [m
[36m@@ -86,7 +86,7 @@[m [mInstall_PHP()[m
 {[m
 	apt-get -y install php libapache2-mod-php php-mcrypt php-mysql php-cli[m
 	systemctl restart apache2[m
[31m-	echo -e "Installation de PHP.......\033[32mDone\033[00m"[m
[32m+[m	[32mecho -e "Installation of PHP.......\033[32mDone\033[00m"[m
 	sleep 4[m
 }[m
 [m
[36m@@ -102,7 +102,7 @@[m [mInstall_phpmyadmin()[m
 	phpenmod mcrypt[m
 	phpenmod mbstring[m
 	systemctl restart apache2[m
[31m-	echo -e "Installation de phpmyadmin.......\033[32mDone\033[00m"[m
[32m+[m	[32mecho -e "Installation of phpmyadmin.......\033[32mDone\033[00m"[m
 	sleep 4[m
 }[m
 [m
[36m@@ -1741,7 +1741,7 @@[m [mSecurity_app()[m
 		# Configuration letsencrypt cerbot[m
 		apt-get -y install python-letsencrypt-apache[m
 		letsencrypt --apache  --email $email -d $domainName -d rainloop.$domainName  -d postfixadmin.$domainName[m
[31m-		echo -e "Installation de let's encrypt.......\033[32mDone\033[00m"[m
[32m+[m		[32mecho -e "Installation of let's encrypt.......\033[32mDone\033[00m"[m
 		sleep 4[m
 [m
 		# Redirect http to https[m
[36m@@ -2069,13 +2069,13 @@[m [mSecurity_app()[m
 		sshport=""[m
 		while [ "$sshport" == "" ][m
 		do[m
[31m-			dialog --backtitle "Installation of Serge by Cairn Devices" --title "Choix d'un port pour la connexion SSH" \[m
[32m+[m			[32mdialog --backtitle "Installation of Serge by Cairn Devices" --title "Choosing a port for SSH connection" \[m
 			--inputbox "" 7 60 2> $FICHTMP[m
 			sshport=$(cat $FICHTMP)[m
 			test_port=$(netstat -paunt | grep :$sshport\ )[m
 			if [ "$test_port" != "" ][m
 			then[m
[31m-				echo -e "\033[31m / ! \ Attention ce port semble être utilisé / ! \ \033[0m"[m
[32m+[m				[32mecho -e "\033[31m / ! \ Warning this port seems to be used / ! \ \033[0m"[m
 				echo $test_port[m
 				sleep 4[m
 				sshport=""[m
[36m@@ -2084,7 +2084,7 @@[m [mSecurity_app()[m
 		ufw allow $sshport/tcp[m
 		sed -i "5 s/Port 22/Port $sshport/g" /etc/ssh/sshd_config[m
 		dialog --backtitle "Installation of Serge by Cairn Devices" --title "Changement port SSH" \[m
[31m-		--ok-label "Next" --msgbox "Le port SSH à été changé pour éviter les attaques automatiques. Veuillez en prendre note. \nNouveau port : $sshport   \nPour accéder au serveur en ssh : ssh -p$sshport $mainUser@$domainName" 9 66[m
[32m+[m		[32m--ok-label "Next" --msgbox "The SSH port has been changed to avoid automatic attacks. Please take note of it. \nNew port : $sshport   \nTo access the server in ssh : ssh -p$sshport $mainUser@$domainName" 9 66[m
 	}[m
 [m
 	Install_unattendedupgrades()[m
[36m@@ -2494,7 +2494,7 @@[m [mItsCert()[m
 		systemctl restart opendmarc[m
 		systemctl restart postgrey[m
 [m
[31m-		echo -e "Auto cert .............\033[32mDone\033[00m"[m
[32m+[m		[32mecho -e "Auto cert.............\033[32mDone\033[00m"[m
 	fi[m
 }[m
 [m
[36m@@ -2611,10 +2611,10 @@[m [mDIALOG=${DIALOG=dialog}[m
 touch /tmp/dialogtmp && FICHTMP=/tmp/dialogtmp[m
 trap "rm -f $FICHTMP" 0 1 2 3 5 15[m
 $DIALOG --clear --backtitle "Installation of Serge by Cairn Devices" --title "Installation of Serge by Cairn Devices" \[m
[31m---menu "Bonjour, choisissez votre type d'installation :" 15 80 5 \[m
[32m+[m[32m--menu "Hello, choose your installation type :" 15 80 5 \[m
 "dedicated" "Dedicated installation" \[m
 "Serveur mail" "Installation du serveur mail" \[m
[31m-"Mode dev" "Mode développeur" 2> $FICHTMP # TODO Mettre tout le dialog en anglais[m
[32m+[m[32m"Mode dev" "Developer mode" 2> $FICHTMP # TODO Mettre tout le dialog en anglais[m
 valret=$?[m
 choix=$(cat $FICHTMP)[m
 case $valret in[m
