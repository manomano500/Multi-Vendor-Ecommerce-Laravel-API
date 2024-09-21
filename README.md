# Multi-Vendor E-Commerce Laravel API


## Description

This project is a multi-vendor  E-Commerce API is built with Laravel. It offers RESTful API endpoints for managing vendors, products, and orders making it ideal for developers looking to implement a robust e-commerce solution.

## Features-


### Vendor Management
Allow vendors to create stores and manage their products.

### Product Management
Enable vendors to add, update, and delete products effortlessly.

### Order Management
Customers can browse products and place orders seamlessly.

### Payment Integration
Securely process payments using the Plutu Payment Gateway.

### Admin Interface
Admins have full control to manage vendors, products, and orders.

### Authentication & Authorization
Users can register, log in, and access only their authorized resources.


## Prerequisites

- Php (version 7.4 or later)
- Composer
- Laravel 10
- MySQL


## Postman Workspace

For API testing and documentation, you can access the Postman workspace [here](https://www.postman.com/bazzar-2145/workspace/bazar-backend).

## Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/manomano500/bazar-backend.git
    cd Multi-Vendor-Ecommerce-Laravel-API
    ```
2. **Install dependencies:** 
    ```bash
    composer install
    ```
   
3. **Set up your environment variables:** 
    ```bash
    cp .env.example .env
    ```
    Update the `.env` file with your credentials.
4. **Generate an application key:** 
    ```bash
    php artisan key:generate
    ```
5. **Run the migrations:** 
    ```bash
    php artisan migrate
    ```
6. **Start the development server:** 
    ```bash
    php artisan serve
    ```
7. **Access the API:** 
    The API will be available at `http://localhost:8000`.
