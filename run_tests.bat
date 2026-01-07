@echo off
REM Script de lancement rapide des tests US-11 et US-12
echo ================================================
echo    TESTS US-11 ^& US-12 - FreshMarket
echo ================================================
echo.

REM US-11 : Tests Automatiques
echo [US-11] Tests Automatiques ^& CI
echo ---------------------------------------
echo.
echo Lancement des tests PHPUnit...
php bin/phpunit --testdox
echo.

if %errorlevel%==0 (
    echo [OK] Tous les tests sont passes !
) else (
    echo [ERREUR] Certains tests ont echoue
)

echo.
echo.

REM US-12 : Securite
echo [US-12] Securite / Hardening
echo ---------------------------------------
echo.

echo Test 1/3 : Headers de securite HTTP
echo.
call test_security_headers.bat
echo.

echo Test 2/3 : Protection CSRF
start http://localhost:8000/test_csrf_protection.html
echo Page de test CSRF ouverte dans le navigateur
echo.

echo Test 3/3 : Protection XSS
start http://localhost:8000/test_xss_protection.html
echo Page de test XSS ouverte dans le navigateur
echo.

echo.
echo ================================================
echo Tests termines !
echo Consultez TESTS_ET_SECURITE.md pour plus de details
echo ================================================
pause
