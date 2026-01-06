# 🐛 BugTracker

A simple and modern bug tracking web application for development teams.

## 📋 Project Description

BugTracker is a web application that allows development teams to track, manage and share bugs reported by users or the QA department. Built with PHP and MySQL.

## 🎓 Academic Project

- **School**: L'École Multimédia
- **Program**: CDA 2nd Year
- **Year**: 2025

## 🚀 Features

- User authentication (signup/login)
- Create, edit and delete bug tickets
- Categorize bugs (Front-end, Back-end, Infrastructure)
- Set priority levels (Low, Standard, High)
- Track ticket status (Open, In Progress, Closed)
- Assign tickets to team members
- Filter tickets by category and assignment
- Dashboard with statistics

## 🛠️ Technologies

- **Backend**: PHP 8.x
- **Database**: MySQL 
- **Frontend**: HTML5, CSS3, JavaScript
- **Font**: Space Grotesk
- **Version Control**: Git & GitHub

## 📦 Installation

1. Clone the repository
```bash
git clone https://github.com/your-username/bugtracker.git
cd bugtracker
```

2. Import the database
```bash
mysql -u root -p bugtracker < database.sql
```

3. Configure database connection
- Edit `config/database.php` with your credentials

4. Start your local server
```bash
php -S localhost:8000
```

5. Open your browser
```
http://localhost:8000
```

## 🔐 Default Login

- **Email**: admin@bugtracker.com
- **Password**: 123456

## 📁 Project Structure

```
bugtracker/
├── config/          # Configuration files
├── includes/        # Reusable components
├── css/            # Stylesheets
├── js/             # JavaScript files
├── pages/          # Application pages
├── actions/        # Form processing
└── index.php       # Entry point
```

## 🎨 Design

- **Colors**: #333333, #48e5c2, #fcfaf9
- **Typography**: Space Grotesk
- **Responsive**: Mobile, Tablet, Desktop

## 📝 License

Academic project - L'École Multimédia 2025

## 👤 Author

Isabella Digilio - CDA 2nd Year Student
