CREATE DATABASE IF NOT EXISTS sasbilling;
CREATE USER IF NOT EXISTS 'sasbilling'@'localhost' IDENTIFIED BY 'password123';
GRANT ALL PRIVILEGES ON sasbilling.* TO 'sasbilling'@'localhost';
FLUSH PRIVILEGES;