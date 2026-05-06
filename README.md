# School-Management System Livewire

A comprehensive web application built with Laravel for managing educational institutions. This system provides tools for student enrollment, teacher management, exam scheduling, fee collection, and administrative operations.

![Dashboard](https://img.lightshot.app/7K1QlWG4TAapkQwZxogPcg.png)

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

- **Backend**: Laravel 13.x, PHP 8.3+
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

6. **Seed the Database (Optional)**
   ```bash
   Contact us for database file
   ```

7. **Build Assets**
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


## 📞 Contact

For support, collaboration, or project inquiries:

- 📧 Email: [saidmohammad565@gmail.com](mailto:saidmohammad565@gmail.com)
- 💬 WhatsApp: [Chat on WhatsApp](https://wa.me/+8801755339757)
