#!/bin/bash
days=$(date)
days=${days// /_}
mysqldump -u root --all-databases > /home/gabriel/git/it490f17/Backend/sqlBackup/"mysql_${days}.sql"

