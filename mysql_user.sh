#!/bin/bash
# Create MySQL user
# Author : Kaushal Kishore <kaushal.rahuljaiswal@gmail.com>
/usr/libexec/mariadb-prepare-db-dir mariadb.service > /dev/null 2>&1 &&
    /usr/bin/mysqld_safe > /dev/null 2>&1 &

RET=1
while [[ RET -ne 0 ]]; do
    echo "=> Waiting for confirmation of MySQL service startup"
    sleep 10
    mysql -uroot -p -e "status" > /dev/null 2>&1 ||
        mysql -uroot -p${MYSQL_ROOT_PASS} -e "status" > /dev/null 2>&1
    RET=$?
done

mysql -uroot -e "UPDATE mysql.user SET password=PASSWORD('${MYSQL_ROOT_PASS}') WHERE User='root'" > /dev/null 2>&1

mysqladmin -uroot shutdown > /dev/null 2>&1 ||
    mysqladmin -uroot -p${MYSQL_ROOT_PASS} shutdown
