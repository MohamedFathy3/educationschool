@echo off
php artisan optimize:c
php artisan optimize
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan queue:restart
php artisan ser --host api.pyramidsfreight.test --port 8800
