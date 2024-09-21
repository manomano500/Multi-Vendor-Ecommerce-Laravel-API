# Multi-Vendor Bazaar Backend API


## Description

This project is a multi-vendor bazaar backend built with Laravel. It provides RESTful API endpoints for managing vendors, products, and orders in a marketplace environment.

## Features-

- **Vendor Management:** Vendors can create stores and add products to their stores.
- **Product Management:** Vendors can add, update, and delete products from their stores.
- **Order Management:** Customers can view products and make orders.
- **Payment Integration:** Customers can pay for their orders using Plutu Gateway.
- **Admin Interface:** Admin can manage vendors, products, and orders.
- **Authentication:** Users can sign up and log in to the platform.
- **Authorization:** Users can only access resources they are authorized to access.

## Prerequisites

- Php (version 7.4 or later)
- Composer
- Laravel 10
- MySQL


## Postman Workspace

For API testing and documentation, you can access the Postman workspace [here](https://www.postman.com/workspace/your-workspace-name).

## Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/manomano500/bazar-backend.git
    cd bazar-backend
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
