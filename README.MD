# Laravel API

## Install

Run artisan command for laravel basic authorization:

```bash
php artisan make:auth
```

Install package:

```bash
composer require schepotin/laravel-api
```

Run command:

```bash
php artisan laravel-api:publish
```

Run migrations:

```bash
php artisan migrate
```

## Boilerplate for Vue

```bash
php artisan laravel-api:publish --vue
```

```bash
npm install
```

```bash
npm run dev
```

## Eslint

```bash
npm run eslint
```

## API

### Register:

request:

```javascript
fetch('/api/v1/register', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
  },
  body: "name=John&email=johndoe@gmail.com&password=qwerty&password_confirmation=qwerty",
})
.then((response) => response.json())
.then((data) => {
  console.log(data);
});
```

response:

```json
{
  "status": 1,
  "token": "yQZKTMgpB9wmX3seCH1XKrHyq62YQXijvnoAkFsHXmNcuv5SRhiTuWqbdsIP"
}
```

### Login:

request:

```javascript
fetch('/api/v1/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
  },
  body: "email=johndoe@gmail.com&password=123456",
})
.then((response) => response.json())
.then((data) => {
  console.log(data);
});
```

response:

```json
{
  "status": 1,
  "token": "yQZKTMgpB9wmX3seCH1XKrHyq62YQXijvnoAkFsHXmNcuv5SRhiTuWqbdsIP"
}
```

### Password reset:

request:

```javascript
fetch('/api/v1/password/email', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
  },
  body: "email=johndoe@gmail.com",
})
.then((response) => response.json())
.then((data) => {
  console.log(data);
});
```

response:

```json
{
  "status": 1,
  "data": {
    "message": "passwords.sent"
  }
}
```

request:

```javascript
fetch('/api/v1/reset/password', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
  },
  body: "email=johndoe@gmail.com&password=qwerty&password_confirmation=qwerty&token=2b8db1c9655ed1dcf1752867b652774e48e890e2709daa992f271df5d787a8ce",
})
.then((response) => response.json())
.then((data) => {
  console.log(data);
});
```

response:

```json
{
  "status": 1,
  "data": {
    "message": "passwords.reset"
  }
}
```

### Get current logged user

request:

```javascript
fetch('/api/v1/user/current', {
  method: 'GET',
  headers: {
    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
    Authorization: 'Bearer yQZKTMgpB9wmX3seCH1XKrHyq62YQXijvnoAkFsHXmNcuv5SRhiTuWqbdsIP',
  },
})
.then((response) => response.json())
.then((data) => {
  console.log(data);
});
```

response:

```json
{
  "status": 1,
  "data": {
    "id": 1,
    "name": "John",
    "email": "johndoe@gmail.com",
    "created_at": "2017-09-23 12:26:44",
    "updated_at": "2017-09-23 12:26:44"
  }
}
```
