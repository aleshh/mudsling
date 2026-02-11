create extension if not exists "pgcrypto";

create table if not exists public.profiles (
  id uuid primary key references auth.users on delete cascade,
  display_name text not null,
  maximum_consumption numeric(6, 2) default 0,
  created_at timestamptz default now(),
  updated_at timestamptz default now()
);

create or replace function public.set_updated_at()
returns trigger as $$
begin
  new.updated_at = now();
  return new;
end;
$$ language plpgsql;

create trigger profiles_set_updated_at
before update on public.profiles
for each row execute procedure public.set_updated_at();

create or replace function public.handle_new_user()
returns trigger as $$
begin
  insert into public.profiles (id, display_name)
  values (
    new.id,
    coalesce(new.raw_user_meta_data->>'display_name', split_part(new.email, '@', 1))
  );
  return new;
end;
$$ language plpgsql security definer;

drop trigger if exists on_auth_user_created on auth.users;
create trigger on_auth_user_created
after insert on auth.users
for each row execute procedure public.handle_new_user();

create table if not exists public.beverages (
  id uuid primary key default gen_random_uuid(),
  user_id uuid not null references auth.users on delete cascade default auth.uid(),
  name text not null,
  category text not null,
  size_oz numeric(5, 1) not null check (size_oz > 0),
  strength_pct numeric(4, 1) not null check (strength_pct >= 0),
  deleted boolean default false,
  created_at timestamptz default now(),
  updated_at timestamptz default now()
);

create trigger beverages_set_updated_at
before update on public.beverages
for each row execute procedure public.set_updated_at();

create table if not exists public.servings (
  id uuid primary key default gen_random_uuid(),
  user_id uuid not null references auth.users on delete cascade default auth.uid(),
  beverage_id uuid not null references public.beverages on delete restrict,
  local_time timestamptz default now(),
  created_at timestamptz default now()
);

create index if not exists servings_user_time_idx
on public.servings (user_id, local_time desc);

alter table public.profiles enable row level security;
alter table public.beverages enable row level security;
alter table public.servings enable row level security;

create policy "Profiles are readable by owner"
on public.profiles
for select
using (auth.uid() = id);

create policy "Profiles are updatable by owner"
on public.profiles
for update
using (auth.uid() = id)
with check (auth.uid() = id);

create policy "Profiles are insertable by owner"
on public.profiles
for insert
with check (auth.uid() = id);

create policy "Beverages are manageable by owner"
on public.beverages
for all
using (auth.uid() = user_id)
with check (auth.uid() = user_id);

create policy "Servings are manageable by owner"
on public.servings
for all
using (auth.uid() = user_id)
with check (auth.uid() = user_id);
