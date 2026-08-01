@echo off
title SIMAK Demo Starter
echo ============================================
echo  SIMAK Demo - menyalakan backend + ngrok
echo ============================================
echo.

echo [1/2] Menyalakan backend Laravel di port 8000...
start "SIMAK Backend" cmd /k "cd /d C:\laragon\www\project-mobile\praktikum24 && php artisan serve"
timeout /t 3 /nobreak >nul

echo [2/2] Menyalakan tunnel ngrok...
start "SIMAK ngrok" cmd /k "C:\laragon\bin\ngrok\ngrok.exe http --url=https://aqua-cranial-dreamy.ngrok-free.dev 8000"

echo.
echo Selesai! Pastikan MySQL Laragon juga menyala.
echo URL publik: https://aqua-cranial-dreamy.ngrok-free.dev
echo Untuk berhenti: tutup kedua jendela terminal yang terbuka.
pause
