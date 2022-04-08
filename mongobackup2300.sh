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
BACKUPS_DIR="/var/www/backup/2300"$MONGO_DATABASE

# Database user name
####DBUSERNAME="username"
# Database password
####DBPASSWORD="passw0rd"
# Authentication database name
####DBAUTHDB="admin"
# Days to keep the backup
DAYSTORETAINBACKUP="25"
#=====================================================================

TIMESTAMP=`date +%F-%H%M`
BACKUP_NAME="$MONGO_DATABASE-$TIMESTAMP"

echo "Performing backup of $MONGO_DATABASE"
echo "--------------------------------------------"
# Create backup directory
if ! mkdir -p $BACKUPS_DIR; then
	chown user:user  $BACKUPS_DIR
 echo "Can't create backup directory in $BACKUPS_DIR. Go and fix it!" 1>&2
  exit 1;
fi;
# Create dump
##### mongodump -d $MONGO_DATABASE --username $DBUSERNAME --password $DBPASSWORD --authenticationDatabase $DBAUTHDB

mongodump -d $MONGO_DATABASE

mongoexport --db $MONGO_DATABASE --collection sklad_inventory --out  $BACKUPS_DIR/$BACKUP_NAME/json/sklad_inventory.json
mongoexport --db $MONGO_DATABASE --collection sklad_past_inventory --out  $BACKUPS_DIR/$BACKUP_NAME/json/sklad_past_inventory.json
mongoexport --db $MONGO_DATABASE --collection sklad_cs_inventory --out  $BACKUPS_DIR/$BACKUP_NAME/json/sklad_cs_inventory.json
mongoexport --db $MONGO_DATABASE --collection sklad_cs_past_inventory --out  $BACKUPS_DIR/$BACKUP_NAME/json/sklad_cs_past_inventory.json



mongoexport --db $MONGO_DATABASE --collection sklad_shablon --out  $BACKUPS_DIR/$BACKUP_NAME/json/sklad_shablon.js$


mongoexport --db $MONGO_DATABASE --collection user --out  $BACKUPS_DIR/$BACKUP_NAME/json/user.json
mongoexport --db $MONGO_DATABASE --collection sklad --out $BACKUPS_DIR/$BACKUP_NAME/json/sklad.json

mongoexport --db $MONGO_DATABASE --collection cross --out $BACKUPS_DIR/$BACKUP_NAME/json/cross.json
mongoexport --db $MONGO_DATABASE --collection sklad_shablon --out $BACKUPS_DIR/$BACKUP_NAME/json/sklad_shablon.json
mongoexport --db $MONGO_DATABASE --collection sklad_transfer --out $BACKUPS_DIR/$BACKUP_NAME/json/sklad_transfer.j$

mongoexport --db $MONGO_DATABASE --collection spr_glob --out $BACKUPS_DIR/$BACKUP_NAME/json/spr_glob.json
mongoexport --db $MONGO_DATABASE --collection spr_glob_element --out $BACKUPS_DIR/$BACKUP_NAME/json/spr_glob_eleme$
mongoexport --db $MONGO_DATABASE --collection spr_globam --out $BACKUPS_DIR/$BACKUP_NAME/json/spr_globam.json
mongoexport --db $MONGO_DATABASE --collection spr_globam_element --out $BACKUPS_DIR/$BACKUP_NAME/json/sprglobam_el$
mongoexport --db $MONGO_DATABASE --collection spr_things --out $BACKUPS_DIR/$BACKUP_NAME/json/spr_things.json

mongoexport --db $MONGO_DATABASE --collection sprwh_top  --out $BACKUPS_DIR/$BACKUP_NAME/json/sprwh_top.json
mongoexport --db $MONGO_DATABASE --collection sprwh_element --out $BACKUPS_DIR/$BACKUP_NAME/json/sprwh_element.json
mongoexport --db $MONGO_DATABASE --collection sprwh_element_old --out $BACKUPS_DIR/$BACKUP_NAME/json/sprwh_element$
mongoexport --db $MONGO_DATABASE --collection tk --out $BACKUPS_DIR/$BACKUP_NAME/json/tk.json
mongoexport --db $MONGO_DATABASE --collection tz --out $BACKUPS_DIR/$BACKUP_NAME/json/tz.json

mongoexport --db $MONGO_DATABASE --collection barcode_consignment --out $BACKUPS_DIR/$BACKUP_NAME/json/barcode_con$
mongoexport --db $MONGO_DATABASE --collection barcode_pool --out $BACKUPS_DIR/$BACKUP_NAME/json/barcode_pool.json
mongoexport --db $MONGO_DATABASE --collection consignment --out $BACKUPS_DIR/$BACKUP_NAME/json/consignment.json

mongoexport --db $MONGO_DATABASE --collection sklad_delete --out $BACKUPS_DIR/$BACKUP_NAME/json/sklad_delete.json

mongoexport --db $MONGO_DATABASE --collection mobile_inventory --out $BACKUPS_DIR/$BACKUP_NAME/json/mobile_inventory.json

mongoexport --db $MONGO_DATABASE --collection mts_change --out $BACKUPS_DIR/$BACKUP_NAME/json/mts_change.json
mongoexport --db $MONGO_DATABASE --collection mts_crm --out $BACKUPS_DIR/$BACKUP_NAME/json/mts_crm.json
mongoexport --db $MONGO_DATABASE --collection mts_demontage --out $BACKUPS_DIR/$BACKUP_NAME/json/mts_demontage.json
mongoexport --db $MONGO_DATABASE --collection mts_montage --out $BACKUPS_DIR/$BACKUP_NAME/json/mts_montage.json


mongoexport --db $MONGO_DATABASE --collection rem_decision --out $BACKUPS_DIR/$BACKUP_NAME/json/rem_decision.json
mongoexport --db $MONGO_DATABASE --collection rem_history --out $BACKUPS_DIR/$BACKUP_NAME/json/rem_history.json
mongoexport --db $MONGO_DATABASE --collection rem_nepoladki --out $BACKUPS_DIR/$BACKUP_NAME/json/rem_nepoladki.json





####
tar -jcvf $BACKUPS_DIR/$BACKUP_NAME/$BACKUP_NAME.gz $BACKUPS_DIR/$BACKUP_NAME
rm -R $BACKUPS_DIR/$BACKUP_NAME/json

chown user:user -R  $BACKUPS_DIR
chown user:user -R  $BACKUPS_DIR/$BACKUP_NAME


# Rename dump directory to backup name
mv dump $BACKUP_NAME
# Compress backup
tar -zcvf $BACKUPS_DIR/$BACKUP_NAME.tgz $BACKUP_NAME
# Delete uncompressed backup
rm -rf $BACKUP_NAME

# Delete backups older than retention period
find $BACKUPS_DIR -type f -mtime +$DAYSTORETAINBACKUP -exec rm {} +
echo "--------------------------------------------"
echo "Database backup complete!"
