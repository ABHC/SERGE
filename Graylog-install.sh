#!/bin/bash

# Error log
exec 2> >(tee -a error.log)

# Variable declaration
itscert="no"
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
	# Openjdk
	apt-get -y install open-jdk-7-jre
}
