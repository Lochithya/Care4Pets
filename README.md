# Pet Store E-commerce Platform

A professional e-commerce website for a pet store built using HTML, CSS, JavaScript, PHP, and MySQL. This project demonstrates a complete web application with user authentication, product management, shopping cart functionality, and order processing.

## Table of Contents

1. [Project Overview](#project-overview)
2. [Features](#features)
3. [Technology Stack](#technology-stack)
4. [Installation Guide](#installation-guide)
5. [Database Setup](#database-setup)
6. [Project Structure](#project-structure)
7. [Usage Instructions](#usage-instructions)
8. [API Endpoints](#api-endpoints)
9. [Testing](#testing)
10. [Deployment](#deployment)
11. [Contributing](#contributing)
12. [License](#license)

## Project Overview

This pet store e-commerce platform is designed as a university project to demonstrate proficiency in full-stack web development. The application provides a complete online shopping experience for pet owners, featuring product browsing, user authentication, shopping cart management, and order processing.

### Key Objectives

- Create a professional, responsive web application
- Implement secure user authentication and session management
- Develop a robust product catalog with category filtering
- Build a functional shopping cart and checkout system
- Demonstrate best practices in web development
- Ensure cross-browser compatibility and mobile responsiveness

## Features

### Core Functionality

- **User Authentication**: Secure registration and login system with password hashing
- **Product Catalog**: Comprehensive product listing with category-based filtering
- **Shopping Cart**: Add, update, and remove items with real-time total calculation
- **Order Management**: Complete checkout process with order history
- **Responsive Design**: Mobile-friendly interface that works on all devices
- **Admin Features**: Product management capabilities for administrators

### Page Structure

1. **Home Page**: Welcome section with featured products and category navigation
2. **About Us**: Company information and service details
3. **Products**: Complete product catalog with filtering options
4. **Shopping Cart**: Cart management and checkout functionality
5. **Contact Us**: Contact form and business information
6. **User Authentication**: Login and registration pages

### Product Categories

The platform now features a sophisticated two-level filtering system:

#### Pet Types:
- **Dogs**: Products specifically for dogs
- **Cats**: Products specifically for cats  
- **Birds**: Products specifically for birds
- **Small Pets**: Products for hamsters, rabbits, guinea pigs, etc.

#### Product Types:
- **Pets**: Live animals available for adoption/purchase
- **Food**: Nutritional products and treats
- **Toys**: Entertainment and enrichment items
- **Accessories**: Collars, leashes, cages, litter, and other supplies

#### Advanced Filtering:
Users can filter products by:
1. **Pet Type Only**: See all products for a specific pet (e.g., all dog products)
2. **Product Type Only**: See specific product categories across all pets (e.g., all food products)
3. **Combined Filtering**: Precise filtering by both criteria (e.g., dog food, cat toys)
4. **No Filters**: Browse all available products

## Technology Stack

### Frontend Technologies

- **HTML5**: Semantic markup for content structure
- **CSS3**: Modern styling with flexbox and grid layouts
- **JavaScript**: Interactive functionality and AJAX requests
- **Responsive Design**: Mobile-first approach with media queries

### Backend Technologies

- **PHP 8.1**: Server-side scripting and business logic
- **MySQL 8.0**: Relational database for data storage
- **Session Management**: Secure user session handling

### Development Tools

- **Git**: Version control system
- **VSCode**: Recommended development environment
- **XAMPP/LAMP**: Local development server stack

## Installation Guide

### Prerequisites

Before installing the project, ensure you have the following software installed:

- PHP 8.1 or higher
- MySQL 8.0 or higher
- Web server (Apache/Nginx) or PHP built-in server
- Git (for version control)

### Step-by-Step Installation

1. **Clone the Repository**
   ```bash
   git clone <repository-url>
   cd pet_store_ecommerce
   ```

2. **Install PHP and MySQL**
   ```bash
   # Ubuntu/Debian
   sudo apt update
   sudo apt install php php-mysql php-cli mysql-server
   
   # Start MySQL service
   sudo service mysql start
   ```

3. **Configure Database**
   ```bash
   # Access MySQL as root
   sudo mysql
   
   # Create database and user
   CREATE DATABASE pet_store;
   CREATE USER 'petstore_user'@'localhost' IDENTIFIED BY 'password123';
   GRANT ALL PRIVILEGES ON pet_store.* TO 'petstore_user'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   ```

4. **Import Database Schema**
   ```bash
   mysql -u petstore_user -p pet_store < database/schema.sql
   mysql -u petstore_user -p pet_store < database/sample_data.sql
   ```

5. **Configure Application**
   - Update database credentials in `includes/config.php` if necessary
   - Ensure proper file permissions for the web server

6. **Start Development Server**
   ```bash
   cd public
   php -S localhost:8000
   ```

7. **Access the Application**
   - Open your web browser and navigate to `http://localhost:8000`
   - Use the test account: username `admin`, password `password123`

## Database Setup

### Database Schema

The application uses a well-structured relational database with the following tables:

#### Users Table
- `id`: Primary key (auto-increment)
- `username`: Unique username for login
- `email`: User email address
- `password`: Hashed password using PHP's password_hash()
- `created_at`: Account creation timestamp

#### Pet Types Table
- `id`: Primary key (auto-increment)
- `name`: Pet type name (Dogs, Cats, Birds, Small Pets)

#### Product Types Table
- `id`: Primary key (auto-increment)
- `name`: Product type name (Pets, Food, Toys, Accessories)

#### Products Table
- `id`: Primary key (auto-increment)
- `name`: Product name
- `description`: Product description
- `price`: Product price (decimal)
- `stock_quantity`: Available inventory
- `image_url`: Product image path
- `pet_type_id`: Foreign key to pet_types table
- `product_type_id`: Foreign key to product_types table

#### Orders Table
- `id`: Primary key (auto-increment)
- `user_id`: Foreign key to users table
- `order_date`: Order creation timestamp
- `total_amount`: Order total (decimal)
- `status`: Order status (pending, completed, cancelled)

#### Order Items Table
- `id`: Primary key (auto-increment)
- `order_id`: Foreign key to orders table
- `product_id`: Foreign key to products table
- `quantity`: Item quantity
- `price`: Item price at time of order

#### Cart Table
- `id`: Primary key (auto-increment)
- `user_id`: Foreign key to users table
- `product_id`: Foreign key to products table
- `quantity`: Item quantity in cart

### Sample Data

The database includes sample data for testing:
- 4 pet types (Dogs, Cats, Birds, Small Pets)
- 4 product types (Pets, Food, Toys, Accessories)
- 10 sample products with proper categorization
- 1 test user account (admin/password123)

### Database Migration

If upgrading from the original single-category system, run the migration script:
```bash
mysql -u petstore_user -p pet_store < database/migration_nested_categories.sql
```

This script will:
1. Create new pet_types and product_types tables
2. Migrate existing product data to the new schema
3. Remove the old categories table
4. Preserve all existing data while upgrading the structure

## Project Structure

```
pet_store_ecommerce/
├── public/                 # Web-accessible files
│   ├── index.php          # Homepage
│   ├── products.php       # Product catalog
│   ├── cart.php           # Shopping cart
│   ├── login.php          # User login
│   ├── register.php       # User registration
│   ├── about.php          # About us page
│   ├── contact.php        # Contact page
│   └── logout.php         # Logout functionality
├── includes/              # PHP include files
│   ├── config.php         # Database configuration
│   ├── auth.php           # Authentication functions
│   ├── product.php        # Product management functions
│   ├── cart.php           # Cart management functions
│   └── order.php          # Order processing functions
├── api/                   # API endpoints
│   ├── cart_actions.php   # Cart AJAX endpoints
│   └── checkout.php       # Checkout processing
├── css/                   # Stylesheets
│   └── style.css          # Main stylesheet
├── js/                    # JavaScript files
│   └── cart.js            # Cart functionality
├── images/                # Product and site images
├── database/              # Database files
│   ├── schema.sql         # Database structure
│   └── sample_data.sql    # Sample data
└── README.md              # Project documentation
```

## Usage Instructions

### For End Users

1. **Browse Products**
   - Visit the homepage to see featured products
   - Navigate to the Products page for the complete catalog
   - Use category filters to find specific types of products

2. **User Registration**
   - Click "Login" in the navigation menu
   - Select "Register here" to create a new account
   - Fill in username, email, and password
   - Login with your new credentials

3. **Shopping Process**
   - Browse products and click "Add to Cart" for desired items
   - View your cart by clicking the "Cart" menu item
   - Update quantities or remove items as needed
   - Click "Proceed to Checkout" to place your order

4. **Account Management**
   - Login to access cart and checkout functionality
   - View order history (feature can be extended)
   - Logout when finished shopping

### For Administrators

1. **Product Management**
   - Use the database interface or create admin pages
   - Add new products with images and descriptions
   - Update inventory levels and prices
   - Manage product categories

2. **Order Management**
   - Monitor orders through the database
   - Update order statuses
   - Generate reports (feature can be extended)

## API Endpoints

The application includes several AJAX endpoints for dynamic functionality:

### Cart Management API (`/api/cart_actions.php`)

- **Add to Cart**
  - Method: POST
  - Parameters: `action=add`, `product_id`, `quantity`
  - Response: JSON success/error message

- **Update Cart**
  - Method: POST
  - Parameters: `action=update`, `product_id`, `quantity`
  - Response: JSON success/error message

- **Remove from Cart**
  - Method: POST
  - Parameters: `action=remove`, `product_id`
  - Response: JSON success/error message

### Checkout API (`/api/checkout.php`)

- **Process Checkout**
  - Method: POST
  - Parameters: `action=checkout`
  - Response: JSON with order ID or error message

## Testing

### Manual Testing Checklist

1. **Homepage Testing**
   - [ ] Page loads correctly
   - [ ] Navigation menu works
   - [ ] Featured products display
   - [ ] Category links function

2. **User Authentication**
   - [ ] Registration form validation
   - [ ] Login functionality
   - [ ] Session management
   - [ ] Logout process

3. **Product Catalog**
   - [ ] Product listing displays
   - [ ] Category filtering works
   - [ ] Product images load
   - [ ] Price formatting correct

4. **Shopping Cart**
   - [ ] Add to cart functionality
   - [ ] Quantity updates
   - [ ] Item removal
   - [ ] Total calculation

5. **Responsive Design**
   - [ ] Mobile compatibility
   - [ ] Tablet layout
   - [ ] Desktop optimization

### Test Accounts

- **Admin Account**: username `admin`, password `password123`

### Browser Compatibility

Tested and compatible with:
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Deployment

### Production Deployment Steps

1. **Server Requirements**
   - PHP 8.1+ with MySQL extension
   - MySQL 8.0+ or MariaDB 10.5+
   - Web server (Apache/Nginx)
   - SSL certificate (recommended)

2. **File Upload**
   - Upload all files to web server
   - Set proper file permissions (755 for directories, 644 for files)
   - Ensure `images/` directory is writable

3. **Database Configuration**
   - Create production database
   - Import schema and sample data
   - Update `includes/config.php` with production credentials

4. **Security Considerations**
   - Change default passwords
   - Enable HTTPS
   - Configure proper error reporting
   - Set up regular backups

### Environment Configuration

For production deployment, update the following in `includes/config.php`:

```php
// Production database configuration
define('DB_HOST', 'your-production-host');
define('DB_USERNAME', 'your-production-username');
define('DB_PASSWORD', 'your-secure-password');
define('DB_NAME', 'your-production-database');
```

## Contributing

### Development Guidelines

1. **Code Standards**
   - Follow PSR-12 coding standards for PHP
   - Use meaningful variable and function names
   - Comment complex logic
   - Maintain consistent indentation

2. **Git Workflow**
   - Create feature branches for new functionality
   - Write descriptive commit messages
   - Test thoroughly before merging

3. **Security Best Practices**
   - Validate and sanitize all user inputs
   - Use prepared statements for database queries
   - Implement proper session management
   - Hash passwords securely

### Future Enhancements

Potential improvements for the project:

- **Admin Dashboard**: Complete admin interface for product and order management
- **Inventory Management**: Low stock alerts and automatic reordering
- **Search Functionality**: Product search with filters
- **Multi-language Support**: Internationalization capabilities

## License

This project is developed for educational purposes as a university assignment. Feel free to use and modify the code for learning and non-commercial purposes.

## Support

For questions or issues related to this project:

1. Check the documentation thoroughly
2. Review the code comments for implementation details
3. Test with the provided sample data
4. Ensure all prerequisites are properly installed

---

**Note**: This project is designed for educational purposes and demonstrates fundamental web development concepts. For production use, additional security measures and optimizations should be implemented.

