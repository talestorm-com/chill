# chill

## Запуск девелоперского окружения :
```shell script
docker-compose -f develop/docker-compose.yml -p chill up -d
./develop/scripts/init_db.sh

sudo chmod -R 0777 .
```
## Остановка девелоперского окружения :
```shell script
./develop/scripts/docker_rm_all.sh
```
Адрес админки http://10.227.64.2/admin :
```shell script
Login: admin
password: 123456
 ```
Адрес pgadmin http://10.227.64.6/ :
```shell script
Login: root
password: 123