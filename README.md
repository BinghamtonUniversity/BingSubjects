# BingSubjects
BingSubjects is an Open Source Database for tracking research subjects across a variety of research studies.

# Developer Installation Instructions
1. Clone the repo locally: `git clone https://github.com/BinghamtonUniversity/BingSubjects.git`
2. Install Composer Dependencies: `composer install`
3. Copy the `.env.enample` file to `.env`
4. Setup MySQL Databases:
```bash
$ mysql
> CREATE DATABASE bingsubjects;
> CREATE USER 'bingsubjects'@'127.0.0.1' IDENTIFIED BY 'bingsubjects';
> GRANT ALL PRIVILEGES ON bingsubjects. * TO 'bingsubjects'@'127.0.0.1';
> exit;
```
4. Modify the `.env` file as follows:
```
DB_DATABASE=bingsubjects
DB_USERNAME=bingsubjects
DB_PASSWORD=bingsubjects
```
5. Generate App Key: `php artisan key:generate`
6. Run Migrations & Seed Database: `php artisan migrate:refresh --seed`
7. Serve the application `php artisan serve`

# License
IAMBing is open-source software licensed under the [MIT license](http://opensource.org/licenses/MIT).
