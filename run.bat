@echo off
setlocal EnableExtensions

title Employees Management - Run

set "ROOT=%~dp0"
set "APP_HOST=127.0.0.1"
set "APP_PORT="
set "APP_URL="
set "MYSQL_HOST=127.0.0.1"
set "MYSQL_PORT="
set "MYSQL_USER=root"
set "MYSQL_PASS="
set "MYSQL_PASS_ARG="
set "DB_NAME=employee_manager"
set "RUNTIME_DIR=%ROOT%.runtime"

if defined DB_USER set "MYSQL_USER=%DB_USER%"
if defined DB_PASS set "MYSQL_PASS=%DB_PASS%"
if defined MYSQL_PASS set "MYSQL_PASS_ARG=-p%MYSQL_PASS%"

cd /d "%ROOT%"

echo ==========================================
echo Employees Management - Auto start
echo ==========================================
echo Project: %ROOT%
echo.

call :find_xampp
if not defined XAMPP_DIR (
    echo [ERROR] XAMPP was not found.
    echo Install XAMPP or set XAMPP_HOME, then run this file again.
    echo Example: set XAMPP_HOME=C:\xampp
    pause
    exit /b 1
)

set "PHP_EXE=%XAMPP_DIR%\php\php.exe"
set "MYSQL_EXE=%XAMPP_DIR%\mysql\bin\mysql.exe"
set "MYSQLD_EXE=%XAMPP_DIR%\mysql\bin\mysqld.exe"
set "MYSQL_DEFAULT_INI=%XAMPP_DIR%\mysql\bin\my.ini"

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
echo Apache is not required. The app uses PHP server on port 8000+.
echo.

call :pick_app_port
if not defined APP_PORT (
    echo [ERROR] Ports 8000-8005 are busy. Close another PHP server and run again.
    pause
    exit /b 1
)
set "APP_URL=http://%APP_HOST%:%APP_PORT%"

call :prepare_mysql
if errorlevel 1 exit /b 1

call :prepare_database
if errorlevel 1 exit /b 1

call :start_php_server
if errorlevel 1 exit /b 1

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

for %%D in ("%~d0\xampp" "%ROOT%..\xampp" "C:\xampp" "D:\xampp" "E:\xampp" "F:\xampp" "%ProgramFiles%\xampp" "%ProgramFiles(x86)%\xampp") do (
    if exist "%%~fD\php\php.exe" (
        set "XAMPP_DIR=%%~fD"
        exit /b 0
    )
)
exit /b 0

:pick_app_port
if defined APP_PORT (
    netstat -ano | findstr /R /C:":%APP_PORT% .*LISTENING" >nul
    if errorlevel 1 exit /b 0
    set "APP_PORT="
)

for %%P in (8000 8001 8002 8003 8004 8005) do (
    netstat -ano | findstr /R /C:":%%P .*LISTENING" >nul
    if errorlevel 1 (
        set "APP_PORT=%%P"
        exit /b 0
    )
)
exit /b 0

:prepare_mysql
if defined DB_PORT (
    set "MYSQL_PORT=%DB_PORT%"
    call :mysql_ping
    if not errorlevel 1 (
        echo MySQL is ready on configured port %MYSQL_PORT%.
        exit /b 0
    )
)

call :detect_mysql
if not errorlevel 1 exit /b 0

echo No accessible MySQL was found on ports 3308, 3307, 3306, 3309, 3310.
echo Starting XAMPP MySQL with its current my.ini...
call :start_mysql_default
call :wait_and_detect_mysql
if not errorlevel 1 exit /b 0

echo MySQL did not start with the current XAMPP config.
echo Trying fallback MySQL port 3308...
set "MYSQL_PORT=3308"
call :start_mysql_with_temp_ini
call :wait_mysql_port
if not errorlevel 1 exit /b 0

