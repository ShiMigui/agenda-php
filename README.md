# Agenda PHP

Aplicação web de **agenda de clientes e contatos** em PHP puro com MySQL, empacotada em Docker.

## Funcionalidades

- Autenticação de usuários (`Auth`)
- CRUD de clientes e contatos (padrão *Repository*)
- Alertas de feedback (`Alert`)
- Conexão com MySQL via `mysqli` (singleton)
- Imagem Docker com Apache + PHP 8.4

## Estrutura

```
app/
├── dockerfile                # Apache + PHP 8.4 + extensão mysqli
├── private/                  # código da aplicação (fora do docroot)
│   ├── Auth.php              # autenticação
│   ├── Connection.php        # conexão com o banco
│   ├── repository/           # acesso a dados (ClientRepository, ContactRepository)
│   ├── component/, template/ # componentes e templates HTML
│   └── trait/TSingleton.php  # padrão singleton
└── public/                   # docroot (arquivos públicos)
```

## Executando

Configure as variáveis de ambiente do banco (`DB_NAME`, `DB_HOST`, `DB_USER`, `DB_PASS`) e suba com Docker:

```bash
docker build -t agenda-php ./app
docker run -p 8080:80 \
  -e DB_HOST=... -e DB_NAME=... -e DB_USER=... -e DB_PASS=... \
  agenda-php
```

Acesse `http://localhost:8080`.
