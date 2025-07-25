# Library Management System

A microservices-based library management system built with PHP 8.4 and Symfony 6.4. This project provides functionality for managing books in a library.

## Project Overview

The Library Management System is structured as a set of microservices, with the main "Book" microservice handling book-related operations. The project follows Domain-Driven Design (DDD) principles with a clean architecture approach, separating the codebase into:

- **Domain Layer**: Core business logic, entities, and business rules
- **Application Layer**: Use cases and application services
- **Infrastructure Layer**: Technical implementations and external integrations

## Requirements

### System Requirements

- Docker and Docker Compose
- Git

### PHP Requirements

- PHP 8.4
- PHP Extensions:
  - intl
  - pdo
  - pdo_mysql
  - zip
  - opcache
  - redis
  - ctype
  - iconv

### Framework and Libraries

- Symfony 6.4
- Composer (for dependency management)
- PHPUnit 12.2+ (for unit testing)
- Behat 3.13+ (for behavior testing)

## Installation

### Using Docker (Recommended)

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd Library
   ```

2. Start the Docker containers:
   ```bash
   docker-compose up -d
   ```

3. Install dependencies for all microservices:
   ```bash
   docker exec php bash -c "./install-all.sh"
   ```
   
   Alternatively, you can run the script directly if you have bash installed:
   ```bash
   ./install-all.sh
   ```

4. Access the application:
   - Web interface: http://localhost:8080

### Manual Installation (Without Docker)

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd Library
   ```

2. Install PHP 8.4 and required extensions

3. Install dependencies for all microservices:
   ```bash
   ./install-all.sh
   ```

4. Configure your web server (Nginx or Apache) to point to the `ms/Book/public` directory

## Project Structure

```
Library/
├── docker/                  # Docker configuration files
├── ms/                      # Microservices
│   └── Book/                # Book microservice
│       ├── bin/             # Symfony console commands
│       ├── config/          # Symfony configuration
│       ├── public/          # Public web directory
│       ├── src/             # Source code
│       └── vendor/          # Dependencies
├── src/                     # Shared source code
│   ├── Book/                # Book domain
│   │   ├── Application/     # Application services
│   │   ├── Domain/          # Domain entities and logic
│   │   └── Infrastructure/  # Infrastructure implementations
│   └── Shared/              # Shared code across domains
├── tests/                   # Test files
│   ├── Book/                # Book domain tests
│   └── Shared/              # Shared code tests
├── docker-compose.yml       # Docker Compose configuration
└── install-all.sh           # Installation script
```

## Running Tests

### PHPUnit (Unit Tests)

To run unit tests for the Book microservice:

```bash
docker exec php bash -c "cd ms/Book && php bin/phpunit"
```

Or without Docker:

```bash
cd ms/Book
php bin/phpunit
```

### Behat (Behavior Tests)

To run behavior tests for the Book microservice:

```bash
docker exec php bash -c "cd ms/Book && vendor/bin/behat"
```

Or without Docker:

```bash
cd ms/Book
vendor/bin/behat
```

## Services

The application consists of the following services:

- **PHP (8.4)**: Application server running PHP-FPM
- **Nginx**: Web server exposing the application on port 8080
- **Redis**: Cache server for improved performance

## Development

### Code Style

This project follows PSR-2 coding standards. To ensure your code adheres to these standards, please review the code style guidelines before contributing.

### Adding New Features

When adding new features, please follow the existing architecture and patterns:

1. Define domain entities and interfaces in the Domain layer
2. Implement use cases in the Application layer
3. Provide concrete implementations in the Infrastructure layer
4. Add appropriate tests for all new functionality

## Troubleshooting

### Common Issues

- **Permission Issues**: If you encounter permission issues with Docker volumes, you may need to adjust permissions on the host:
  ```bash
  chmod -R 777 var/cache var/log
  ```

- **Composer Memory Limit**: If Composer runs out of memory during installation, increase the memory limit:
  ```bash
  COMPOSER_MEMORY_LIMIT=-1 docker exec php bash -c "cd ms/Book && composer install"
  ```

## License

This project is proprietary software. All rights reserved.