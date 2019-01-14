
if [ "${MAIL_TO}" == "" ]
then
  echo "Falta dar de alta el mail destino"
  echo ""
  echo "export MAIL_TO=mimail@netlabs.com.ar"
  exit 255
fi

docker stack deploy -c dev.yml subastas --with-registry-auth
