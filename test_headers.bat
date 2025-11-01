@echo off
echo ========================================
echo    TEST HEADERS DE SECURITE
echo ========================================
echo.

curl -I http://localhost:8000 > test_headers.txt 2>&1

echo Headers enregistres dans test_headers.txt
echo.
echo Verification des headers:
echo.

findstr /C:"X-XSS-Protection" test_headers.txt && echo [OK] X-XSS-Protection trouve || echo [ERREUR] X-XSS-Protection manquant
findstr /C:"X-Frame-Options" test_headers.txt && echo [OK] X-Frame-Options trouve || echo [ERREUR] X-Frame-Options manquant
findstr /C:"X-Content-Type-Options" test_headers.txt && echo [OK] X-Content-Type-Options trouve || echo [ERREUR] X-Content-Type-Options manquant
findstr /C:"Referrer-Policy" test_headers.txt && echo [OK] Referrer-Policy trouve || echo [ERREUR] Referrer-Policy manquant
findstr /C:"Content-Security-Policy" test_headers.txt && echo [OK] Content-Security-Policy trouve || echo [ERREUR] Content-Security-Policy manquant

echo.
echo ========================================
echo Ouvrez test_headers.txt pour voir tous les headers
echo ========================================
pause
