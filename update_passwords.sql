-- Mise à jour des mots de passe avec hash bcrypt pour "admin123"
UPDATE user SET password = '$2y$13$4v.VUUsBCQPx0Db1HreATOx0SfOptPW6Vth6Y1N6HpBQcbXGOlrgS' WHERE email = 'admin@example.com';
UPDATE user SET password = '$2y$13$4v.VUUsBCQPx0Db1HreATOx0SfOptPW6Vth6Y1N6HpBQcbXGOlrgS' WHERE email = 'user@example.com';

SELECT id, email, nom, prenom, roles FROM user;
