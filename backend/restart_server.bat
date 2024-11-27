@echo off
echo Reiniciando el servidor PHP...

REM Detener todos los procesos de PHP
taskkill /F /IM php.exe

REM Esperar un momento
timeout /t 2

REM Iniciar el servidor PHP en el puerto 8000
start php -S localhost:8000 -t backend

echo Servidor PHP reiniciado en http://localhost:8000 