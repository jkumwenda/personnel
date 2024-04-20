# Pharmacy Personnel Registration System

## Installation

### Requirements
- PHP 8.1 or higher
- Composer
- Node.js
- NPM
- MySQL 8.0 or higher
- Git
- Apache 2.4 or higher

### Steps
1. Clone the repository
```bash
git clone https://github.com/Laughwellreformed/personnel.git
```
2. Navigate to the project directory and install dependencies
```bash
composer install
npm install
```
3. Create a database
```bash
mysql -u root -p
CREATE DATABASE personelReg;
```
4. Create a .env file IF it doesn't exist (if it does, skip this step)
```bash
cp .env.example .env
```
5. Edit the .env file to match your database credentials
```bash
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=personelReg
DB_USERNAME=root
DB_PASSWORD=
```
5. Run the migrations
```bash
php artisan migrate
```
6. Run the seeder
```bash
php artisan db:seed
```
7. Start the server
```bash
php artisan serve
```
8. Start the frontend
```bash
npm run dev
```
9. Navigate to http://localhost:8000 in your browser
10. Login with the following credentials:
```bash
Super Admin
Email: superadmin@admin.com
Password: password

Personnel User
Email: personnel@user.com
Password: password
```
