
# Property API

A RESTful API for managing real estate properties, built with Laravel.

## Run locally

1.  Clone the repository
2.  Build and start Docker containers:

bash ```

Copy

`docker compose build docker compose up`
```
3.  Setup the application:

bash

Copy

`docker compose run --rm api php artisan key:generate docker compose run --rm api php artisan migrate docker compose run --rm api php artisan db:seed # populate with sample data`

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

-   `GET /docs/api` - Interactive API documentation (open in browser)

## Testing

Run tests with:

bash ```

Copy

`docker compose run --rm api php artisan test`
```
## Notes

-   Uses SQLite for database
-   Sample data available via seeder