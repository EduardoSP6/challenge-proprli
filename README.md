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
cp .env.example .env
````

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

## Generate API Docs with Swagger:

````
php artisan l5-swagger:generate
````

After executing the above command, the documentation will be 
available at: http://localhost:8000/api/documentation

## Code Structure:

The structure was based on Clean Architecture, DDD and SOLID concepts, where the part of the code that 
holds the business rules is in the **./src directory**. This directory is subdivided into:

* Application - Layer responsible for the application flow. It contains use cases, interfaces, DTOs, etc.


* Domain - Layer responsible for executing business rules. In it you will find entities, ValueObjects, Enums and Factories.


* Infrastructure - Layer has concrete implementations of interfaces, such as: Repositories, Services, etc.


* Tests - Layer responsible for unit and integration tests of the application. The tests were performed with PHPUnit and are in the ./tests/ directory.

