# Alko Price List

This is a PHP application that fetches Alko's daily price list, converts prices to GBP using the CurrencyLayer API, and stores the data in a MySQL database. It also provides a front-end to list the products and modify the order amounts using AJAX.

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Running Tests](#running-tests)
- [Directory Structure](#directory-structure)
- [Scheduling the PHP Script with Cron](#scheduling-the-php-script-with-cron)
- [Instructions for Docker Usage](#instructions-for-docker-usage)
- [License](#license)

## Features

- Fetches and parses Alko's daily price list (from an Excel file).
- Fetches the EUR to GBP conversion rate from the CurrencyLayer API.
- Stores product data in a MySQL database, with prices converted to GBP.
- Provides a simple front-end with AJAX functionality to list and modify product data.

## Requirements

- PHP 8.0 or higher
- MySQL 5.7+ or MariaDB
- Composer
- PHP extensions:
  - PDO
  - JSON
  - cURL
- CurrencyLayer API key (free tier is sufficient)

## Installation

### Step 1: Clone the repository

```bash
git clone https://github.com/esa-kian/draivi.git
cd draivi
```

### Step 2: Install dependencies

Make sure Composer is installed on your system. Then, run the following command to install the project dependencies:

```bash
composer install
```

### Step 3: Set up the database

1. Create a MySQL database:

    ```sql
    CREATE DATABASE alko_prices;
    ```

2. You can find the SQL structure in the database.sql file located in the root of the project. Import the SQL file into your MySQL database:

    ```bash
    mysql -u your_user -p alko_prices < database.sql
    ```

### Step 4: Configuration
You can configure the app using either  `.env`

Create a `.env` file from the provided `.env.example` file:

```bash
cp .env.example .env
```

Edit the `.env` file with your database credentials, CurrencyLayer API key, and the Alko price list URL:
```
DB_DSN="mysql:host=localhost;dbname=alko_prices;charset=utf8mb4"
DB_USERNAME="your_database_user"
DB_PASSWORD="your_database_password"
CURRENCY_API_KEY="your_currencylayer_api_key"
ALKO_EXCEL_URL="https://www.alko.fi/valikoimat-ja-hinnasto/hinnasto"
```

### Step 5: Run the script to fetch and store data
After configuring the database and API keys, run the script that fetches the Alko price list, retrieves the latest exchange rates, and stores the data in the database:
```bash
php public/retrieve.php
```

This script will:

- Fetch the Alko price list (Excel file).
- Parse the Excel file to extract the relevant data.
- Fetch the EUR to GBP exchange rate using the CurrencyLayer API.
- Update or insert the data into the MySQL database.


## Usage

### Frontend (AJAX functionality)

The application provides a simple front-end where you can view and interact with the product data stored in the database.

1. Start a local PHP server (or use Apache/Nginx):
```bash
php -S localhost:8000
```

2. Navigate to `http://localhost:8000/public/index.php` in your browser.

This page includes two buttons:
- List: Loads the product data from the database and displays it in a table.
- Empty: Clears the displayed table.

3. For each product listed, you can:
- Add: Increments the `orderamount` by 1.
- Clear: Sets the `orderamount` to 0.

Both actions update the database without reloading the page using AJAX.


## Running Tests
To run unit tests for the application, ensure PHPUnit is installed via Composer. Then run:

```bash
composer test
```

This command will execute all tests located in the `tests/` directory.

## Directory Structure

The project's directory structure is organized as follows:

```bash 
├── src/                    # Application source code
│   ├── Controllers/        # Controllers for business logic
│   ├── Services/           # Services for handling API calls and business operations
│   ├── Repositories/       # Repository classes for database operations
│   ├── DTOs/               # Data Transfer Objects
│   └── Models/             # Data models (Entities)
├── public/                 # Frontend PHP files for AJAX interface
├── tests/                  # Unit tests
├── database.sql            # SQL file to set up the database schema
├── config.php              # Configuration file (alternative to .env)
├── .env.example            # Example environment variables file
├── composer.json           # Composer configuration for dependencies
├── README.md               # Project documentation
└── .env                    # Environment configuration (optional)
```
## Scheduling the PHP Script with Cron

To automate the execution of the `retrieve.php` file every day at 8:00 AM, you can use `cron`, a time-based job scheduler in Unix-like operating systems. Follow these steps:

1. **Open the Crontab Configuration:**
Run the following command in your terminal to edit the crontab configuration for the current user:
   ```bash
   crontab -e
   ```

2. **Add a New Cron Job:**
Add the following line to the crontab file:
    ```bash
    0 8 * * * /usr/bin/php /path/to/project/public/retrieve.php >> /path/to/project/logs/output.log 2>&1
    ```


- `0 8 * * *` specifies that the command should run at 8:00 AM every day.
- `/usr/bin/php` should be replaced with the path to your PHP executable, which you can find by running `which php` in the terminal.
- `/path/to/project/public/retrieve.php` should be replaced with the actual path to your `retrieve.php` file.
- `>> /path/to/project/logs/output.log 2>&1` redirects standard output and errors to a log file, allowing you to review the output of the script execution.

3. **Save and Exit:**
After adding the line, save the changes and exit the editor. The cron job will now be scheduled to run the specified PHP script at the designated time.


## Instructions for Docker Usage
1. **Build the Docker Image:**

### NOTE: 
- Make sure to update the `DB_DSN` variable in your `.env` file with the correct database connection details to ensure proper connectivity.

```
docker build -t php-alko-app .
```
2. **Run the Container:**
```
docker run -p -d 8000:80 php-alko-app
```
3. **Stop the Container:**
```
docker stop php-alko-app
```

## License

This project is open-source and available under the MIT License.
