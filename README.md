# Votes

## Installation

## Backend/API

The application uses `mysql` as it's primary database and `sqlite` for testing.

Navigate to the `caffeine-backend` directory.

Copy `.env.example` to `.env` and change the variables to match your environment.

Run `composer install` in your terminal.

Run `php artisan key:generate`.

After setting up your development environment, run:
`php artisan migrate --seed` in your terminal to run both the database migrations and the seed

To refresh the database and run the seeder, run:
`php artisan migrate:fresh --seed` in your terminal to run both the database migrations and the seed

Easiest way to get the project running is to run using PHP's internal server. Run the command below:
`php artisan serve` and get the URL of the project from the command line.

The backend is a Laravel 6 based project. For more info on setting up your dev environment, visit:

[Laravel Installation Guide](https://laravel.com/docs/6.x#installing-laravel)


#### Testing API

To run tests, navigate to the `caffeine-backend` in the terminal and run:
`./vendor/bin/phpunit`


## Frontend

Navigate to the `caffeine-app` directory and run `npm install` in your terminal.

Navigate to `src/app/environments/env.ts` and modify the `apiUrl` variable to point to the URI of the `caffeine-backend` application.

Run `ng serve` to start the development server.

The frontent is an Angular 9 based application.