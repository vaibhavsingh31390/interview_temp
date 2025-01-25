# User Order Cycle Laravel Application

## Features

-   User Management
-   Order Product Creation
-   Product Cycle Tracking
-   AJAX Form Submission
-   Reverse Routing for User Details
-   View All Users

## Installation

1.  Clone the repository.

2.  Install dependencies:

    ```bash
    composer install
    ```

3.  Set up your `.env` file with PostgreSQL credentials:

        ```env

    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=interview
    DB_USERNAME=postgres
    DB_PASSWORD=postgres

        ```

4.  Run migrations:

    ```bash
    php artisan migrate
    ```

5.  Install frontend dependencies:

    ```bash
    npm install
    npm run dev
    ```

6.  Serve the application:

    ```bash
    php artisan serve
    ```

7.  To create users, product_order and product cycle, visit:

    ```
    http://127.0.0.1:8000/
    ```

8.  To view users specific data with product_order and product cycle, visit:

    ```
    http://127.0.0.1:8000/view/{user_id}
    ```

9.  To view all users, visit:
    ```
    http://127.0.0.1:8000/view-all
    ```

## Author

Vaibhav Singh
