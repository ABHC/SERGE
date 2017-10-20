#!/bin/bash

# Error log
exec 2> >(tee -a error.log)

# Variable declaration
sshport="22"

###############################
###   Function declaration  ###
###############################

Update_sys()
{
	apt-get -y update
	apt-get -y upgrade
	echo -e "Mise à jour.......\033[32mDone\033[00m"
	sleep 4
}

Install_dependency()
{
	apt-get -y install dialog
	apt-get -y install git
	apt-get -y install unzip
	apt-get -y install apt-transport-https
	apt-get -y install rsync
}

Install_Apache2()
{
	apt-get -y install apache2
	a2enmod ssl
	a2enmod rewrite
	a2enmod proxy
	a2enmod proxy_http
	a2enmod proxy_html
	a2enmod xml2enc
	a2enmod headers
	a2enmod proxy_wstunnel
	systemctl restart apache2
	echo -e "Installation d'apache2.......\033[32mDone\033[00m"
	sleep 4
}

Install_PHP()
{
	apt-get -y install php libapache2-mod-php php-mcrypt php-mysql php-cli
	systemctl restart apache2
	echo -e "Installation de PHP.......\033[32mDone\033[00m"
	sleep 4
}

Install_Mysql()
{
	echo "mysql-server mysql-server/root_password password $adminPass" | sudo debconf-set-selections
	echo "mysql-server mysql-server/root_password_again password $adminPass" | sudo debconf-set-selections
	apt-get -y install mysql-server

	# Secure MySQL installation
	mysql -u root -p${adminPass} -e "DELETE FROM mysql.user WHERE User=''"
	mysql -u root -p${adminPass} -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\_%'"
	mysql -u root -p${adminPass} -e "FLUSH PRIVILEGES"

	systemctl restart apache2

	echo "# Set engine in utf8 by default" >> /etc/mysql/mysql.conf.d/mysqld.cnf
	echo "skip-character-set-client-handshake" >> /etc/mysql/mysql.conf.d/mysqld.cnf
	echo "collation-server=utf8_unicode_ci" >> /etc/mysql/mysql.conf.d/mysqld.cnf
	echo "character-set-server=utf8" >> /etc/mysql/mysql.conf.d/mysqld.cnf

	systemctl restart mysql

	echo -e "Installation de MySQL.......\033[32mDone\033[00m"
	sleep 4
}

Install_Java()
{
	# Openjdk
	apt-get -y install openjdk-8-jre
}

Install_Elasticsearch()
{
	wget https://download.elastic.co/elasticsearch/release/org/elasticsearch/distribution/deb/elasticsearch/2.3.1/elasticsearch-2.3.1.deb
	dpkg -i elasticsearch-2.3.1.deb
	systemctl enable elasticsearch.service
	rm elasticsearch-2.3.1.deb

	# Configuration
	sed -i "s/# cluster.name: my-application/cluster.name: graylog/g" /etc/elasticsearch/elasticsearch.yml
	sed -i "s/# node.name: node-1/node.name: \"node 00\"/g" /etc/elasticsearch/elasticsearch.yml

	systemctl start elasticsearch
}

Install_MongoDB()
{
	apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv EA312927
	echo "deb http://repo.mongodb.org/apt/ubuntu xenial/mongodb-org/3.2 multiverse" | tee /etc/apt/sources.list.d/mongodb-org-3.2.list

	apt-get update
	apt-get install -y mongodb-org

	echo '[Unit]
Description=High-performance, schema-free document-oriented database
After=network.target

[Service]
User=mongodb
ExecStart=/usr/bin/mongod --quiet --config /etc/mongod.conf

[Install]
WantedBy=multi-user.target' > /etc/systemd/system/mongodb.service

	systemctl start mongodb
}

