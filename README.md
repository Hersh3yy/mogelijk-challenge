
# Property API

A RESTful API for managing real estate properties, built with Laravel.

## Features

-   CRUD operations for property listings
-   Price range filtering
-   Pagination support
-   API documentation with Scramble
-   SQLite database for simplicity
-   Full test coverage

## Setup

1.  Clone the repository
2.  Build and start Docker containers:

bash

Copy

`docker compose build docker compose up`

3.  Generate application key and setup database:

bash

Copy

`docker compose run --rm api php artisan key:generate docker compose run --rm api php artisan migrate`

## API Endpoints

### Properties

-   `GET /api/properties` - List all properties
    -   Optional query parameters:
        -   `price_min`: Minimum price filter
        -   `price_max`: Maximum price filter
        -   `per_page`: Items per page (default: 15)
        -   `page`: Page number
-   `POST /api/properties` - Create a new property
    -   Required fields:
        -   `name`: String, min 3 characters
        -   `address`: String
        -   `price`: Number, greater than 0
-   `GET /api/properties/{id}` - Get a specific property
-   `PUT /api/properties/{id}` - Update a property
    -   Optional fields (same validation as POST)
-   `DELETE /api/properties/{id}` - Delete a property

### Documentation

-   `GET /docs/api` - Interactive API documentation

## Testing

Run tests with:

bash

Copy

`docker compose run --rm api php artisan test`
