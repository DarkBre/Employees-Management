@echo off
setlocal

title Employees Management - Run

set "ROOT=%~dp0"
set "APP_PORT=8000"
set "APP_URL=http://127.0.0.1:%APP_PORT%"

cd /d "%ROOT%"

echo ==========================================
echo Employees Management - Auto start
echo ==========================================
echo Project: %ROOT%
echo.

call :find_xampp
if not defined XAMPP_DIR (
    echo [ERROR] XAMPP was not found.
    echo Install XAMPP, then run this file again.
    echo Common path: C:\xampp
    pause
    exit /b 1
)

set "PHP_EXE=%XAMPP_DIR%\php\php.exe"
set "MYSQL_EXE=%XAMPP_DIR%\mysql\bin\mysql.exe"
set "MYSQLD_EXE=%XAMPP_DIR%\mysql\bin\mysqld.exe"

if not exist "%PHP_EXE%" (
    echo [ERROR] PHP was not found at:
    echo %PHP_EXE%
    pause
    exit /b 1
)

if not exist "%MYSQL_EXE%" (
    echo [ERROR] MySQL client was not found at:
    echo %MYSQL_EXE%
    pause
    exit /b 1
)

echo XAMPP: %XAMPP_DIR%
echo.

call :start_apache
call :start_mysql
call :wait_mysql
call :prepare_database
call :start_php_server

echo.
echo Web is ready:
echo %APP_URL%
echo.
start "" "%APP_URL%"

echo Keep the PHP/MySQL windows running while using the web.
echo Press any key to close this launcher window.
pause >nul
exit /b 0

:find_xampp
if defined XAMPP_HOME (
    if exist "%XAMPP_HOME%\php\php.exe" (
        set "XAMPP_DIR=%XAMPP_HOME%"
        exit /b 0
    )
)

for %%D in ("%~d0\xampp" "C:\xampp" "D:\xampp" "E:\xampp" "%ProgramFiles%\xampp" "%ProgramFiles(x86)%\xampp") do (
    if exist "%%~D\php\php.exe" (
        set "XAMPP_DIR=%%~D"
        exit /b 0
    )
)
exit /b 0

:start_apache
if not exist "%XAMPP_DIR%\apache\bin\httpd.exe" exit /b 0

netstat -ano | findstr /R /C:":80 .*LISTENING" >nul
if not errorlevel 1 (
    echo Apache/port 80 is already running.
    exit /b 0
)

echo Starting Apache...
pushd "%XAMPP_DIR%"
start "XAMPP Apache" /min "apache\bin\httpd.exe"
popd
timeout /t 2 /nobreak >nul
exit /b 0

:start_mysql
netstat -ano | findstr /R /C:":3306 .*LISTENING" >nul
if not errorlevel 1 (
    echo MySQL/port 3306 is already running.
    exit /b 0
)

if not exist "%MYSQLD_EXE%" (
    echo [ERROR] MySQL server was not found at:
    echo %MYSQLD_EXE%
    pause
    exit /b 1
)

echo Starting MySQL...
pushd "%XAMPP_DIR%"
start "XAMPP MySQL" /min "mysql\bin\mysqld.exe" --defaults-file="mysql\bin\my.ini" --standalone
popd
exit /b 0

:wait_mysql
echo Waiting for MySQL...
for /l %%I in (1,1,25) do (
    "%MYSQL_EXE%" -uroot -e "SELECT 1" >nul 2>nul
    if not errorlevel 1 goto mysql_ready
    timeout /t 1 /nobreak >nul
)

echo [ERROR] MySQL is not ready.
echo If XAMPP root has a password, update config\config.php and import database manually.
pause
exit /b 1

:mysql_ready
echo MySQL is ready.
exit /b 0

:prepare_database
if not exist "%ROOT%database.sql" (
    echo [WARN] database.sql was not found. Skipping database import.
    exit /b 0
)

echo Preparing database from database.sql...
"%MYSQL_EXE%" -uroot < "%ROOT%database.sql"
if errorlevel 1 (
    echo [ERROR] Cannot import database.sql.
    echo Check MySQL root password or phpMyAdmin configuration.
    pause
    exit /b 1
)

echo Database is ready.
exit /b 0

:start_php_server
netstat -ano | findstr /R /C:":%APP_PORT% .*LISTENING" >nul
if not errorlevel 1 (
    echo PHP server/port %APP_PORT% is already running.
    exit /b 0
)

echo Starting PHP server on %APP_URL% ...
start "Employees Management PHP Server" /min "%PHP_EXE%" -S 127.0.0.1:%APP_PORT% -t "%ROOT%public" "%ROOT%public\index.php"
timeout /t 2 /nobreak >nul
exit /b 0
