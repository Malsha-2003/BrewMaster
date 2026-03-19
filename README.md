# ☕ BrewMaster – Digital Coffee Recipe Book

**Course:** COM 2303 – Web Design  
**Registration Number:** ASB/2023/144  
**Phase:** 3 – PHP and Database Integration  
**University:** Rajarata University of Sri Lanka, Department of Computing

---

## 📋 About the Project

BrewMaster is an interactive web application for exploring, filtering, and sharing coffee recipes. Users can register, log in, and manage their own recipes across four categories: **Hot, Cold, Sweet, and Strong**.

---

## ✨ Features Implemented

| Feature | Description |
|---|---|
| Dynamic Content Updates | Filter recipes by category (Hot / Cold / Sweet / Strong) |
| Interactive Image Slider | Bootstrap carousel on the home page hero section |
| Form Validation | JS + PHP validation on all forms |
| Smooth Scrolling | JS smooth scroll on all anchor links |
| Event Handling | Modal popups for recipe details, hover effects on cards |
| User Authentication | Register, Login, Logout with session handling |
| Database Integration | MySQL with prepared statements |
| Contact Form | Saves messages to database |

---

## 🗂️ Folder Structure

```
brewmaster/
├── css/
│   └── style.css            # Custom styles + Bootstrap overrides
├── js/
│   └── main.js              # Validation, filtering, modals, animations
├── images/                  # (for future image uploads)
├── includes/
│   ├── db.php               # Database connection
│   ├── functions.php        # Helper functions (sanitize, flash, etc.)
│   └── navbar.php           # Shared navigation bar
├── auth/
│   ├── register.php         # User registration
│   ├── login.php            # User login
│   └── logout.php           # Session destroy & logout
├── index.php                # Home page (hero + carousel + featured recipes)
├── recipes.php              # All recipes with filter buttons
├── contact.php              # Contact form (saved to DB)
├── dashboard.php            # User dashboard (add/delete recipes)
├── database.sql             # MySQL database dump
└── README.md
```

---

## ⚙️ Setup Instructions

### Requirements
- XAMPP or WAMP (Apache + PHP 7.4+ + MySQL)
- Web browser (Chrome, Firefox, Edge)

### Step 1 – Start Services
Open XAMPP/WAMP Control Panel and start **Apache** and **MySQL**.

### Step 2 – Copy Project Files
Place the `brewmaster/` folder inside your web server root:
- **XAMPP:** `C:/xampp/htdocs/brewmaster/`
- **WAMP:**  `C:/wamp64/www/brewmaster/`

### Step 3 – Import the Database
1. Open **phpMyAdmin**: http://localhost/phpmyadmin
2. Click **New** → create a database named `brewmaster`
3. Select the `brewmaster` database
4. Click the **Import** tab → choose `database.sql` → click **Go**

### Step 4 – Configure Database (if needed)
Open `includes/db.php` and update if your MySQL has a password:

```php
define('DB_USER', 'root');   // your MySQL username
define('DB_PASS', '');       // your MySQL password (blank for XAMPP default)
```

### Step 5 – Open in Browser
```
http://localhost/brewmaster/
```

---

## 🔐 Sample Login
A sample admin account is included in `database.sql`:
- **Email:** admin@brewmaster.com  
- **Password:** password

Or register a new account via the **Register** page.

---

## 🛡️ Security Notes
- Passwords stored with `password_hash()` (bcrypt)
- All user inputs sanitized with `htmlspecialchars()` / `stripslashes()`
- Database queries use **prepared statements** (prevents SQL injection)
- Sessions properly destroyed on logout

---

## 🧪 Technologies Used
- PHP 7.4+
- MySQL / phpMyAdmin
- HTML5 / CSS3
- Bootstrap 5.3
- JavaScript (ES6)
- GitHub (for hosting/submission)
