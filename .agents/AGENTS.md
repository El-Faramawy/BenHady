# BenHady — Project Agent Rules

## Project Overview
BenHady is a Laravel API application using JWT authentication (`tymon/jwt-auth`). The application follows a layered Clean Architecture pattern: **Controller → Service → Repository → Model**.

---

## Folder Structure

```
BenHady/
├── .agents/              # Agent rules and guidelines (AGENTS.md)
├── app/
│   ├── Constants/        # Shared application constants and message keys
│   ├── Enums/            # PHP enums for domain states, types, and providers
│   ├── Exceptions/       # Custom domain exceptions & handler classes
│   ├── Http/
│   │   ├── Controllers/  # Thin HTTP controllers (DTO creation & service delegation)
│   │   ├── Middleware/   # HTTP and authentication middleware
│   │   └── Requests/     # FormRequest classes for strict input validation
│   ├── Models/           # Eloquent models ($fillable defined, mass-assignment protected)
│   ├── Providers/        # Service providers (e.g., RepositoryServiceProvider)
│   ├── Repositories/     # Database abstraction layer (all DB queries reside here)
│   ├── Responses/        # Standardized API response formatters
│   └── Services/         # Pure business logic services (organized by domain/feature)
├── bootstrap/            # Application bootstrapping & global exception configuration
├── config/               # Laravel environment and package configuration files
├── database/             # Migrations, seeders, and factories
├── resources/            # Views, assets, and localization language files (lang/)
├── routes/               # API route definitions (api.php)
├── storage/              # Application storage, cache, and log outputs
├── tests/                # Feature and Unit test suites
└── vendor/               # Third-party PHP dependencies
```

---

## Architecture & Design Principles

### Layered Architecture (Clean Architecture)

```
Request Flow:
Route → Middleware → FormRequest (validation) → Controller → DTO → Service → Repository → Model
                                                    ↓
                                               Exception ← Global Handler → ApiResponse → Client
```

| Layer | Responsibility |
|---|---|
| **Controller** | Receives HTTP request, creates DTO, calls service, returns API response |
| **Service** | Contains pure business logic only. No DB queries, no HTTP awareness |
| **Repository** | Handles all database operations. Returns models/collections or throws business exceptions |
| **DTO** | Immutable data transfer object between HTTP and Service layers |
| **FormRequest** | Input validation rules and authorization checks only |
| **Exception** | Custom business exceptions thrown by services/repositories |
| **ApiResponse** | Standardized JSON response structure rendered by global handler |

### SOLID Principles
- **Single Responsibility Principle (SRP)**: Each class has one specific job (e.g., FormRequest validates, Controller delegates, Service executes logic, Repository queries DB).
- **Open/Closed Principle (OCP)**: Design modules to be open for extension but closed for modification using strategy patterns, interfaces, enums, and config mappings.
- **Liskov Substitution Principle (LSP)**: Implementations must fulfill interface contracts predictably without altering expected behavior or signatures.
- **Interface Segregation Principle (ISP)**: Create small, domain-specific interfaces rather than monolithic interfaces.
- **Dependency Inversion Principle (DIP)**: Depend on abstractions/interfaces; bind implementations using Laravel container and constructor injection.

### RESTful API Standards
- **Standard Verbs**: Use `GET` (read), `POST` (create), `PUT`/`PATCH` (update), and `DELETE` (remove).
- **Resource Naming**: Plural nouns for resource endpoints (e.g., `/api/users`, `/api/orders`).
- **Consistent Response Format**: All responses must use the standard `ApiResponse` JSON structure.
- **Appropriate HTTP Status Codes**: `200` Success, `201` Created, `400` Bad Request, `401` Unauthenticated, `403` Forbidden, `404` Not Found, `422` Validation Error, `500` Server Error.

### Clean Code Principles
- Write self-documenting code with clear variable and function names.
- Keep functions small and focused on a single task.
- Enforce strict typing (`declare(strict_types=1);`) and explicit return types.

---

## ✅ DO's

### Architecture & Structure

- ✅ **DO** follow Laravel directory conventions: put Requests in `app/Http/Requests/`, Responses in `app/Http/Responses/`
- ✅ **DO** use the **Repository Pattern** for all database interactions. Never call Eloquent directly from a Service
- ✅ **DO** perform all request validation in separate `FormRequest` classes in `app/Http/Requests/` (never validate inline inside controllers or services)
- ✅ **DO** follow RESTful API conventions, Clean Architecture, and SOLID principles strictly
- ✅ **DO** use constructor injection for dependencies — consider Interfaces when you need multiple implementations or testability
- ✅ **DO** group related files by domain/feature (e.g., `Auth/`, `User/`, `Order/`)
- ✅ **DO** register repository bindings in a dedicated `RepositoryServiceProvider`
- ✅ **DO** keep Controllers thin — they should only: validate input via FormRequest, create DTOs, call a service method, and return a response

### SOLID Principles