echo.
echo [ERROR] Cannot start or connect to MySQL.
echo Fix on the school computer:
echo 1. Open XAMPP Control Panel as Administrator.
echo 2. MySQL - Config - my.ini.
echo 3. Change every port=3306 to port=3308.
echo 4. Top-right Config - Service and Port Settings - MySQL - Main Port: 3308.
echo 5. Restart XAMPP and run this file again.
echo.
pause
exit /b 1

:detect_mysql
for %%P in (3308 3307 3306 3309 3310) do (
    set "MYSQL_PORT=%%P"
    call :mysql_ping
    if not errorlevel 1 (
        echo MySQL is ready on port %%P.
        exit /b 0
    )
)
exit /b 1

:start_mysql_default
if not exist "%MYSQLD_EXE%" (
    echo [ERROR] MySQL server was not found at:
    echo %MYSQLD_EXE%
    pause
    exit /b 1
)

pushd "%XAMPP_DIR%"
start "XAMPP MySQL" /min "mysql\bin\mysqld.exe" --defaults-file="mysql\bin\my.ini" --standalone
popd
exit /b 0

:wait_and_detect_mysql
for /l %%I in (1,1,20) do (
    call :detect_mysql
    if not errorlevel 1 exit /b 0
    timeout /t 1 /nobreak >nul
)
exit /b 1

:start_mysql_with_temp_ini
if not exist "%MYSQL_DEFAULT_INI%" exit /b 1
if not exist "%RUNTIME_DIR%" mkdir "%RUNTIME_DIR%" >nul 2>nul
set "MYSQL_TEMP_INI=%RUNTIME_DIR%\mysql-%MYSQL_PORT%.ini"

powershell -NoProfile -ExecutionPolicy Bypass -Command "$source = '%MYSQL_DEFAULT_INI%'; $target = '%MYSQL_TEMP_INI%'; (Get-Content -LiteralPath $source) -replace 'port\s*=\s*\d+', 'port=%MYSQL_PORT%' | Set-Content -LiteralPath $target -Encoding ASCII"
if errorlevel 1 exit /b 1

pushd "%XAMPP_DIR%"
start "XAMPP MySQL %MYSQL_PORT%" /min "mysql\bin\mysqld.exe" --defaults-file="%MYSQL_TEMP_INI%" --standalone
popd
exit /b 0

:wait_mysql_port
echo Waiting for MySQL on port %MYSQL_PORT%...
for /l %%I in (1,1,20) do (
    call :mysql_ping
    if not errorlevel 1 (
        echo MySQL is ready on port %MYSQL_PORT%.
        exit /b 0
    )
    timeout /t 1 /nobreak >nul
)
exit /b 1

:mysql_ping
"%MYSQL_EXE%" --protocol=tcp -h%MYSQL_HOST% -P%MYSQL_PORT% -u%MYSQL_USER% %MYSQL_PASS_ARG% -e "SELECT 1" >nul 2>nul
exit /b %errorlevel%

:prepare_database
if not exist "%ROOT%database.sql" (
    echo [WARN] database.sql was not found. Skipping database import.
    exit /b 0
)

echo Preparing database on MySQL port %MYSQL_PORT%...
"%MYSQL_EXE%" --protocol=tcp -h%MYSQL_HOST% -P%MYSQL_PORT% -u%MYSQL_USER% %MYSQL_PASS_ARG% < "%ROOT%database.sql"
if errorlevel 1 (
    echo [ERROR] Cannot import database.sql.
    echo Check MySQL user, password, port, or phpMyAdmin configuration.
    pause
    exit /b 1
)

echo Database is ready.
exit /b 0

:start_php_server
set "DB_HOST=%MYSQL_HOST%"
set "DB_PORT=%MYSQL_PORT%"
set "DB_USER=%MYSQL_USER%"
set "DB_NAME=%DB_NAME%"
set "DB_PASS=%MYSQL_PASS%"

echo Starting PHP server on %APP_URL% ...
start "Employees Management PHP Server" /min "%PHP_EXE%" -S %APP_HOST%:%APP_PORT% -t "%ROOT%public" "%ROOT%public\index.php"
timeout /t 2 /nobreak >nul
exit /b 0
