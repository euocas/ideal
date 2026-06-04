# .github/copilot-instructions.md

## IDEAL Project - Copilot Instructions

### Quick Reference

- **Language**: Portuguese (entity names, variable names, method names)
- **Database**: MySQL `empreiteira` on localhost:3306
- **Entry point**: `public/index.php`
- **Auth check**: Always call `Auth::verificar()` in protected controller methods
- **Naming**: Models are singular (Usuario, Obra); controllers are plural (UsuariosController, ObrasController)

### Before Making Changes

1. **Database Schema**: Check `script_bd/` for current schema; understand which tables and columns exist
2. **Existing Models**: Review similar entity models to match naming and method patterns
3. **Security**: Always use prepared statements, escape output with `htmlspecialchars()`, and regenerate sessions on login
4. **Session**: Check `session_status()` before starting or using sessions

### Common Pitfalls

- **Hardcoded URLs**: Use `?url=route` in public/index.php; avoid hardcoding paths
- **Missing Auth Check**: Controllers must call `Auth::verificar()` before accessing protected data
- **SQL Injection**: Always use prepared statements with named parameters (`:name`)
- **Cross-Site Scripting (XSS)**: Always escape output in views with `htmlspecialchars()`
- **Missing Namespaces**: All classes must be in `App\*` namespace with correct casing

### Recommended File Organization

When adding a new feature:

```
New Entity: "Equipamento"

app/Models/Equipamento.php          # Database model
app/Controllers/EquipamentosController.php  # HTTP handler
app/Views/equipamentos/index.php    # List view
app/Views/equipamentos/form.php     # Add/edit form (optional)
public/assets/css/equipamentos.css  # Styling (optional)
```

### Code Standards

**Models**:
- Constructor calls `Conexao` to get PDO connection
- Methods are lowercase in Portuguese (buscar, listar, criar, atualizar, deletar)
- Return `PDO::FETCH_ASSOC` for queries
- Use named parameters in SQL (`:id`, `:nome`, etc.)

**Controllers**:
- Public methods correspond to routes (index, show, store, update, destroy)
- Call `Auth::verificar()` as first line (except AuthController)
- Instantiate models and extract data before require_once
- Redirect on errors with query parameters (e.g., `?erro=campos`)

**Views**:
- Include header and sidebar templates
- Use `htmlspecialchars()` for all dynamic content
- Use PHP `foreach` for iteration
- Avoid inline logic beyond conditionals and loops

### Testing Database Locally

1. Open phpMyAdmin (XAMPP Control Panel)
2. Import SQL script from `script_bd/` into `empreiteira` database
3. Verify tables and schema
4. Test login with admin/test credentials (check database for actual user records)

### Adding a New Route

1. Create/update controller with appropriate method
2. Add case in `public/index.php` switch statement:
   ```php
   case 'equipamentos':
       $controller = new EquipamentosController();
       $controller->index();
       break;
   ```
3. Create/update views in `app/Views/equipamentos/`
4. Link in sidebar or header navigation

### Debugging Tips

- **Session issues**: Check `session_status()` and call `session_start()` if needed
- **Database errors**: Check Conexao connection string and database credentials in `app/Config/Conexao.php`
- **404 errors**: Verify route exists in `public/index.php` switch statement
- **Auth redirects**: Check that `Auth::verificar()` is called and `$_SESSION['usuario']` is set
- **SQL errors**: Use `$stmt->execute()` error handling; PDOException will catch SQL syntax errors

