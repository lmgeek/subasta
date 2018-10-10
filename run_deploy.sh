docker run -ti \
  -e DB_HOST=192.168.6.46 \
  -e DB_DATABASE=subastas \
  -e DB_USERNAME=subastas \
  -e DB_PASSWORD="Su845t45!" \
  -e SUBJECT="deploy subastas" \
  --entrypoint="" \
  subastas_deploy bash
