# Quick Setup Guide for Pet Store E-commerce

This guide will help you set up the Pet Store E-commerce platform quickly for development or testing purposes.

## Prerequisites

- PHP 8.1 or higher
- MySQL 8.0 or higher
- Git (optional, for cloning)

## Quick Setup (5 minutes)

### Step 1: Install Required Software

**Ubuntu/Debian:**
```bash
sudo apt update
sudo apt install -y php php-mysql php-cli mysql-server
sudo service mysql start
```

**Windows (using XAMPP):**
1. Download and install XAMPP from https://www.apachefriends.org/
2. Start Apache and MySQL services from XAMPP Control Panel

**macOS (using Homebrew):**
```bash
brew install php mysql
brew services start mysql
```

### Step 2: Setup Database

```bash
# Access MySQL (use 'root' with no password for fresh installations)
sudo mysql

# Create database and user
CREATE DATABASE pet_store;
CREATE USER 'petstore_user'@'localhost' IDENTIFIED BY 'password123';
GRANT ALL PRIVILEGES ON pet_store.* TO 'petstore_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 3: Get the Project Files

**Option A: Download ZIP**
1. Download the project ZIP file
2. Extract to your desired location
3. Navigate to the project directory

**Option B: Clone Repository**
```bash
git clone <repository-url>
cd pet_store_ecommerce
```

### Step 4: Import Database

```bash
# Import database structure and sample data
mysql -u petstore_user -p pet_store < database/schema.sql
mysql -u petstore_user -p pet_store < database/sample_data.sql
# Enter password: password123
```

### Step 5: Start the Application

```bash
# Navigate to the public directory
cd public

# Start PHP development server
php -S localhost:8000
```

### Step 6: Access the Website

1. Open your web browser
2. Go to: `http://localhost:8000`
3. Test login with: username `admin`, password `password123`

## Troubleshooting

### Common Issues

**1. "php: command not found"**
- Solution: Install PHP using your system's package manager

**2. "Connection failed" error**
- Solution: Check MySQL service is running and credentials are correct

**3. "Permission denied" errors**
- Solution: Ensure proper file permissions (755 for directories, 644 for files)

**4. Images not displaying**
- Solution: Check that image files exist in the `images/` directory

### Verification Steps

1. **Database Connection**: Check if products appear on the homepage
2. **User Authentication**: Try logging in with admin/password123
3. **Cart Functionality**: Add items to cart and verify they appear
4. **Responsive Design**: Test on different screen sizes

## Default Test Account

- **Username**: admin
- **Password**: password123

## File Structure Overview

```
pet_store_ecommerce/
├── public/           # Main website files
├── includes/         # PHP backend functions
├── api/             # AJAX endpoints
├── css/             # Stylesheets
├── js/              # JavaScript files
├── images/          # Product images
└── database/        # SQL files
```

## Next Steps

1. **Customize Products**: Add your own products through the database
2. **Modify Styling**: Edit `css/style.css` to change the appearance
3. **Add Features**: Extend functionality as needed for your project
4. **Deploy**: Follow the deployment guide in README.md for production

## Getting Help

- Check the main README.md for detailed documentation
- Review code comments for implementation details
- Ensure all prerequisites are properly installed
- Test with provided sample data first

---

**Quick Start Complete!** Your Pet Store E-commerce platform should now be running at `http://localhost:8000`

