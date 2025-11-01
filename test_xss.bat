@echo off
echo ========================================
echo    TEST XSS - RECHERCHE
echo ========================================
echo.

echo Test 1: Script simple
curl -s "http://localhost:8000/products/search?q=^<script^>alert('XSS')^</script^>" -o test_xss_1.html
echo Resultat enregistre dans test_xss_1.html
echo.

echo Test 2: Image avec onerror
curl -s "http://localhost:8000/products/search?q=^<img+src=x+onerror=alert('XSS')^>" -o test_xss_2.html
echo Resultat enregistre dans test_xss_2.html
echo.

echo Test 3: Event handler
curl -s "http://localhost:8000/products/search?q=test+onclick=alert('XSS')" -o test_xss_3.html
echo Resultat enregistre dans test_xss_3.html
echo.

echo ========================================
echo Ouvrez les fichiers HTML pour verifier
echo Le texte doit etre echappe, PAS execute
echo ========================================
pause
