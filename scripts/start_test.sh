#!/bin/bash

apache2

until curl -s --connect-timeout 1 -o /dev/null http://localhost
do
	echo "curl localhost: sleeping 1"
	sleep 1
done

DEST_DIR=/var/www/html/public/results

if [ -d ${DEST_DIR} ]
then
  rm -rf ${DEST_DIR}
fi

php /wait_for.php findeploy 999

mkdir ${DEST_DIR}

/var/www/html/vendor/bin/phpunit /var/www/html/tests \
  --coverage-clover ${DEST_DIR}/phpunit.coverage.xml \
  --coverage-html ${DEST_DIR}/coverage-html \
  --log-junit ${DEST_DIR}/phpunit.xml \
  --testdox-html ${DEST_DIR}/agile.html || true

tar czvvf ${DEST_DIR}/coverage-html.tgz ${DEST_DIR}/coverage-html

touch ${DEST_DIR}/fin

killall -9 apache2

/start.sh
