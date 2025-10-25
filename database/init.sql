DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS roles;

-- Create books table
CREATE TABLE IF NOT EXISTS books (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    isbn VARCHAR(20) UNIQUE,
    pages INT,
    available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create roles table
CREATE TABLE IF NOT EXISTS roles (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name VARCHAR(50) NOT NULL UNIQUE,
    permissions JSON NOT NULL DEFAULT (JSON_ARRAY()),
    description TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role_id CHAR(36),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

select  users.id, users.name, users.role_id, roles.name as role_name, roles.permissions
from users
left join roles on users.role_id = roles.id;


-- Insert sample books
INSERT INTO books (id, title, author, isbn, pages, available) VALUES
(UUID(), 'Design Patterns', 'Erich Gamma, Richard Helm, Ralph Johnson, John Vlissides', '9780201633610', 395, FALSE),
(UUID(), 'Refactoring Code', 'Martin Fowler', '9780201485677', 448, TRUE),
(UUID(), 'You Don\'t Know JS', 'Kyle Simpson', '9781491904244', 278, FALSE),
(UUID(), 'Introduction to Algorithms', 'Thomas H. Cormen, Charles E. Leiserson, Ronald L. Rivest, Clifford Stein', '9780262033848', 1312, TRUE),
(UUID(), 'Cracking the Coding Interview', 'Gayle Laakmann McDowell', '9780984782857', 687, TRUE),
(UUID(), 'Code Complete', 'Steve McConnell', '9780735619678', 960, FALSE),
(UUID(), 'The Art of Computer Programming', 'Donald E. Knuth', '9780201896830', 3168, TRUE),
(UUID(), 'Head First Design Patterns', 'Eric Freeman, Bert Bates, Kathy Sierra, Elisabeth Robson', '9780596007126', 694, TRUE);

-- Insert roles
SET @admin_role_id = UUID();
SET @user_role_id = UUID();
SET @manager_role_id = UUID();


INSERT INTO roles (id, name, permissions, description) VALUES
(@admin_role_id, 'admin', JSON_ARRAY('user:read', 'user:create', 'user:update', 'user:delete', 'book:read', 'book:create', 'book:update', 'book:delete', 'role:read', 'role:create', 'role:update', 'role:delete','role:assign'), 'Administrator with full access'),
(@user_role_id, 'user', JSON_ARRAY('book:read', 'self:read', 'self:update', 'self:delete'), 'Regular user with limited access'),
(@manager_role_id, 'manager', JSON_ARRAY('user:read', 'user:update', 'book:read', 'book:update', 'book:delete','book:create'), 'Manager with limited user access, full book access');

-- Insert sample users
-- user: alice@example.com, password: password
INSERT INTO users (id, name, email, password, role_id) VALUES
(UUID(), 'Alice Johnson', 'alice@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', @admin_role_id);