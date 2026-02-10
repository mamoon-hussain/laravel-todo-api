# Laravel Todo API

A simple Laravel-based API for managing tasks (create, read, update, delete, and list with search/filtering).

## Requirements

- PHP 8.0.2 or higher
- MySQL database
- Composer

## Installation

1. **Clone the repository**:
   git clone https://github.com/mamoon-hussain/laravel-todo-api.git cd laravel-todo-api

2. **Install dependencies**:
   composer install

3. **Set up the environment**:
- Copy `.env.example` to `.env`:
  ```
  cp .env.example .env
  ```
- Edit `.env` and configure your database:
  ```
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=todo_db
  DB_USERNAME=root
  DB_PASSWORD=
  ```
- Generate the application key:
  ```
  php artisan key:generate
  ```
  
4. **Create the database**:
- In MySQL, create a database named `todo_db`:
  ```
  CREATE DATABASE todo_db;
  ```
- Grant permissions to the `root` user if needed (e.g., `GRANT ALL PRIVILEGES ON todo_db.* TO 'root'@'localhost';`).

5. **Run migrations**:
   php artisan migrate

6. **Start the server**:
   php artisan serve

- Use Swagger UI at `http://localhost:8000/api/docs`

## API Endpoints

- `GET /api/task/index` - List tasks (with pagination, search, and status filter)
- `GET /api/task/details?task_id=1` - Get task details
- `POST /api/task/create` - Create a new task
- `PUT /api/task/update` - Update a task
- `DELETE /api/task/delete?task_id=1` - Delete a task

## Notes

- This project uses Laravel 9.x (check `composer.json` for exact version).
- For production, use a non-root MySQL user and set a strong password.
- If you encounter issues, ensure PHP and MySQL versions match the requirements.

## License

This project is open-sourced under the MIT license.