- ✅ **DO** follow **SRP**: each class should have one reason to change. A Service handles business logic, a Repository handles DB queries, a Controller handles HTTP
- ✅ **DO** keep dependencies loosely coupled — prefer injection over hard-coded `new` calls
- ✅ **DO** use **constructor injection** for all dependencies (never `new` a dependency inside a class)
- ✅ **DO** keep interfaces small and focused (Interface Segregation Principle)
- ✅ **DO** design classes that are **open for extension but closed for modification** — use strategy patterns, enums, and config over hardcoded switch statements

### DTOs

- ✅ **DO** use DTOs to transfer data between Controller and Service layers
- ✅ **DO** make DTO properties `readonly` when possible
- ✅ **DO** provide a `fromRequest()` static factory method on each DTO
- ✅ **DO** provide a `toArray()` method when the DTO needs to be converted for mass assignment
- ✅ **DO** keep namespaces matching the actual file path (`App\Services\User\DTO`, NOT `App\Http\Services\User\DTO`)

### Exception Handling

- ✅ **DO** create **custom Business Exceptions** that extend a base `BusinessException` class
- ✅ **DO** handle all exceptions in the **Global Exception Handler** (`bootstrap/app.php` → `withExceptions`)
- ✅ **DO** use specific exception classes: `UserNotFoundException`, `UserNotActiveException`, `AuthenticationFailedException`
- ✅ **DO** log the full exception with context (`Log::error('...', ['exception' => $e])`)
- ✅ **DO** return **generic, translatable messages** to the client (use lang files)
- ✅ **DO** use correct HTTP status codes consistently:
  - `200` — Success
  - `201` — Created
  - `400` — Bad Request
  - `401` — Unauthenticated
  - `403` — Forbidden/Unauthorized
  - `404` — Not Found
  - `422` — Validation Error
  - `500` — Server Error

### Response Format

- ✅ **DO** use a **consistent API response format** across ALL endpoints (including validation errors)
- ✅ **DO** make `ApiResponse` stateless — create a new instance or reset state before each use
- ✅ **DO** separate success messages from error messages clearly

```json
{
    "code": 200,
    "data": {},
    "messages": ["Success message"],
    "errors": []
}
```

### Security

- ✅ **DO** use `$fillable` in every Model to whitelist mass-assignable fields
- ✅ **DO** validate all inputs via FormRequest classes before they reach the Controller
- ✅ **DO** use specific query methods (`findByPhone()`, `findByEmail()`) instead of dynamic column names
- ✅ **DO** hash sensitive data before storing it

### Messages & Localization

- ✅ **DO** use Laravel's built-in **lang files** (`resources/lang/en/messages.php`) for all messages
- ✅ **DO** use the `__('messages.key')` helper consistently
- ✅ **DO** support at minimum Arabic (`ar`) and English (`en`) locales
- ✅ **DO** translate all form validation messages and attribute names in `resources/lang/ar/validation.php` and `resources/lang/en/validation.php`
- ✅ **DO** place shared message constants in `app/Constants/Messages/` (outside of any Service folder)

### Code Quality & Comments

- ✅ **DO** use PHP strict types: `declare(strict_types=1);` at the top of every file
- ✅ **DO** add return type declarations to every method
- ✅ **DO** add parameter type hints to every method
- ✅ **DO** use PHP Enums for any fixed set of values (status, type, provider)
- ✅ **DO** remove debug/info logging from production code paths (no `info()` in response builders)
- ✅ **DO** write clean, meaningful comments only when non-obvious business logic requires explanation
- ✅ **DO** import classes via `use` statements at the top of the file and use short class names instead of inline Fully Qualified Class Names (FQN) in both code signatures and PHPDoc / docblock annotations (e.g., `use Illuminate\Contracts\Validation\ValidationRule;` instead of `\Illuminate\Contracts\Validation\ValidationRule`)
- ✅ **DO** update `apidog_collection.json` (OpenAPI 3.0.1) whenever adding or modifying API endpoints, using `multipart/form-data` as the default request body content format.
- ✅ **DO** always read `.env` variables through configuration files using the `config()` helper (e.g., `config('services.sms.condition')`), never use `env()` directly in the code to ensure caching works correctly.

---

## ❌ DON'Ts

### Package Management

- ❌ **DON'T** install or add new packages (`composer require` or `npm install`) without asking the user for explicit permission first

### Architecture & Structure

- ❌ **DON'T** call Eloquent (`User::where(...)`) directly from a Service — use a Repository
- ❌ **DON'T** perform validation inline inside controllers or services — use a FormRequest class
- ❌ **DON'T** put Requests outside `app/Http/Requests/`
- ❌ **DON'T** put Responses outside `app/Http/Responses/`
- ❌ **DON'T** couple Messages/Constants to a specific Service folder — they are cross-cutting concerns
- ❌ **DON'T** create files without matching namespaces — the namespace MUST match the file path

### SOLID Principles

