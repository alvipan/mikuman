@echo off

start php artisan serve
start php artisan schedule:work
start npm run dev

timeout /t 5

start http://localhost:8000