Install_Piwik()
{
	# Dependancy
	apt-get install -y php7.0-mbstring
	apt-get install -y php7.0-gd
	apt-get install -y libfreetype6-dev

	#Create database
	mysql -u root -p${adminPass} -e "CREATE DATABASE piwik;"
	mysql -u root -p${adminPass} -e "CREATE USER 'piwik'@'localhost' IDENTIFIED BY '$internalPass';"
	mysql -u root -p${adminPass} -e "GRANT USAGE ON *.* TO 'piwik'@'localhost';"
	mysql -u root -p${adminPass} -e "GRANT ALL PRIVILEGES ON piwik.* TO 'piwik'@'localhost';"

	# Installation of Piwik
	wget https://builds.piwik.org/piwik.zip
	unzip piwik.zip  -d /var/www/
	rm  piwik.zip
	rm /var/www/How\ to\ install\ Piwik.html
	mkdir /var/www/piwik/logs/

	chown www-data:www-data /var/www/piwik/ -Rf
	chmod 750 piwik/ -Rf

	# Apache2 configuration for Piwik
	echo "<VirtualHost *:80>" > /etc/apache2/sites-available/piwik.conf
	echo "ServerAdmin postmaster@$domainName" >> /etc/apache2/sites-available/piwik.conf
	echo "ServerName piwik.$domainName" >> /etc/apache2/sites-available/piwik.conf
	echo "ServerAlias piwik.$domainName" >> /etc/apache2/sites-available/piwik.conf
	echo "DocumentRoot /var/www/piwik/" >> /etc/apache2/sites-available/piwik.conf
	echo "# Pass the default character set" >> /etc/apache2/sites-available/piwik.conf
	echo "AddDefaultCharset utf-8" >> /etc/apache2/sites-available/piwik.conf
	echo "# Containment of piwik" >> /etc/apache2/sites-available/piwik.conf
	echo "php_admin_value open_basedir /var/www/piwik/" >> /etc/apache2/sites-available/piwik.conf
	echo "# Prohibit access to files starting with a dot" >> /etc/apache2/sites-available/piwik.conf
	echo "<FilesMatch ^\\.>" >> /etc/apache2/sites-available/piwik.conf
	echo "    Order allow,deny" >> /etc/apache2/sites-available/piwik.conf
	echo "    Deny from all" >> /etc/apache2/sites-available/piwik.conf
	echo "</FilesMatch>" >> /etc/apache2/sites-available/piwik.conf
	echo "<Directory /var/www/piwik/ >" >> /etc/apache2/sites-available/piwik.conf
	echo "AllowOverride All" >> /etc/apache2/sites-available/piwik.conf
	echo "</Directory>" >> /etc/apache2/sites-available/piwik.conf
	echo "ErrorLog /var/www/piwik/logs/error.log" >> /etc/apache2/sites-available/piwik.conf
	echo "CustomLog /var/www/piwik/logs/access.log combined" >> /etc/apache2/sites-available/piwik.conf
	echo "</VirtualHost>" >> /etc/apache2/sites-available/piwik.conf

	a2ensite piwik.conf
	systemctl restart apache2

	# DNS for Piwik
	dialog --backtitle "Installation of monitoring server by Cairn Devices" --title "DNS" \
	--ok-label "Next" --msgbox "
	In order to access to Piwik page you have to update your DNS configuration :
	piwik.$domainName.	0	A	ipv4 of your server" 8 70

	# Piwik configuration
	# SSL
	if [ "$itscert" = "yes" ]
	then
		Sssl="s"
	else
		Sssl=""
	fi

	email=""
	# Ask for email adresse for Piwik
	while [ "$email" == "" ]
	do
		dialog --backtitle "Cairngit installation" --title "Email for Piwik"\
		--inputbox "    /!\\ This email will be sent to Piwik servers /!\\" 7 60 2> $FICHTMP
		email=$(cat $FICHTMP)
	done

	curl -L -d "" "http$Sssl://piwik.$domainName/index.php?action=systemCheck"  >> /dev/null
	curl -L -d "type=InnoDB&host=127.0.0.1&username=piwik&password=$internalPass&dbname=piwik&tables_prefix=piwik_&adapter=PDO\MYSQL" "http$Sssl://piwik.$domainName/index.php?action=databaseSetup"  >> /dev/null
	curl -L -d "login=admin&password=$adminPass&password_bis=$adminPass&email=$email&subscribe_newsletter_piwikorg=1&subscribe_newsletter_professionalservices=1" "http$Sssl://piwik.$domainName/index.php?action=setupSuperUser&module=Installation"  >> /dev/null
	curl -L -d "siteName=$domainName&url=$domainName&timezone=UTC&ecommerce=0" "http$Sssl://piwik.$domainName/index.php?action=firstWebsiteSetup&module=Installation"  >> /dev/null
	curl -L -d "" "http$Sssl://piwik.$domainName/index.php?action=trackingCode&module=Installation&site_idSite=1&site_name=$domainName"  >> /dev/null
	curl -L -d "" "http$Sssl://piwik.$domainName/index.php?action=finished&module=Installation&site_idSite=1&site_name=$domainName"  >> /dev/null

	sed -i "s/installation_in_progress = 1//g" /var/www/piwik/config/config.ini.php

	chown www-data:www-data /var/www/piwik/ -Rf
	chmod 750 piwik/ -Rf
}

Install_Graylog()
{
	apt-get install -y apt-transport-https

	wget https://packages.graylog2.org/repo/packages/graylog-2.3-repository_latest.deb
	dpkg -i graylog-2.3-repository_latest.deb
	rm graylog-2.3-repository_latest.deb

	apt-get update
	apt-get -y install graylog-server
	systemctl enable graylog-server.service

	# Configuration
	apt-get install -y pwgen
	sed -i -e "s/password_secret =.*/password_secret = $(pwgen -s 128 1)/" /etc/graylog/server/server.conf
	sed -i -e "s/root_password_sha2 =.*/root_password_sha2 = $(echo -n $adminPass | shasum -a 256 | cut -d' ' -f1)/" /etc/graylog/server/server.conf
	sed -i -e "s/rest_listen_uri = http:\/\/127.0.0.1:9000\/api\//rest_listen_uri = http:\/\/monitoring.$domainName:9000\/api\//g" /etc/graylog/server/server.conf
	sed -i -e "s/#web_listen_uri = http:\/\/127.0.0.1:9000\//web_listen_uri = http:\/\/monitoring.$domainName:9000\//g" /etc/graylog/server/server.conf

	systemctl restart graylog-server

	# Port for web interface
	ufw allow 9000/tcp

	# Port to send log from other servers
	ufw allow 8514/udp

	ufw enable

	systemctl restart rsyslog
}

