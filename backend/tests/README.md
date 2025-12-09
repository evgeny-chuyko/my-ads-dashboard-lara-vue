# Testing Guide

## Test Structure

```
tests/
├── Feature/                    # E2E API tests
│   ├── Auth/                  # Authentication
│   │   ├── RegisterTest.php
│   │   ├── LoginTest.php
│   │   ├── LogoutTest.php
│   │   └── MeTest.php
│   ├── Publisher/             # Publisher endpoints
│   │   ├── AppIndexTest.php
│   │   ├── AppCreateTest.php
│   │   ├── AppShowTest.php
│   │   ├── AppUpdateTest.php
│   │   ├── AppDeleteTest.php
│   │   └── AppStatsTest.php
│   └── Admin/                 # Admin endpoints
│       ├── UserManagementTest.php
│       ├── AppManagementTest.php
│       ├── StatsTest.php
│       └── AuditLogTest.php
├── Traits/                    # Helper traits
│   └── TestHelpers.php
└── Unit/                      # Unit tests
```

## Running Tests

### All tests
```bash
php artisan test
```

### Specific group
```bash
# Auth tests
php artisan test --testsuite=Feature --filter Auth

# Publisher tests
php artisan test --testsuite=Feature --filter Publisher

# Admin tests
php artisan test --testsuite=Feature --filter Admin
```

### Specific file
```bash
php artisan test tests/Feature/Publisher/AppCreateTest.php
```

### Specific test
```bash
php artisan test --filter test_user_can_create_app
```

### With coverage
```bash
php artisan test --coverage
```

### With verbose output
```bash
php artisan test --verbose
```

## Test Preparation

### 1. Ensure database is configured
Tests use SQLite in-memory database (configured in `phpunit.xml`):
```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

### 2. Run migrations (if needed)
```bash
php artisan migrate --env=testing
```

### 3. Create roles in database
Ensure your database has roles with IDs:
- `1` - Admin
- `2` - Publisher

Can be added via seeder or migration.

## Test Coverage

### ✅ Auth (4 files, ~30 tests)
- Registration with validation
- Login/Logout
- Get user data
- Token verification

### ✅ Apps CRUD (6 files, ~60 tests)
- Create, read, update, delete
- Field validation
- Access control checks
- App statistics

### ✅ Admin (4 files, ~25 tests)
- User management (ban/unban)
- View all apps
- System statistics
- Audit logs

### ✅ Permissions
- Verify regular users cannot access admin endpoints
- Verify users cannot edit other users' apps
- Verify banned users cannot use API

## Helper Methods (TestHelpers trait)

```php
// Create users
$user = $this->createUser();
$admin = $this->createAdmin();
$publisher = $this->createPublisher();
$bannedUser = $this->createBannedUser();

// Create apps
$app = $this->createApp($user);
$app = $this->createApp($user, ['name' => 'Custom Name']);

// Authentication
$this->loginAs($user);

// Response assertions
$this->assertUnauthorized($response);
$this->assertForbidden($response);
$this->assertNotFound($response);
$this->assertJsonValidationErrors($response, ['field']);
```

## Factories

### UserFactory
```php
User::factory()->create();
User::factory()->admin()->create();
User::factory()->publisher()->create();
User::factory()->banned()->create();
```

### AppFactory
```php
App::factory()->create();
App::factory()->forUser($user)->create();
App::factory()->active()->create();
App::factory()->paused()->create();
App::factory()->archived()->create();
```

## Examples

### App creation test
```php
public function test_user_can_create_app(): void
{
    $user = $this->createUser();

    $response = $this->loginAs($user)
        ->postJson('/api/apps', [
            'name' => 'My App',
            'description' => 'Description',
        ]);

    $response->assertStatus(201)
        ->assertJsonFragment(['name' => 'My App']);

    $this->assertDatabaseHas('apps', [
        'name' => 'My App',
        'user_id' => $user->id,
    ]);
}
```

### Permission check test
```php
public function test_user_cannot_update_another_users_app(): void
{
    $user1 = $this->createUser();
    $user2 = $this->createUser();
    $app = $this->createApp($user2);

    $response = $this->loginAs($user1)
        ->putJson("/api/apps/{$app->id}", [
            'name' => 'Hacked',
        ]);

    $this->assertForbidden($response);
}
```

## CI/CD Integration

### GitHub Actions
```yaml
- name: Run tests
  run: php artisan test --coverage --min=80
```

### GitLab CI
```yaml
test:
  script:
    - php artisan test --coverage
```

## Troubleshooting

### Error "Table not found"
```bash
php artisan migrate:fresh --env=testing
```

### Error "Class not found"
```bash
composer dump-autoload
```

### Slow tests
Use `ParallelTesting`:
```bash
php artisan test --parallel
```
