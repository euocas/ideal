# IDEAL Project - Agent Guide

## Project Overview

**IDEAL** is a custom PHP MVC web application for managing construction projects ("empreiteira"). It handles clients, projects, vehicles, staff, and financial operations.

- **Tech Stack**: PHP 7+, MySQL/PDO, HTML/CSS
- **Database**: MySQL (localhost:3306, database: `empreiteira`)
- **Entry Point**: `public/index.php`
- **Namespace**: `App\` (PSR-4 autoloading)

## Architecture

### Directory Structure

- **`app/Controllers/`** - HTTP handlers; instantiate models and render views
- **`app/Models/`** - Database models; each handles one entity (Usuario, Cliente, Obra, etc.)
- **`app/Views/`** - PHP templates organized by feature (auth/, clientes/, etc.)
- **`app/Core/`** - Shared utilities (Auth verification)
- **`app/Config/`** - Database connection (Conexao.php), session config
- **`public/`** - Web root; index.php routes requests
- **`routes/web.php`** - Route definitions (see Routing section)
- **`script_bd/`** - Database schema migrations (sequential SQL scripts)

### Routing System

`public/index.php` implements **switch-based routing** using the `?url=` query parameter:

```php
$url = $_GET['url'] ?? 'login';
switch ($url) {
    case 'login':
        $authController = new AuthController();
        $authController->index();
        break;
    // ... more routes
}
```

**New routes**: Add a case to the switch statement in `public/index.php`.

## Key Development Patterns

### Models

Each model represents one database entity and handles all database operations for that entity:

```php
namespace App\Models;
use App\Config\Conexao;

class Obra {
    private $conn;
    
    public function __construct() {
        $conexao = new Conexao();
        $this->conn = $conexao->getConnection();
    }
    
    public function buscarPorId($id) {
        $sql = "SELECT * FROM obra WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
```

**Pattern**: 
- Constructor initializes PDO connection
- Public methods are named in Portuguese (buscar*, criar, atualizar, deletar)
- Use prepared statements with named parameters
- Return associative arrays (PDO::FETCH_ASSOC)

### Controllers

Controllers instantiate models, handle business logic, and render views:

```php
namespace App\Controllers;

class ObrasController {
    public function index() {
        // Check auth
        Auth::verificar();
        
        // Load model
        $obraModel = new Obra();
        
        // Get data
        $obras = $obraModel->listar();
        
        // Render view
        require_once __DIR__ . '/../Views/obras/index.php';
    }
}
```

**Pattern**:
- Always call `Auth::verificar()` first (except AuthController)
- Instantiate models and call their methods
- Extract data into variables before require_once
- Pass data to views via PHP variables, not parameters

### Views

Views are plain PHP files with HTML and embedded PHP:

```php
<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="container">
    <h1>Obras</h1>
    <?php foreach ($obras as $obra): ?>
        <div class="obra-item">
            <h3><?php echo htmlspecialchars($obra['nome']); ?></h3>
            <p><?php echo htmlspecialchars($obra['descricao']); ?></p>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>
```

**Pattern**:
- Include header and sidebar via require_once
- Always escape output with `htmlspecialchars()`
- Use foreach for lists
- Keep business logic in controllers, not views

## Authentication & Session

**Auth System**:
- Session-based (PHP sessions)
- Models: `App\Models\Usuario`
- Controller: `App\Controllers\AuthController`
- Verification: `App\Core\Auth::verificar()` - checks `$_SESSION['usuario']`

**Key security practices**:
- Passwords hashed with `password_verify()`
- Session regeneration on login (`session_regenerate_id(true)`)
- Sensitive data (passwords) removed before session storage
- Cache headers set on auth check

**Implementation**:
- Login flow: POST email/password → verify → set session → redirect to dashboard
- Protected pages: Call `Auth::verificar()` at controller start
- Logout: Clear session and destroy

## Database

### Connection

Database connection is centralized in `App\Config\Conexao`:
- Host: `localhost`
- Port: `3306`
- Database: `empreiteira`
- User: `root`
- Password: (empty by default)

### Schema & Migrations

Database schema is defined in SQL scripts in `script_bd/`:
- `script_19_05_26_NOVO.sql` - Current schema (likely most recent)
- Apply migrations manually to MySQL for local dev

### Current Entities

From models, the database includes:
- **usuario** - System users
- **cliente** - Clients
- **empresa** - Companies
- **obra** - Construction projects
- **funcionario** - Staff members
- **veiculo** - Vehicles
- **financeiro_automovel** - Vehicle finances
- **financeiro_funcionario** - Staff finances
- **financeiro_obra** - Project finances
- **relatorio** - Reports

## Common Tasks

### Adding a New Entity (full CRUD)

1. **Create database table** - Add to SQL script in `script_bd/`
2. **Create Model** - File `app/Models/EntityName.php`
   - Methods: `listar()`, `buscarPorId($id)`, `criar($data)`, `atualizar($id, $data)`, `deletar($id)`
3. **Create Controller** - File `app/Controllers/EntityNameController.php`
   - Methods: `index()` (list), `show($id)`, `store()` (create), `update($id)` (edit), `destroy($id)` (delete)
   - Add `Auth::verificar()` at start of each method
4. **Create Views** - Directory `app/Views/entityname/`
   - `index.php` - List view
   - `form.php` - Add/edit form (optional)
5. **Add Routes** - Add cases to switch in `public/index.php`

### Adding Form Validation

Validation is done in controllers before calling model methods:

```php
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: index.php?url=login&erro=campos');
    exit;
}
```

**Pattern**: Validate, redirect with error code if invalid, proceed if valid.

### Accessing Session Data

```php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuario = $_SESSION['usuario']; // Returns array with user data
```

## Development Environment

### Setup

1. Place project in `C:\xampp\htdocs\ideal\`
2. Run XAMPP (Apache + MySQL)
3. Import database: `script_bd/script_19_05_26_NOVO.sql` into MySQL
4. Access at `http://localhost/ideal/public/index.php?url=login`

### Default Credentials

Check database after schema import for test user accounts.

### CSS/Assets

- CSS files: `public/assets/css/` (one per feature, e.g., `clientes.css`)
- Icons: `public/assets/icons/`
- Images: `public/assets/img/`
- Include in views via `<link rel="stylesheet" href="..." >`

## Important Notes

- **Namespacing**: All classes use `App\` namespace with PSR-4 autoloading
- **Language**: Code and database are in Portuguese (buscar, atualizar, criar, etc.)
- **Error Handling**: Try/catch in Conexao class; PDOException handling for DB errors
- **Routing**: Manual switch-based system; add cases directly in `public/index.php`
- **No Framework**: This is a custom MVC; no external framework like Laravel
- **Database Port**: Explicitly set to 3306 in Conexao class

## Files to Know

- `public/index.php` - Entry point and router
- `app/Config/Conexao.php` - Database connection configuration
- `app/Core/Auth.php` - Authentication verification
- `app/Views/includes/header.php` - Common header template
- `app/Views/includes/sidebar.php` - Navigation sidebar

---

Use this guide to understand the codebase structure, conventions, and patterns. Most tasks involve creating or modifying controllers, models, and views following the established patterns.
