# MealKit

MealKit is a PHP subscription-based recipe and meal planning web application built for XAMPP/PHP environments.

## Overview

- Front controller architecture using `index.php` and a custom router
- MVC-like organization with `controllers/`, `models/`, and `views/`
- User roles for admin and customer management
- Recipe, meal plan, category, ingredient, subscription, payment, and notification support
- Mobile money sandbox integration for Tanzanian providers (M-Pesa, Tigo Pesa, Airtel Money, Halotel)

## Features

### For Customers
- Browse and search recipes with filters
- Create and manage weekly meal plans
- Save favorite recipes
- Subscribe to premium plans (Monthly/Yearly)
- Download recipes as PDF
- Track nutritional information
- Serving size calculator
- In-app notifications

### For Admins
- Manage recipes, categories, and ingredients
- Create and edit subscription plans
- View payment history and analytics
- Manage user accounts and subscriptions
- Generate reports
- Notification management system

## Requirements

- PHP 8.0 or newer
- MySQL / MariaDB (default port: 3307)
- XAMPP or another Apache/PHP stack
- PHP extensions: `pdo`, `pdo_mysql`, `json`, `mbstring`, `fileinfo`, `openssl`

## Installation

1. **Copy the project into your web root**
   - Example: `C:\xampp\htdocs\mealkit`

2. **Ensure the following directories are writable**
   - `uploads/recipes`
   - `uploads/profiles`
   - `uploads/pdfs`

3. **Configure the application**
   - Copy `config/config.example.php` to `config/config.php`
   - Update `config/config.php` with your settings:
     - `APP_URL` - Your application URL
     - Database credentials: `DB_HOST`, `DB_PORT` (default: 3307), `DB_NAME`, `DB_USER`, `DB_PASS`
     - Optional mobile money sandbox settings

4. **Import the database schema**
   - Open `http://localhost/phpmyadmin`
   - Create a database named `mealkit_db` or your chosen name
   - Import `database/mealkit.sql`

5. **Seed demo data (optional)**
   - Visit `http://localhost/mealkit/database/seed.php`

6. **Run the setup check**
   - Visit `http://localhost/mealkit/setup_check.php`
   - Confirm all checks pass

7. **Delete `setup_check.php` before deploying to production**

## Folder Structure

```
mealkit/
├── assets/              # CSS, JavaScript, images, fonts
├── config/              # Application settings and database bootstrap
├── controllers/         # Request handlers for pages and actions
├── database/            # SQL schema and seed scripts
├── includes/            # Reusable helpers, session and security classes
├── models/              # Database interaction and data access logic
├── uploads/             # User-uploaded content directories
├── views/               # HTML templates for home, auth, admin, and customer pages
├── index.php            # Front controller
├── router.php           # URL routing logic
└── README.md            # This file
```

## Routing

All requests are routed through `index.php` via query string parameter `url`.

**URL pattern:** `?url=controller/action/param1/param2`

**Example routes:**
- `/` or `?url=` → `HomeController@index`
- `/auth/login` or `?url=auth/login` → `AuthController@login`
- `/recipes/view/42` or `?url=recipes/view/42` → `RecipeController@viewRecipe`

## Default Credentials

### Admin Account
- Email: `admin@mealkit.com`
- Password: `admin123`

### Customer Account (from seed data)
- Email: `customer@example.com`
- Password: `password123`

## Subscription Plans

- **Free** - Access to public recipes and basic meal planning
- **Monthly** (10,000 TZS) - Unlimited recipes, PDF downloads, 5 meal plans/month
- **Yearly** (100,000 TZS) - Everything in Monthly plus premium recipes, unlimited meal plans, priority support

## Payment Methods

- Mobile Money (Tanzania):
  - M-Pesa
  - Tigo Pesa
  - Airtel Money
  - Halotel Money

## Important Notes

- `APP_ENV` is set to `development` by default in `config/config.php`
- Before production, update `APP_URL`, database credentials, `MOMO_*` settings, and remove `setup_check.php`
- Keep user-uploaded directories protected and writable
- The application uses port 3307 for MySQL by default (configurable in `config/config.php`)

## Security

- CSRF protection on all forms
- Password hashing using bcrypt
- SQL injection prevention via PDO prepared statements
- Session management with secure defaults
- Input sanitization and validation

## Troubleshooting

### Database Connection Failed
- Ensure MySQL/MariaDB is running in XAMPP
- Check that the port in `config/config.php` matches your MySQL port (default: 3307)
- Verify database credentials are correct
- Confirm the database exists and has been imported

### File Upload Issues
- Ensure upload directories are writable
- Check PHP `upload_max_filesize` and `post_max_size` settings
- Verify file types match allowed MIME types in config

### Mobile Money Sandbox
- Sandbox mode is enabled by default for testing
- Update `MOMO_*` constants in config for production
- Test transactions have a configurable success rate (default: 85%)

## Development

### Adding New Features
1. Create controller in `controllers/`
2. Create model in `models/` (if database interaction needed)
3. Create view in `views/` (appropriate subfolder)
4. Add route to `router.php` if needed
5. Update navigation in relevant header files

### Code Style
- Follow PSR-12 coding standards
- Use strict types (`declare(strict_types=1)`)
- Add PHPDoc comments for all public methods
- Use prepared statements for all database queries

## License

This project is proprietary software. All rights reserved.

## Support

For support or changes, modify the PHP files in `controllers/`, `models/`, and `views/`.

## Version History

- **v1.0.0** - Initial release with core features
  - Recipe management
  - Meal planning
  - Subscription system
  - Mobile money integration
  - User authentication and authorization
