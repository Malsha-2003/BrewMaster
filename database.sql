-- BrewMaster – Digital Coffee Recipe Book
-- Course: COM 2303 – Web Design | Registration: ASB/2023/144
-- Phase 3: PHP and Database Integration

CREATE DATABASE IF NOT EXISTS brewmaster;
USE brewmaster;

-- ─── Users Table ───────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    email      VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ─── Recipes Table ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS recipes (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT          NOT NULL,
    title        VARCHAR(150) NOT NULL,
    category     ENUM('Hot','Cold','Sweet','Strong') NOT NULL DEFAULT 'Hot',
    ingredients  TEXT         NOT NULL,
    instructions TEXT         NOT NULL,
    brew_time    VARCHAR(30)  DEFAULT NULL,   -- e.g. "5 mins"
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ─── Messages Table (Contact Form) ─────────────────────────────────
CREATE TABLE IF NOT EXISTS messages (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    message    TEXT         NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ─── Sample Data ───────────────────────────────────────────────────
INSERT INTO users (username, email, password) VALUES
('admin', 'admin@brewmaster.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- sample password: password

INSERT INTO recipes (user_id, title, category, ingredients, instructions, brew_time) VALUES
(1, 'Classic Espresso',    'Hot',    'Fine ground coffee 18g, Water 30ml',                           '1. Preheat portafilter.\n2. Tamp coffee at 30lbs.\n3. Pull shot for 25-30 seconds.',              '30 sec'),
(1, 'Iced Caramel Latte',  'Cold',   'Espresso 2 shots, Milk 200ml, Ice, Caramel syrup 2 tbsp',      '1. Brew espresso.\n2. Fill glass with ice.\n3. Pour milk then espresso.\n4. Drizzle caramel.',   '5 mins'),
(1, 'Honey Sweet Mocha',   'Sweet',  'Espresso 1 shot, Dark chocolate 20g, Honey 1 tbsp, Milk 150ml','1. Melt chocolate in hot milk.\n2. Add honey.\n3. Pour espresso on top.\n4. Whisk well.',         '8 mins'),
(1, 'Double Shot Lungo',   'Strong', 'Coarse ground coffee 20g, Hot water 120ml',                    '1. Use lungo basket.\n2. Pull shot for 45-50 seconds.\n3. Serve immediately.',                    '50 sec');
