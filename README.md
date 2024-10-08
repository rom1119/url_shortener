# url_shortener
using symfony and react url shorter


# requirements
app launched on 
docker version 20.10.11
docker-compose 1.29.2

# running
1. run command ~ docker-compose up --build

2. go to `php` container from console command :
# `docker exec -it <php_container_id> /bin/sh`
<php_container_id> can be show by this command `docker container ls`
3.from container go to /app path and then run this command 
# `php bin/console doctrine:migrations:migrate --no-interaction`
4. finnally go to localhost:3000 in you browser
