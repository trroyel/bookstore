# 📚 PHP Bookstore Application

A modern PHP MVC web application for managing books and users with a clean architecture, security features, and responsive design.

## 🚀 Quick Start

### With Docker (Recommended)

```bash
# Clone the repository
git clone <repository-url>
cd Bookstore

# Start the application
docker-compose up -d

# Access the application
open http://localhost:8080
```

### Without Docker

**Requirements:**
- PHP 8.0+
- Web server (Apache/Nginx)

**Setup:**
```bash
# Clone the repository
git clone <repository-url>
cd Bookstore/src

# Configure web server document root to /src/public
# For PHP built-in server (development only):
cd src/public
php -S localhost:8080

# Access the application
open http://localhost:8080
```

## 📁 Project Structure

```
Bookstore/
├── nginx/                  # default.conf
├── php/                    # Dockerfile
├── src/
│   ├── app/
│   │   ├── Controllers/    # Request handlers
│   │   ├── Core/           # Framework components, DI
│   │   ├── Services/       # Business logic
│   │   └── Views/          # HTML templates
│   ├── public/             # Web root
│   └── storage/            # JSON data files
├── docker-compose.yml      # Docker configuration
└── README.md
```

## 🛠 Technologies

- **Backend:** PHP 8, Custom MVC Framework
- **Frontend:** Bootstrap 5, Vanilla JavaScript
- **Data:** JSON file storage
- **Security:** CSRF protection, XSS prevention
- **Deployment:** Docker, Nginx, PHP-FPM

## ✨ Features

### Public Features
- 🏠 **Homepage** - Book showcase and landing page
- 🔐 **Authentication** - Login/signup system
- 📱 **Responsive Design** - Mobile-friendly interface

### Admin Features
- 📊 **Dashboard** - Statistics and overview
- 📚 **Book Management** - CRUD operations
- 👥 **User Management** - User administration
- 🔍 **Search** - Books and users search
- 🔒 **Security** - Protected admin routes

### API Features
- 📡 **REST API** - JSON endpoints for books
- 🔗 **API Routes:**
  - `GET /api/books` - List all books
  - `GET /api/books/{id}` - Get book by ID
  - You are welcome to contribute this project.

## 🔧 Architecture

### MVC Pattern
- **Models:** Data structures (Book, User)
- **Views:** HTML templates with PHP
- **Controllers:** Request handling and business logic

### Core Components
- **Router:** URL routing with middleware support
- **Container:** Dependency injection container
- **Request:** HTTP request abstraction
- **Services:** Business logic layer
- **Container:** Inject all dependencies

### Security Features
- ✅ CSRF token protection
- ✅ XSS prevention (output escaping)
- ✅ Path traversal protection
- ✅ Open redirect prevention
- ✅ Session-based authentication

## 🎯 Default Credentials

**Admin User:**
- Email: `alice@example.com`
- Password: `password`

**Regular User:**
- Email: `bob@example.com`
- Password: `password`

## 🔄 Development

### Adding New Features
1. Create controller in `app/Controllers/`
2. Add routes in `public/index.php`
3. Create views in `app/Views/`
4. Update container in `app/Core/Container.php` if needed

### File Permissions
```bash
# Ensure storage directory is writable
chmod 755 src/storage/
chmod 644 src/storage/*.json
```

## 📝 API Usage

```bash
# Get all books
curl http://localhost:8080/api/books

# Get specific book
curl http://localhost:8080/api/books/1
```

## 🐳 Docker Details

**Services:**
- `app` - PHP-FPM application server
- `web` - Nginx web server

**Ports:**
- `8080` - Web application

**Volumes:**
- `./src` - Application source code

## 🤝 Contributing

1. Fork the repository
2. Create feature branch
3. Make changes
4. Test thoroughly
5. Submit pull request

## 📄 License

This project is open source and available under the MIT License.