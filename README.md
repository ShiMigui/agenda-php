# Agenda PHP

Web application for a **client and contact address book** in plain PHP with MySQL, packaged in Docker.

## Features

- User authentication (`Auth`)
- CRUD for clients and contacts (*Repository* pattern)
- Feedback alerts (`Alert`)
- MySQL connection via `mysqli` (singleton)
- Docker image with Apache + PHP 8.4

## Structure

```
app/
├── dockerfile                # Apache + PHP 8.4 + mysqli extension
├── private/                  # application code (outside the docroot)
│   ├── Auth.php              # authentication
│   ├── Connection.php        # database connection
│   ├── repository/           # data access (ClientRepository, ContactRepository)
│   ├── component/, template/ # HTML components and templates
│   └── trait/TSingleton.php  # singleton pattern
└── public/                   # docroot (public files)
```

## Running

Configure the database environment variables (`DB_NAME`, `DB_HOST`, `DB_USER`, `DB_PASS`) and start with Docker:

```bash
docker build -t agenda-php ./app
docker run -p 8080:80 \
  -e DB_HOST=... -e DB_NAME=... -e DB_USER=... -e DB_PASS=... \
  agenda-php
```

Access `http://localhost:8080`.