Security_app()
{
	Mail_adress()
	{
		# Install dependency
		echo "postfix postfix/mailname string $domainName" | debconf-set-selections
		echo "postfix postfix/main_mailer_type string 'Internet Site'" | debconf-set-selections
		apt-get -y install mailutils

		email=""
		# Ask for email adress for security report
		while [ "$email" == "" ]
		do
			dialog --backtitle "Cairngit installation" --title "Email for security reports"\
			--inputbox "       /!\\ Should be external of this server /!\\" 7 60 2> $FICHTMP
			email=$(cat $FICHTMP)
		done
		hostname=$(hostname)
	}

	Lets_cert()
	{
		# Configuration letsencrypt cerbot
		apt-get -y install python-letsencrypt-apache
		letsencrypt --apache  --email $email -d esmweb.$domainName -d piwik.$domainName
		echo -e "Installation de let's encrypt.......\033[32mDone\033[00m"
		sleep 4

		# Redirect http to https
		#sed -i "s/<\/VirtualHost>/Redirect permanent \/ https:\/\/$domainName\/\n<\/VirtualHost>/g" /etc/apache2/sites-available/base.conf
		sed -i "s/<\/VirtualHost>/Redirect permanent \/ https:\/\/piwik.$domainName\/\n<\/VirtualHost>/g" /etc/apache2/sites-available/piwik.conf

		# Add crontab to in order to renew the certificate
		crontab -l > /tmp/crontab.tmp
		echo "0 0 1 */2 * letsencrypt renew" >> /tmp/crontab.tmp
		crontab /tmp/crontab.tmp
		rm /tmp/crontab.tmp

		# Add SSL to modular apps
		if [ "$esmweb" = "installed" ]
		then
			letsencrypt --apache  --email $email -d esmweb.$domainName
			sed -i "s/<\/VirtualHost>/Redirect permanent \/ https:\/\/esmweb.$domainName\/\n<\/VirtualHost>/g" /etc/apache2/sites-available/esmweb.conf
		fi

		itscert="yes"

		systemctl restart apache2
	}

	Check_rootkits()
	{
		apt-get -y install rkhunter chkrootkit lynis

		# Configuration of rkhunter
		rkhunter --propupd

		# Configuration of lynis
		apt-key adv --keyserver keyserver.ubuntu.com --recv-keys C80E383C3DE9F082E01391A0366C67DE91CA5D5F
		echo "deb https://packages.cisofy.com/community/lynis/deb/ xenial main" > /etc/apt/sources.list.d/cisofy-lynis.list

		# Create script
		mkdir /home/$mainUser/.securityScript
		echo "#!/bin/bash" > /home/$mainUser/.securityScript/rkhunter.sh
		echo "apt update" >> /home/$mainUser/.securityScript/rkhunter.sh
		echo "apt install rkhunter" >> /home/$mainUser/.securityScript/rkhunter.sh
		echo "rkhunter --update" >> /home/$mainUser/.securityScript/rkhunter.sh
		echo "/usr/bin/rkhunter --checkall --nocolors --skip-keypress | /usr/bin/mail -s \"Rkhunter on $hostname\" $email" >> /home/$mainUser/.securityScript/rkhunter.sh

		echo "#!/bin/bash" > /home/$mainUser/.securityScript/chkrootkit.sh
		echo "apt update" >> /home/$mainUser/.securityScript/chkrootkit.sh
		echo "apt install chkrootkit" >> /home/$mainUser/.securityScript/chkrootkit.sh
		echo "/usr/sbin/chkrootkit | /usr/bin/mail -s \"ChkRootkit on $hostname\" $email" >> /home/$mainUser/.securityScript/chkrootkit.sh

		echo "#!/bin/bash" > /home/$mainUser/.securityScript/lynis.sh
		echo "apt update" >> /home/$mainUser/.securityScript/lynis.sh
		echo "apt install lynis" >> /home/$mainUser/.securityScript/lynis.sh
		echo "/usr/sbin/lynis --check-all --cronjob -Q | /usr/bin/mail -s \"Lynis on $hostname\" $email" >> /home/$mainUser/.securityScript/lynis.sh

		# Crontab rules for anti rootkit
		crontab -l > /tmp/crontab.tmp
		echo "0 1 * * 0 /bin/bash /home/$mainUser/.securityScript/rkhunter.sh" >> /tmp/crontab.tmp

		echo "0 2 * * 0 /bin/bash /home/$mainUser/.securityScript/chkrootkit.sh" >> /tmp/crontab.tmp

		echo "0 3 * * 0 /bin/bash /home/$mainUser/.securityScript/lynis.sh" >> /tmp/crontab.tmp

		crontab /tmp/crontab.tmp
		rm /tmp/crontab.tmp
	}

	mail_SSH()
	{
		# Send mail when someone is succefully connected in SSH
		echo "ip=\`echo \$SSH_CONNECTION | cut -d \" \" -f 1\`
		hostname=\`hostname\`
		Date=\$(date)
		echo \"User \$USER just logged in from \$ip at \$Date\" | mail -s \"SSH Login\" $email &" >>  /etc/ssh/sshrc
	}

	Install_modSecurity()
	{
		#Install and configure mod-security
		apt-get -y install libapache2-mod-security2

		systemctl restart apache2

		rm -rf /usr/share/modsecurity-crs

		git clone https://github.com/SpiderLabs/owasp-modsecurity-crs.git /usr/share/modsecurity-crs

		mv /usr/share/modsecurity-crs/crs-setup.conf.example /usr/share/modsecurity-crs/crs-setup.conf
		mv /etc/modsecurity/modsecurity.conf-recommended /etc/modsecurity/modsecurity.conf

		sed -i "s/SecRuleEngine DetectionOnly/SecRuleEngine On/g" /etc/modsecurity/modsecurity.conf

		sed -i 's/<\/IfModule>/        IncludeOptional "\/usr\/share\/modsecurity-crs\/*.conf"\n        IncludeOptional "\/usr\/share\/modsecurity-crs\/rules\/*.conf"\n<\/IfModule>/g'  /etc/apache2/mods-available/security2.conf

		systemctl restart apache2
	}

	Hide_ApacheVersion()
	{
		echo "ServerSignature Off
		ServerTokens Prod
		TraceEnable Off
		Header unset ETag
		Header always unset X-Powered-By
		FileETag None" >> /etc/apache2/apache2.conf

		systemctl restart apache2
	}

	Install_Fail2ban()
	{
		apt-get -y install fail2ban

		echo "[ssh-ddos]
		enabled  = true
		port     = ssh,sftp,$sshport
		filter   = sshd-ddos
		logpath  = /var/log/auth.log
		maxretry = 6

		[apache]
		enabled  = true
		port     = http,https
		filter   = apache-auth
		logpath  = /var/log/apache*/*error.log
		maxretry = 6

		[apache-noscript]
		enabled  = true
		port     = http,https
		filter   = apache-noscript
		logpath  = /var/log/apache*/*error.log
		maxretry = 6

		[apache-overflows]
		enabled  = true
		port     = http,https
		filter   = apache-overflows
		logpath  = /var/log/apache*/*error.log
		maxretry = 2

		[apache-badbots]
		enabled  = true
		port     = http,https
		filter   = apache-badbots
		logpath  = /var/log/apache*/*error.log
		maxretry = 2

		[php-url-fopen]
		enabled = true
		port    = http,https
		filter  = php-url-fopen
		logpath = /var/log/apache*/*access.log

		[ssh]
		enabled = true
		port = ssh,sftp,$sshport
		filter = sshd
		logpath = /var/log/auth.log
		maxretry = 6
		bantime = 1000

		[http-get-post-dos]
		enabled = true
		port = http,https
		filter = http-get-post-dos
		logpath = /var/log/apache2/access.log
		maxretry = 360
		findtime = 120
		mail-whois-lines[name=%(__name__)s, dest=%(destemail)s, logpath=%(logpath)s]
		bantime = 200

		[http-w00t]
		enabled = true
		filter = http-w00t
		logpath = /var/log/apache2/*.log
		maxretry = 1" > /etc/fail2ban/jail.local

		# Add filter http-get-post-dos
		echo "[Definition]" > /etc/fail2ban/filter.d/http-get-post-dos.conf
		echo 'failregex = ^<HOST> -.*"(GET|POST).*' >> /etc/fail2ban/filter.d/http-get-post-dos.conf
		echo "ignoreregex =" >> /etc/fail2ban/filter.d/http-get-post-dos.conf

		# Add filter w00t
		echo "[Definition]" > /etc/fail2ban/filter.d/http-w00t.conf
		echo 'failregex = ^<HOST> -.*"(GET|POST).*\/.*w00t.*' >> /etc/fail2ban/filter.d/http-w00t.conf
		echo "ignoreregex =" >> /etc/fail2ban/filter.d/http-w00t.conf

		# Mail Fail2ban
		echo '# Fail2Ban configuration file
		#
		# Author: Yannic Arnoux
		#         Based on sendmail-buffered written by Cyril Jaquier
		#
		#

		[INCLUDES]

		before = sendmail-common.conf

		[Definition]

		# Option:  actionstart
		# Notes.:  command executed once at the start of Fail2Ban.
		# Values:  CMD
		#
		actionstart = printf %%b "Subject: [Fail2Ban] <name>: started on `uname -n`
		From: <sendername> <<sender>>
		To: <dest>\n
		Hi,\n
		The jail <name> has been started successfully.\n
		Regards,\n
		Fail2Ban" | /usr/sbin/sendmail -f <sender> <dest>

		# Option:  actionstop
		# Notes.:  command executed once at the end of Fail2Ban
		# Values:  CMD
		#
		actionstop = if [ -f <tmpfile> ]; then
			printf %%b "Subject: [Fail2Ban] Report from `uname -n`
			From: <sendername> <<sender>>
			To: <dest>\n
			Hi,\n
			These hosts have been banned by Fail2Ban.\n
			`cat <tmpfile>`
			\n,Regards,\n
			Fail2Ban" | /usr/sbin/sendmail -f <sender> <dest>
			rm <tmpfile>
		fi
		printf %%b "Subject: [Fail2Ban] <name>: stopped  on `uname -n`
		From: Fail2Ban <<sender>>
		To: <dest>\n
		Hi,\n
		The jail <name> has been stopped.\n
		Regards,\n
		Fail2Ban" | /usr/sbin/sendmail -f <sender> <dest>

		# Option:  actioncheck
		# Notes.:  command executed once before each actionban command
		# Values:  CMD
		#
		actioncheck =

		# Option:  actionban
		# Notes.:  command executed when banning an IP. Take care that the
		#          command is executed with Fail2Ban user rights.
		# Tags:    See jail.conf(5) man page
		# Values:  CMD
		#
		actionban = printf %%b "`date`: <name> ban <ip> after <failures> failure(s)\n" >> <tmpfile>
		if [ -f <mailflag> ]; then
			printf %%b "Subject: [Fail2Ban] Report from `uname -n`
			From: <sendername> <<sender>>
			To: <dest>\n
			Hi,\n
			These hosts have been banned by Fail2Ban.\n
			`cat <tmpfile>`
			\n,Regards,\n
			Fail2Ban" | /usr/sbin/sendmail -f <sender> <dest>
			rm <tmpfile>
			rm <mailflag>
		fi

		# Option:  actionunban
		# Notes.:  command executed when unbanning an IP. Take care that the
		#          command is executed with Fail2Ban user rights.
		# Tags:    See jail.conf(5) man page
		# Values:  CMD
		#
		actionunban =

		[Init]

		# Default name of the chain
		#
		name = default

		# Default temporary file
		#
		tmpfile = /var/run/fail2ban/tmp-mail.txt

		# Default flag file
		#
		mailflag = /var/run/fail2ban/mail.flag' > /etc/fail2ban/action.d/sendmail-cron.conf

		# Ajout d'une règle cron pour mail automatique
		crontab -l > /tmp/crontab.tmp
		echo "@daily touch /var/run/fail2ban/mail.flag" >> /tmp/crontab.tmp
		crontab /tmp/crontab.tmp
		rm /tmp/crontab.tmp

		echo "
		[DEFAULT]
		destemail = $email" >> /etc/fail2ban/jail.local
		echo 'action_mwlc = %(banaction)s[name=%(__name__)s, port="%(port)s", protocol="%(protocol)s", chain="%(chain)s"]
		%(mta)s-cron[name=%(__name__)s, dest="%(destemail)s", logpath=%(logpath)s, chain="%(chain)s", sendername="%(sendername)s"] action = %(action_mwlc)s' >> /etc/fail2ban/jail.local

		systemctl restart fail2ban
	}

	Change_SSHport()
	{
		sshport=""
		while [ "$sshport" == "" ]
		do
			dialog --backtitle "Installation of monitoring server by Cairn Devices" --title "Choix d'un port pour la connexion SSH" \
			--inputbox "" 7 60 2> $FICHTMP
			sshport=$(cat $FICHTMP)
			test_port=$(netstat -paunt | grep :$sshport\ )
			if [ "$test_port" != "" ]
			then
				echo -e "\033[31m / ! \ Attention ce port semble être utilisé / ! \ \033[0m"
				echo $test_port
				sleep 4
				sshport=""
			fi
		done
		sed -i "5 s/Port 22/Port $sshport/g" /etc/ssh/sshd_config
		dialog --backtitle "Installation of monitoring server by Cairn Devices" --title "Changement port SSH" \
		--ok-label "Next" --msgbox "Le port SSH à été changé pour éviter les attaques automatiques. Veuillez en prendre note. \nNouveau port : $sshport   \nPour accéder au serveur en ssh : ssh -p$sshport $mainUser@$domainName" 9 66
	}

	Install_unattendedupgrades()
	{
		apt-get -y install unattended-upgrades
		sed -i "s/\/\/Unattended-Upgrade::Mail \"root\";/Unattended-Upgrade::Mail \"$email\";/g" /etc/apt/apt.conf.d/50unattended-upgrades
	}

	DOSDDOSOtherattacks_protection()
	{
		# UFW
		ufw allow Apache Secure
		ufw allow Dovecot Secure IMAP
		ufw allow Dovecot Secure POP3
		ufw allow OpenSSH

		# Install and configure mod-evasive for apache2
		apt-get -y install libapache2-mod-evasive
		mkdir -p /var/lock/mod_evasive
		chown -R apache:apache /var/lock/mod_evasive

		systemctl restart apache2
		# Remove blacklist ip
		crontab -l > /tmp/crontab.tmp
		echo "0 5 * * * find /var/lock/mod_evasive -mtime +1 -type f -exec rm -f '{}' \;" >> /tmp/crontab.tmp
		crontab /tmp/crontab.tmp
		rm /tmp/crontab.tmp

		echo "net.ipv4.conf.all.send_redirects = 0" >> /etc/sysctl.conf
		echo "net.ipv4.conf.all.accept_source_route = 0" >> /etc/sysctl.conf
		echo "net.ipv6.conf.all.accept_source_route = 0" >> /etc/sysctl.conf
		echo "net.ipv4.conf.all.accept_redirects = 0" >> /etc/sysctl.conf
		echo "net.ipv6.conf.all.accept_redirects = 0" >> /etc/sysctl.conf
		echo "net.ipv4.conf.default.rp_filter = 1" >> /etc/sysctl.conf
		echo "net.ipv4.conf.all.rp_filter = 1" >> /etc/sysctl.conf
		echo "net.ipv4.tcp_syncookies = 1" >> /etc/sysctl.conf
		echo "net.ipv4.tcp_max_syn_backlog = 2048" >> /etc/sysctl.conf
		echo "net.ipv4.icmp_echo_ignore_broadcasts = 1" >> /etc/sysctl.conf
		echo "net.ipv4.icmp_ignore_bogus_error_responses = 1" >> /etc/sysctl.conf
		echo "net.ipv4.conf.all.log_martians = 1" >> /etc/sysctl.conf

		sysctl -n -e -q

		#  Secure shared memory
		tmpfs     /run/shm     tmpfs     defaults,noexec,nosuid     0     0

		# Stop IP spoofing
		nospoof on >> /etc/host.conf

	}

	ESMWEB_monitoring()
	{
		# Install dependency
		apt-get -y install php-xml

		wget http://www.ezservermonitor.com/esm-web/downloads/version/2.5
		mkdir -p /var/www/esmweb/logs/
		unzip 2.5 -d /var/www/esmweb
		rsync -a /var/www/esmweb/eZServerMonitor-2.5/ /var/www/esmweb/
		rm 2.5
		rm -R /var/www/esmweb/eZServerMonitor-2.5/
		chown -R www-data:www-data /var/www/esmweb

		# Apache2 configuration for esmweb
		echo "<VirtualHost *:80>" > /etc/apache2/sites-available/esmweb.conf
		echo "ServerAdmin postmaster@$domainName" >> /etc/apache2/sites-available/esmweb.conf
		echo "ServerName esmweb.$domainName" >> /etc/apache2/sites-available/esmweb.conf
		echo "ServerAlias esmweb.$domainName" >> /etc/apache2/sites-available/esmweb.conf
		echo "DocumentRoot /var/www/esmweb/" >> /etc/apache2/sites-available/esmweb.conf
		echo "# Pass the default character set" >> /etc/apache2/sites-available/esmweb.conf
		echo "AddDefaultCharset utf-8" >> /etc/apache2/sites-available/esmweb.conf
		echo "# Containment of esmweb" >> /etc/apache2/sites-available/esmweb.conf
		echo "php_admin_value open_basedir /var/www/esmweb/" >> /etc/apache2/sites-available/esmweb.conf
		echo "# Prohibit access to files starting with a dot" >> /etc/apache2/sites-available/esmweb.conf
		echo "<FilesMatch ^\\.>" >> /etc/apache2/sites-available/esmweb.conf
		echo "    Order allow,deny" >> /etc/apache2/sites-available/esmweb.conf
		echo "    Deny from all" >> /etc/apache2/sites-available/esmweb.conf
		echo "</FilesMatch>" >> /etc/apache2/sites-available/esmweb.conf
		echo "<Directory /var/www/esmweb/ >" >> /etc/apache2/sites-available/esmweb.conf
		echo "AllowOverride All" >> /etc/apache2/sites-available/esmweb.conf
		echo "</Directory>" >> /etc/apache2/sites-available/esmweb.conf
		echo "ErrorLog /var/www/esmweb/logs/error.log" >> /etc/apache2/sites-available/esmweb.conf
		echo "CustomLog /var/www/esmweb/logs/access.log combined" >> /etc/apache2/sites-available/esmweb.conf
		echo "</VirtualHost>" >> /etc/apache2/sites-available/esmweb.conf

		a2ensite esmweb.conf
		systemctl restart apache2

		# DNS for esmweb
		dialog --backtitle "Installation of monitoring server by Cairn Devices" --title "DNS" \
		--ok-label "Next" --msgbox "
		In order to access to esmweb monitoring page you have to update your DNS configuration :
		esmweb.$domainName.	0	A	ipv4 of your server" 8 70

		# Configure Esmweb
		sed -i "s/\"auto_refresh\": 0/\"auto_refresh\": 60/g" /var/www/esmweb/conf/esm.config.json
		sed -i "s/\"theme\": \"blue\"/\"theme\": \"purple\"/g" /var/www/esmweb/conf/esm.config.json
		sed -i "s/\"facebook.com\"/\"ovh.com\"/g" /var/www/esmweb/conf/esm.config.json
		sed -i "s/\"port\": 22/\"port\": $sshport/g" /var/www/esmweb/conf/esm.config.json
		sed -i "s/\"list\": \[/\"list\": \[\n            {\n                \"name\": \"Web Server HTTPS\",\n                \"host\": \"localhost\",\n                \"port\": 443,\n                \"protocol\": \"tcp\"\n            },\n/g" /var/www/esmweb/conf/esm.config.json

		# Auth php
		sed -i "s/^<?php/<?php \nsession_start();\n#Access\n\$redirect = \"connexion.php\";\n\nif(isset(\$_SESSION['esmAdmin']))\n{\n        if(\$_SESSION['esmAdmin'] != 'true')\n        {\n                header(\"Location: \$redirect\");\n        }\n}\nelse\n{\n        header(\"Location: \$redirect\");\n}\n\n/g" /var/www/esmweb/index.php

		echo "<?php
		session_start();

		# Test access autorization
		if (isset(\$_POST['conn_pseudo']) && isset(\$_POST['conn_password']))
		{
			\$esmIdconn = htmlspecialchars(\$_POST['conn_pseudo']);
			\$esmPassconn = hash('sha256', \$_POST['conn_password']);

			# Get id and password
			\$secureAccess = fopen('/var/www/esmweb/.htpassword', 'r+');

			\$esmId = fgets(\$secureAccess);
			\$esmPass = fgets(\$secureAccess);

			fclose(\$secureAccess);

			# Clean values
			\$esmId = preg_replace(\"/(\r\n|\n|\r)/\", \"\", \$esmId);
			\$esmPass = preg_replace(\"/(\r\n|\n|\r)/\", \"\", \$esmPass);


			if (\$esmIdconn == \$esmId && \$esmPassconn == \$esmPass)
			{
				session_start();
				\$_SESSION['esmAdmin']   = \"true\";
				\$redirect           = \"index.php\";
				header(\"Location: \$redirect\");
			}
		else
			{
				session_start();
				\$_SESSION['esmAdmin']   = \"false\";
			}
		}

		?>

		<!DOCTYPE html>
		<html>
		<head>
		<meta charset=\"utf-8\" />
		<title>esm\`Web Connexion</title>
		<link href=\"/web/css/connexion.css\" rel=\"stylesheet\" />
		</head>

		<div class=\"connexion\">
		<p class=\"title_connexion\">Connexion</p>

		<form method=\"post\" >
		<p><input class=\"connexion_field\" type=\"text\" name=\"conn_pseudo\" id=\"Pseudo\" value=\"Admin\" /></p>
		<p><input class=\"connexion_field\" type=\"password\" name=\"conn_password\" id=\"Mot de passe\" /></p>
		<input class=\"submit_connexion\" type=\"submit\" value=\"Submit\" />
		</form>
		</div>
		<html>
		" >> /var/www/esmweb/connexion.php

		echo "html
		{
			height: 100%;
			margin:0;
			padding:0;
			background-color: #aa8ecc;
			background-size: cover;
			font-family: 'Ubuntu';
			display: flex;
			justify-content: center;
			align-items: center;
		}

		body
		{
			margin: 0;
		}

		.title_connexion
		{
			color: white;
			font-size: LARGE;
			margin: 0;
			margin-bottom: 30px;
		}

		.connexion
		{
			padding: 10px;
			background-color: #9775c1;
			border-radius: 5px;
			border: 1px solid #8769af;
		}

		.connexion_field
		{
			width: 300px;
			height: 30px;
			border: none;
			border-radius: 3px;
			padding: 3px;
			font-size: 15px;
			text-align: center;
		}

		.submit_connexion
		{
			background-color: #6c548b;
			font-size: LARGE;
			width: 306px;
			height: 36px;
			border: 1px solid #5e4979;
			border-radius: 3px;
			color: white;
			margin-top: 5px;
		}
		" >> /var/www/esmweb/web/css/connexion.css

		esmpasshash=$(echo -n $adminPass | sha256sum | sed 's/  -//g')

		echo "Admin" >>  /var/www/esmweb/.htpassword
		echo $esmpasshash >>  /var/www/esmweb/.htpassword

		chmod 644 /var/www/esmweb/.htpassword
		chown www-data:www-data /var/www/esmweb/.htpassword

		esmweb="installed"

	}

	htpasswd_protection()
	{
		# Apache2 mod
		a2enmod authz_groupfile

		systemctl restart apache

		# Esmweb htpasswd protection
		echo "AuthUserFile /var/www/esmweb/.htpasswd
		AuthGroupFile /dev/null
		AuthName \"Restricted access\"
		AuthType Basic
		require valid-user" >> /var/www/esmweb/.htaccess

		chmod 644 /var/www/esmweb/.htaccess
		chown www-data:www-data /var/www/esmweb/.htaccess

		htpasswd -bcB -C 8 /var/www/esmweb/.htpasswd $email $passnohash

		chmod 644 /var/www/esmweb/.htpasswd
		chown www-data:www-data /var/www/esmweb/.htpasswd

		# Explain how to access to esmweb
		dialog --backtitle "Installation of monitoring server by Cairn Devices" --title "Htpasswd protection" \
		--ok-label "Next" --msgbox "
		In order to access to esmweb monitoring page you have to go to this url :
		https://esmweb.$domainName/

		ID : $email
		Password : Your installation password" 11 70

		systemctl restart apache2

		# Piwik htpasswd protection
		echo "AuthUserFile /var/www/piwik/.htpasswd
		AuthGroupFile /dev/null
		AuthName \"Restricted access\"
		AuthType Basic
		require valid-user" >> /var/www/piwik/.htaccess

		chmod 644 /var/www/piwik/.htaccess
		chown www-data:www-data /var/www/piwik/.htaccess

		htpasswd -bcB -C 8 /var/www/piwik/.htpasswd $email $passnohash

		chmod 644 /var/www/piwik/.htpasswd
		chown www-data:www-data /var/www/piwik/.htpasswd

		# Explain how to access to piwik
		dialog --backtitle "Installation of monitoring server by Cairn Devices" --title "Htpasswd protection" \
		--ok-label "Next" --msgbox "
		In order to access to piwik monitoring page you have to go to this url :
		https://piwik.$domainName/

		ID : $email
		Password : Your installation password" 11 70

		systemctl restart apache2

	}

	Mail_adress

	dialog --backtitle "Installation of security apps" --title "Choose security apps" \
	--ok-label "Ok" --cancel-label "Quit" \
	--checklist "" 17 77 11 \
	"Rootkits" "Check rootkits with rkhunter, chrootkit, lynis" off \
	"SSH" "Change SSH port, send email when SSH connexion" off \
	"ModSecurity" "Install apache WAF" off \
	"Apache" "Hide apache signature" off \
	"Fail2ban" "Install fail2ban rules W00t, Dos/DDos, SSH/SASL" off \
	"Unattended Upgrades" "Install Unattended Upgrade" off \
	"Sys protection" "DOS/DDOS protection, martian log, ICMP" off\
	"Esmweb" "Web page to monitoring your server" off\
	"Htpasswd" "Activate htpasswd protection on admin pages" off\
	"SSL" "SSL certification, with let's encrypt" off 2> $FICHTMP

	if [ $? = 0 ]
	then
		for i in $(cat $FICHTMP)
		do
			case $i in
				"Rootkits") Check_rootkits ;;
				"SSH")  mail_SSH; Change_SSHport ;;
				"ModSecurity") Install_modSecurity ;;
				"Apache") Hide_ApacheVersion ;;
				"Fail2ban") Install_Fail2ban ;;
				"Unattended Upgrades") Install_unattendedupgrades ;;
				"Sys protection") DOSDDOSOtherattacks_protection ;;
				"Esmweb") ESMWEB_monitoring ;;
				"Htpasswd") htpasswd_protection ;;
				"SSL") Lets_cert ;;
			esac
		done
	else exit 0
	fi
}

Cleaning()
{
	a2dissite 000-default
	a2dissite default-ssl
	rm /etc/apache2/sites-available/000-default.conf
	rm /etc/apache2/sites-available/default-ssl.conf
	systemctl restart apache2

	apt-get -y update
	apt-get -y upgrade
	apt-get -y autoremove
	passnohash="0"
	internalPass="0"
	adminPass="0"
	echo -e "Cleaning .............\033[32mDone\033[00m"

	usermod --expiredate 1 root
	passwd -l root

	echo "We will now reboot your server"
	sleep 5
	reboot
}



###################################
###   Beginning of the script   ###
###################################

Update_sys
Install_dependency

DIALOG=${DIALOG=dialog}
touch /tmp/dialogtmp && FICHTMP=/tmp/dialogtmp
trap "rm -f $FICHTMP" 0 1 2 3 5 15
$DIALOG --clear --backtitle "Installation of monitoring server by Cairn Devices" --title "Installation of monitoring server by Cairn Devices" \
--menu "Bonjour, choisissez votre type d'installation :" 15 80 5 \
"dedicated" "Dedicated installation" 2> $FICHTMP # TODO Mettre tout le dialog en anglais
valret=$?
choix=$(cat $FICHTMP)
case $valret in
	0)	echo "'$choix' is your choice";;
	1) 	exit 0;;
	255) 	exit 1;;
esac

mainUser=""
# Ask for actual Linux user in used
while [ "$mainUser" == "" ]
do
	dialog --backtitle "Cairngit installation" --title "Actual Linux user"\
	--inputbox "Please enter the name of the actual Linux user" 7 60 2> $FICHTMP
	mainUser=$(cat $FICHTMP)
done

# Password for installation (Mysql, etc)
passnohash="0"
repassnohash="1"
while [ "$passnohash" != "$repassnohash" ] || [ "$passnohash" == "" ]
do
	dialog --backtitle "Installation of monitoring server by Cairn Devices" --title "Choose the installation password" \
	--insecure --passwordbox "" 7 60 2> $FICHTMP
	passnohash=$(cat $FICHTMP)

	dialog --backtitle "Installation of monitoring server by Cairn Devices" --title "Retype the installation password" \
	--insecure --passwordbox "" 7 60 2> $FICHTMP
	repassnohash=$(cat $FICHTMP)
done

salt=$(date +%T)$(( RANDOM % 100 ))
passnohashsalt=$passnohash$salt
internalPass=$(echo -n $passnohashsalt | sha256sum | sed 's/  -//g')
passnohashsalt="0"
repassnohash="0"

repass="0"
adminPass="1"
while [ "$adminPass" != "$repass" ] || [ "$adminPass" == "" ]
do
	dialog --backtitle "Installation of monitoring server by Cairn Devices" --title "Choose the admin password" \
	--insecure --passwordbox "" 7 60 2> $FICHTMP
	adminPass=$(cat $FICHTMP)

	dialog --backtitle "Installation of monitoring server by Cairn Devices" --title "Retype the admin password" \
	--insecure --passwordbox "" 7 60 2> $FICHTMP
	repass=$(cat $FICHTMP)
done
repass="0"

domainName=""
# Ask for domain name
while [ "$domainName" == "" ]
do
	dialog --backtitle "Installation of monitoring server by Cairn Devices" --title "Domain name" \
	--inputbox "" 7 60 2> $FICHTMP
	domainName=$(cat $FICHTMP)
done

if [ "$choix" = "dedicated" ]
then
	Install_Apache2
	Install_PHP
	Install_Mysql
	Install_Java
	Install_Elasticsearch
	Install_MongoDB
	Install_Graylog
	Install_Piwik
	Security_app
	Cleaning
fi
