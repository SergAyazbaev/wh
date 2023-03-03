#!/bin/sh


===============================================================
# Set the following variables as per your requirement
#=====================================================================
# Database Name to backup
MONGO_DATABASE="wh_develop"
# Database host name
MONGO_HOST="127.0.0.1"
# Database port
MONGO_PORT="27017"
# Backup directory
BACKUPS_DIR="../backup/$MONGO_DATABASE"
# Database user name
####DBUSERNAME="username"
# Database password
####DBPASSWORD="passw0rd"
# Authentication database name
####DBAUTHDB="admin"
# Days to keep the backup
DAYSTORETAINBACKUP="2"
#=====================================================================

TIMESTAMP=`date +%F-%H%M`
BACKUP_NAME="$MONGO_DATABASE-$TIMESTAMP"

echo "Performing backup of $MONGO_DATABASE"
echo "--------------------------------------------"
# Create backup directory
if ! mkdir -p $BACKUPS_DIR; then
  echo "Can't create backup directory in $BACKUPS_DIR. Go and fix it!" 1>&2
  exit 1;
fi;
# Create dump
##### mongodump -d $MONGO_DATABASE --username $DBUSERNAME --password $DBPASSWORD --authenticationDatabase $DBAUTHDB

mongodump -d $MONGO_DATABASE

#mongoexport --db $MONGO_DATABASE --collection user --out  $BACKUPS_DIR/$BACKUP_NAME/json/user.json
#mongoexport --db $MONGO_DATABASE --collection sklad --out $BACKUPS_DIR/$BACKUP_NAME/json/sklad.json

#mongoexport --db $MONGO_DATABASE --collection cross --out $BACKUPS_DIR/$BACKUP_NAME/json/cross.json
#mongoexport --db $MONGO_DATABASE --collection sklad_shablon --out $BACKUPS_DIR/$BACKUP_NAME/json/sklad_shablon.json
#mongoexport --db $MONGO_DATABASE --collection sklad_transfer --out $BACKUPS_DIR/$BACKUP_NAME/json/sklad_transfer.json

#mongoexport --db $MONGO_DATABASE --collection spr_glob --out $BACKUPS_DIR/$BACKUP_NAME/json/spr_glob.json
#mongoexport --db $MONGO_DATABASE --collection spr_glob_element --out $BACKUPS_DIR/$BACKUP_NAME/json/spr_glob_element.json
#mongoexport --db $MONGO_DATABASE --collection spr_globam --out $BACKUPS_DIR/$BACKUP_NAME/json/spr_globam.json
#mongoexport --db $MONGO_DATABASE --collection spr_globam_element --out $BACKUPS_DIR/$BACKUP_NAME/json/sprglobam_element.json
#mongoexport --db $MONGO_DATABASE --collection spr_things --out $BACKUPS_DIR/$BACKUP_NAME/json/spr_things.json

mongoexport --db $MONGO_DATABASE --collection sprwh_top  --out $BACKUPS_DIR/$BACKUP_NAME/json/sprwh_top.json
mongoexport --db $MONGO_DATABASE --collection sprwh_element --out $BACKUPS_DIR/$BACKUP_NAME/json/sprwh_element.json
#mongoexport --db $MONGO_DATABASE --collection sprwh_element_old --out $BACKUPS_DIR/$BACKUP_NAME/json/sprwh_element_old.json
#mongoexport --db $MONGO_DATABASE --collection tk --out $BACKUPS_DIR/$BACKUP_NAME/json/tk.json
#mongoexport --db $MONGO_DATABASE --collection tz --out $BACKUPS_DIR/$BACKUP_NAME/json/tz.json

mongoexport --db $MONGO_DATABASE --collection sklad_inventory_cs --out $BACKUPS_DIR/$BACKUP_NAME/json/sklad_inventory_cs.json

#mongoexport --db $MONGO_DATABASE --collection barcode_consignment --out $BACKUPS_DIR/$BACKUP_NAME/json/barcode_consignment.json
#mongoexport --db $MONGO_DATABASE --collection barcode_pool --out $BACKUPS_DIR/$BACKUP_NAME/json/barcode_pool.json
#mongoexport --db $MONGO_DATABASE --collection consignment --out $BACKUPS_DIR/$BACKUP_NAME/json/consignment.json

####
#chown user:user ../backup/wh_prod -R

#### Rename dump directory to backup name
#mv dump $BACKUP_NAME
#### Compress backup
#tar -zcvf $BACKUPS_DIR/$BACKUP_NAME.tgz $BACKUP_NAME
##### Delete uncompressed backup
#rm -rf $BACKUP_NAME

# Delete backups older than retention period
#find $BACKUPS_DIR -type f -mtime +$DAYSTORETAINBACKUP -exec rm {} +
echo "--------------------------------------------"
echo "Database backup complete!"
