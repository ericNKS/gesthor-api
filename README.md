# Gesthor-api

**Gesthor-api** is a RESTful API designed to manage users and companies, supporting core authentication and resource operations.

---
&nbsp;

## 📁 Endpoints

### 🏢 CompanyController

Base Path: `/api/company`

#### 🔍 Get Company by ID

#### `GET`
- **Endpoint**: `/api/company/{id}`
- **Response**: `200 OK`
- **Description**: Retrieves company information by ID.
  &nbsp;

#### ➕ Create Company

#### `POST`
- **Endpoint**: `/api/company/`
- **Request Body**:
  ```json
  {
    "name": "Company Name",     // min length: 3
    "cnpj": "12345678901234"    // length: 14
  }
  ```

- **Response**: `201 Created`

- **Description**: Creates a new company with a valid name and CNPJ.
  &nbsp;

#### ♻️ Update Company

#### `PATCH or PUT`

- **Endpoint**:  `/api/company/{id}`

- **Request Body**:
```json
{
"name": "New Name"
}
```
- **Response**: `200 OK`

- **Description**: Updates the company's name.
  &nbsp;


---

### 👤 UserController

#### 🔍 Get User by ID
#### `GET`

- **Endpoint**: `/api/user/{id}`
- **Response**: `200 OK`
- **Description**: Retrieves user information by ID.
  &nbsp;

#### 📝 Register
#### `POST`

- **Endpoint**: `/api/register`

- Request Body:

```json
{
"email": "user@example.com",
"password": {
"first": "password123",
"second": "password123"
},
"comId": 1
}
```
- **Response**: `201 Created`
- **Description**: Registers a new user linked to a company. Returns a JWT token.
  &nbsp;

#### 🔐 Login

#### `POST`

- **Endpoint**: `/api/login`
- Request Body:
```json
{
"email": "user@example.com",
"password": "password123"
}
```
- **Response**: `200 OK`
- **Description**: Authenticates the user and returns a JWT token.
  &nbsp;

#### 🚪 Logout
#### `POST`

- **Endpoint**: `/api/logout`
- **Response**: `204 No Content`
- **Description**: Revokes the user's JWT token and ends the session.
  &nbsp;

---

#### 🛠️ Tech Stack
- PHP with Symfony Framework
- JWT Authentication
- RESTful API
  &nbsp;

---

📌 Notes
- All routes return appropriate HTTP status codes.
- JWT is required for protected routes (where applicable).
- Validation is enforced on inputs such as name and CNPJ for companies.
