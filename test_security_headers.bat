@echo off
REM Script de test des headers de sécurité HTTP
echo ================================================
echo TEST DES HEADERS DE SECURITE HTTP
echo ================================================
echo.

REM URL de l'application
set URL=http://localhost:8000

echo Test de l'URL: %URL%
echo.
echo Verification des headers de securite...
echo.

REM Utilise curl pour récupérer les headers
curl -I -s %URL% > temp_headers.txt

echo --- X-XSS-Protection ---
findstr /C:"X-XSS-Protection" temp_headers.txt
if %errorlevel%==0 (echo [OK] Protection XSS presente) else (echo [ERREUR] Protection XSS manquante)
echo.

echo --- X-Frame-Options ---
findstr /C:"X-Frame-Options" temp_headers.txt
if %errorlevel%==0 (echo [OK] Protection Clickjacking presente) else (echo [ERREUR] Protection Clickjacking manquante)
echo.

echo --- X-Content-Type-Options ---
findstr /C:"X-Content-Type-Options" temp_headers.txt
if %errorlevel%==0 (echo [OK] Protection MIME Sniffing presente) else (echo [ERREUR] Protection MIME Sniffing manquante)
echo.

echo --- Content-Security-Policy ---
findstr /C:"Content-Security-Policy" temp_headers.txt
if %errorlevel%==0 (echo [OK] CSP presente) else (echo [ERREUR] CSP manquante)
echo.

echo --- Referrer-Policy ---
findstr /C:"Referrer-Policy" temp_headers.txt
if %errorlevel%==0 (echo [OK] Referrer Policy presente) else (echo [ERREUR] Referrer Policy manquante)
echo.

echo.
echo === Contenu complet des headers ===
type temp_headers.txt
echo.

del temp_headers.txt

echo ================================================
echo Test termine
echo ================================================
pause
