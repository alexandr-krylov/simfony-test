
# How to install and run

git clone <https://github.com/alexandr-krylov/simfony-test.git>  
cd simfony-test  
docker-compose up -d  
docker exec -it php composer install
docker exec -it php bin/console doctrine:migrations:migrate

## Run tests

docker exec -it php cp phpunit.dist.xml phpunit.xml
docker exec -it php bin/phpunit  
