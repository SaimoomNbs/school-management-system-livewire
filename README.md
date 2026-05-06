# Academy Management System

A comprehensive web application built with Laravel for managing educational institutions. This system provides tools for student enrollment, teacher management, exam scheduling, fee collection, and administrative operations.

## 🚀 Features

- **Student Management**: Complete student profiles, enrollment, and tracking
- **Teacher Management**: Staff profiles, assignments, and scheduling
- **Class & Section Management**: Organize students into classes and sections
- **Exam System**: Create exams, schedule tests, and manage results
- **Fee Management**: Track fees, payments, and generate invoices
- **Notification System**: Automated notifications for important events
- **Gallery & Events**: Manage school galleries and event calendars
- **User Authentication**: Secure login system with role-based access
- **Responsive Design**: Mobile-friendly interface using Tailwind CSS

## 🛠 Tech Stack

- **Backend**: Laravel 11.x, PHP 8.2+
- **Frontend**: Livewire, Tailwind CSS, Alpine.js
- **Database**: MySQL
- **Build Tool**: Vite
- **Testing**: PHPUnit
- **Other**: Composer, NPM

## 📋 Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL 8.0 or higher
- Git

## 🔧 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd academy
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   ```
   Configure your database and other settings in `.env` file.

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Run Database Migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the Database (Optional)**
   ```bash
   php artisan db:seed
   ```

8. **Build Assets**
   ```bash
   npm run build
   # or for development
   npm run dev
   ```

## 🚀 Usage

1. **Start the Development Server**
   ```bash
   php artisan serve
   ```
   Access the application at `http://localhost:8000`

2. **For Production**
   Configure your web server (Apache/Nginx) to serve the `public` directory.

## 📁 Project Structure

```
academy/
├── app/                    # Application logic
│   ├── Http/Controllers/   # HTTP controllers
│   ├── Livewire/          # Livewire components
│   ├── Models/            # Eloquent models
│   └── Services/          # Business logic services
├── database/              # Database migrations & seeders
├── public/                # Public assets
├── resources/             # Views, CSS, JS
│   ├── css/
│   ├── js/
│   └── views/
├── routes/                # Route definitions
├── storage/               # File storage
├── tests/                 # Test files
└── config/                # Configuration files
```

## 🔐 Environment Variables

Create a `.env` file in the root directory with the following variables:

```env
APP_NAME="Academy Management System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=academy_db
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 Support

If you have any questions or need help, please open an issue on GitHub.
