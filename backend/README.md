Install docker
clone the project
create a .env file and copy .env.example file to it
Run:
 docker-compose up -d nginx mysql phpmyadmin redis workspace 
 
To enter the workspace container where you write the commands:
docker-compose exec workspace bash
run composer install


create a database in phpmyadmin

you can see phpmyadmin port with docker ps
credentials:


run:
php artisan migrate
php artisan passport:install



if you want to reset the migrations and migrate them again run:
php artisan migrate:reset
php artisan migrate

if doesnt work run php artisan migrate:fresh which runs does the job without errors using force.