- ❌ **DON'T** give a class multiple responsibilities (e.g., a Service that does DB queries + auth + token management + data mapping)
- ❌ **DON'T** instantiate dependencies with `new` inside class methods — use constructor injection
- ❌ **DON'T** duplicate the same method across classes (e.g., `respondWithToken()` in two services) — extract to a shared service or trait
- ❌ **DON'T** modify a base class every time you need a new variant — extend or compose instead

### Exception Handling

- ❌ **DON'T** throw `HttpResponseException` from Services — Services should not know about HTTP
- ❌ **DON'T** inject `ApiResponse` into Services just for exception formatting
- ❌ **DON'T** catch an exception just to re-throw it without doing anything (e.g., `catch (HttpResponseException $e) { throw $e; }`)
- ❌ **DON'T** expose internal error messages (`$e->getMessage()`) to the API client — this leaks SQL, file paths, and class names
- ❌ **DON'T** wrap every method in try-catch boilerplate — use the global exception handler
- ❌ **DON'T** use different HTTP status codes for the same error in different places (e.g., User not found → 401 in one place and 404 in another)
- ❌ **DON'T** use `422` as a catch-all error code — use the appropriate HTTP status code

### Response Format

- ❌ **DON'T** return different JSON structures from different error sources (e.g., validation errors with `{"code", "message"}` vs API errors with `{"code", "errors", "data", "messages"}`)
- ❌ **DON'T** use a shared/stateful `ApiResponse` object that accumulates state across multiple calls — create fresh instances or reset state
- ❌ **DON'T** leave `info()` or `dd()` or `dump()` calls in production code

### Security

- ❌ **DON'T** use `$guarded = []` in Models — this allows mass assignment of ANY field including `wallet`, `status`, `is_admin`
- ❌ **DON'T** accept dynamic column names from user input for database queries (SQL injection risk)
- ❌ **DON'T** expose stack traces or internal exception details in API responses
- ❌ **DON'T** skip FormRequest validation for any endpoint that accepts user input

### DTOs

- ❌ **DON'T** perform business logic inside DTOs (e.g., hashing passwords in `UpdatePasswordDTO::fromRequest()`) — the DTO should only carry data; hashing belongs in the Service
- ❌ **DON'T** use nullable return types (`?string`) when the method will never actually return null
- ❌ **DON'T** use mismatched namespaces — if the file is in `app/Services/User/DTO/`, the namespace MUST be `App\Services\User\DTO`

### Messages & Localization

- ❌ **DON'T** return un-localized or hardcoded English validation messages/attributes when the request header specifies Arabic (`lang: ar` / `Accept-Language: ar`)

### Code Quality & Comments

- ❌ **DON'T** write unnecessary, trivial, or stupid comments that state the obvious (e.g., `// return response`, `// save user`, `// constructor`)
- ❌ **DON'T** leave commented-out code in production (e.g., `// public function logoutUser(...)`)
- ❌ **DON'T** leave typos in type hints (e.g., `strong` instead of `string`)
- ❌ **DON'T** ignore IDE/static analysis warnings
- ❌ **DON'T** mix Arabic and English in code comments — pick one language for comments
- ❌ **DON'T** use generic `Exception` catch when a more specific exception type is available
- ❌ **DON'T** use inline Fully Qualified Class Names (FQN) in code or PHPDoc annotations (e.g., `\Illuminate\Contracts\Validation\ValidationRule`) — always import classes at the top of the file using `use` statements and use short class names

---

## Naming Conventions

| Element | Convention | Example |
|---|---|---|
| Controller | `PascalCase` + `Controller` | `AuthController` |
| Service | `PascalCase` + `Service` | `LoginService` |
| Repository | `PascalCase` + `Repository` | `UserRepository` |
| Interface | `PascalCase` + `Interface` | `UserRepositoryInterface` |
| DTO | `PascalCase` + `DTO` | `LoginUserDTO` |
| Enum | `PascalCase` + `Enum` | `UserStatusEnum` |
| Exception | `PascalCase` + `Exception` | `UserNotFoundException` |
| FormRequest | `PascalCase` + `Request` | `LoginRequest` |
| Migration | snake_case with timestamp | `2024_01_01_create_users_table` |
| Model | Singular PascalCase | `User` |
| Route | kebab-case | `auth/login` |

---

## Testing Standards

- Write **Feature Tests ONLY** (no Unit tests needed) for all API endpoints.
- Tests must cover the full request lifecycle from the HTTP route/endpoint down to the JSON response (`Route → Middleware → Request → Controller → Response`).
- Use **Mockery** to mock underlying Service layer calls and data return structures using `mockService()` (via `Tests\MockeryHelperTrait` in `Tests\TestCase`).
- Mock dependencies using helper methods (e.g. `$this->mockService(UserService::class, ['methodName' => $mockData])`).
- Test both success and failure paths (e.g., status 200, 201, 400, 401, 403, 404, 422).
- Validate input validations, localized validation error messages, and HTTP status codes via Feature tests.
