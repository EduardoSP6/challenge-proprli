# Proprli API challenge

## Project Description:

Our clients operate in the real estate sector, managing multiple buildings within their accounts.
We need to provide a tool that allows our owners to create tasks for their teams to perform
within each building and add comments to their tasks for tracking progress..
These tasks should be assignable to any team member and have statuses
such as Open, In Progress, Completed, or Rejected.

## Technical Requirements:

- Develop an application using Laravel 10 with REST architecture.
- Implement GET endpoint for listing tasks of a building along with their comments.
- Implement POST endpoint for creating a new task.
- Implement POST endpoint for creating a new comment for a task.
- Define the payload structure for task and comment creation, considering necessary relationships and information for
  possible filters.
- Implement filtering functionality, considering at least three filters such as date range of creation and assigned
  user, or task status and the building it belongs to.

## Installation:

````
docker-compose up -d
````

````
composer install
````

````
php artisan key:generate
````

## Creating tables and populate the database:

````
php artisan migrate --seed
````

## Run tests with PHPUnit:

````
php artisan test
````

## Run static code analysis with PHPStan:

````
composer analyse
````
