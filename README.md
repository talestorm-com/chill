# chill

## Запуск девелоперского окружения :
```shell script
docker-compose -f develop/docker-compose.yml -p chill up -d
./develop/scripts/init_db.sh
sudo chmod -R 0777 .
sudo nano /etc/hosts/   - добавить 10.227.64.1   chill.local
Добавить проект в исключения Add block.
```
## Остановка девелоперского окружения :
```shell script
./develop/scripts/docker_rm_all.sh
```
Адрес админки https://10.227.64.1/admin/Pages/index :
```shell script
Login: info@chillvision.ru
password: Chill2019
 ```
Адрес pgadmin http://10.227.64.6/ :
```shell script
Login: root
password: 123