#!/bin/sh

#=====================================================================
# Set the following variables as per your requirement
#=====================================================================
# Database Name to backup
MONGO_DATABASE="wh_prod"

# Database host name
MONGO_HOST="127.0.0.1"
# Database port
MONGO_PORT="27017"
# Backup directory

#BACKUPS_DIR="/var/www/backup/$MONGO_DATABASE"
BACKUPS_DIR="/var/www/111"

# Database user name
####DBUSERNAME="username"
# Database password
####DBPASSWORD="passw0rd"
# Authentication database name
####DBAUTHDB="admin"
# Days to keep the backup
DAYSTORETAINBACKUP="21"
#=====================================================================



echo "Performing backup of $MONGO_DATABASE"
echo "--------------------------------------------"

# Create dump
	##### mongodump -d $MONGO_DATABASE --username $DBUSERNAME --password $DBPASSWORD --authenticationDatabase $DBAUTHDB

#Restore arh
mongorestore /var/www/backup/$MONGO_DATABASE



echo "--------------------------------------------"
echo "Database RESTORE complete!"

