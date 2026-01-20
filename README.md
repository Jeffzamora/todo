# Todo Pro API (PHP + MySQL)

Base URL (XAMPP):
- http://localhost/todo/public

## Requisitos
- PHP 8.x
- MariaDB/MySQL (XAMPP)
- Importar la BD: `todo_pro.sql`

## Variables (.env)
- DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS
- JWT_SECRET (largo)
- JWT_TTL_MIN (15 recomendado)
- REFRESH_TTL_DAYS (30)
- CRON_KEY (para /cron/recurring/run)

## Endpoints clave
- GET /health
- POST /auth/login
- POST /auth/refresh
- POST /auth/logout

Protegidos con `Authorization: Bearer <token>`:
- /projects, /tasks, /tags, /shopping/*, /pantry, /recurring, /audit, /shopping/templates

## Cron (recurrencias)
- POST /cron/recurring/run?key=CRON_KEY&limit=50

Configura un cron en hosting para llamarlo cada 5-10 minutos.

## Docs
- docs/openapi.json (OpenAPI)
- docs/postman_collection.json (Postman)

## Migraciones / Fixes
Si importaste `todo_pro.sql` desde un dump antiguo, aplica este fix (importante para consistencia de tipos):
- `migrations/001_fix_audit_log_user_id.sql`

Esto alinea `audit_log.user_id` con `usuarios.id` (BIGINT UNSIGNED) y evita errores con llaves foráneas en otros entornos.

## Flujo recomendado para Flutter (tokens)
1) Login: `POST /auth/login` → guarda `access_token` + `refresh_token`.
2) Cada request protegido: header `Authorization: Bearer <access_token>`.
3) Si recibes 401/invalid token: llama `POST /auth/refresh` con `{ "refresh_token": "..." }`.
4) Actualiza tokens guardados y reintenta el request.
5) Logout: `POST /auth/logout` con `{ "refresh_token": "..." }`.

Recomendación de storage: `flutter_secure_storage`.

## Producción (hosting)
- Configura `APP_DEBUG=false` en `.env`.
- Ajusta CORS en `public/index.php` (reemplaza `*` por tu dominio/app).
- Protege el endpoint cron usando `CRON_KEY` (ya implementado).
