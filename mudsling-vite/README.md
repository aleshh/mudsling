# Mudsling (Vite + Supabase)

## Quick Start (Local Supabase)
1. Install the Supabase CLI and Docker.
2. Start the local stack from `mudsling-vite/`:

```bash
supabase start
supabase db reset
```

3. Copy `.env.example` to `.env` and fill in your local Supabase URL + anon key
   from `supabase status` (typically `http://localhost:54321`).
4. Install dependencies and start the dev server:

```bash
npm install
npm run dev
```

You can also run everything in one command after `.env` is set:

```bash
npm run dev:local
```

`npm run dev:local` will reset the local database (with retry on 502 errors),
create/refresh `.env.local` using `supabase status`, and then start Vite.

## Supabase Auth Notes
- The local stack uses `supabase/config.toml` for redirect URLs.
- Email confirmation is enabled by default in Supabase. The app will show a
  “check your email” message after sign‑up.
- Local email testing is available at `http://localhost:54324` (Inbucket).

## Demo Account (Local)
- Email: `demo@mudsling.test`
- Password: `password123`

## Environment Variables
- `VITE_SUPABASE_URL`
- `VITE_SUPABASE_ANON_KEY`

## Project Layout
- `src/pages`: route‑level screens
- `src/components`: layout + shared UI pieces
- `src/lib`: date + alcohol math helpers
- `supabase/migrations`: tables, triggers, and RLS policies
- `supabase/seed.sql`: intentionally empty (auth seeding handled by script)
- `scripts/seed-local.mjs`: creates demo user + sample data locally
