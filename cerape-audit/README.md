# CERAPE Audit Module

## Overview

The CERAPE Audit Module is designed to provide a comprehensive auditing system for the CERAPE application. This module captures and logs various events related to user actions and system changes, ensuring accountability and traceability within the application.

## Features

- **Audit Logging**: Automatically logs create, update, delete, and restore actions for models using the Auditable trait.
- **User Context**: Captures user information, including IP address, user agent, and session details for each logged event.
- **Filament Integration**: Seamlessly integrates with the Filament admin panel, providing a user-friendly interface for viewing audit logs.
- **Search and Filter**: Allows users to search and filter audit logs based on various criteria such as user, event type, and date range.
- **Export Options**: Supports exporting audit logs in CSV, Excel, and PDF formats.

## Installation

1. Clone the repository:
   ```
   git clone <repository-url>
   ```

2. Navigate to the project directory:
   ```
   cd cerape-audit
   ```

3. Install dependencies:
   ```
   composer install
   ```

4. Run migrations to create the necessary database tables:
   ```
   php artisan migrate
   ```

5. Set up your environment variables in the `.env` file.

## Usage

- Access the audit logs through the Filament admin panel under the "Segurança" menu.
- Use the provided filters to narrow down the logs based on specific criteria.
- View detailed information about each audit log entry, including old and new values for modified fields.

## Contributing

Contributions are welcome! Please follow the standard Git workflow for submitting changes.

## License

This project is licensed under the MIT License. See the LICENSE file for more details.