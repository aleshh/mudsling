-- Demo auth user for local development
-- Email: demo@mudsling.test
-- Password: password123

insert into auth.users (
  id,
  instance_id,
  aud,
  role,
  email,
  encrypted_password,
  email_confirmed_at,
  raw_user_meta_data,
  created_at,
  updated_at
)
values (
  '11111111-1111-1111-1111-111111111111',
  '00000000-0000-0000-0000-000000000000',
  'authenticated',
  'authenticated',
  'demo@mudsling.test',
  crypt('password123', gen_salt('bf')),
  now(),
  jsonb_build_object('display_name', 'Demo Drinker'),
  now(),
  now()
)
on conflict (id) do nothing;

insert into auth.identities (
  id,
  user_id,
  identity_data,
  provider,
  created_at,
  updated_at
)
values (
  '22222222-2222-2222-2222-222222222222',
  '11111111-1111-1111-1111-111111111111',
  jsonb_build_object(
    'sub', '11111111-1111-1111-1111-111111111111',
    'email', 'demo@mudsling.test'
  ),
  'email',
  now(),
  now()
)
on conflict (id) do nothing;

update public.profiles
set maximum_consumption = 2.0
where id = '11111111-1111-1111-1111-111111111111';

insert into public.beverages (
  id,
  user_id,
  name,
  category,
  size_oz,
  strength_pct,
  deleted,
  created_at,
  updated_at
)
values
  (
    '33333333-3333-3333-3333-333333333333',
    '11111111-1111-1111-1111-111111111111',
    'Pale Ale',
    'Beer',
    12.0,
    5.0,
    false,
    now() - interval '2 days',
    now() - interval '1 hours'
  ),
  (
    '44444444-4444-4444-4444-444444444444',
    '11111111-1111-1111-1111-111111111111',
    'Cabernet',
    'Wine',
    5.0,
    13.5,
    false,
    now() - interval '3 days',
    now() - interval '2 days'
  )
on conflict (id) do nothing;

insert into public.servings (
  id,
  user_id,
  beverage_id,
  local_time,
  created_at
)
values
  (
    '55555555-5555-5555-5555-555555555555',
    '11111111-1111-1111-1111-111111111111',
    '33333333-3333-3333-3333-333333333333',
    now() - interval '2 hours',
    now() - interval '2 hours'
  ),
  (
    '66666666-6666-6666-6666-666666666666',
    '11111111-1111-1111-1111-111111111111',
    '44444444-4444-4444-4444-444444444444',
    now() - interval '1 days',
    now() - interval '1 days'
  ),
  (
    '77777777-7777-7777-7777-777777777777',
    '11111111-1111-1111-1111-111111111111',
    '33333333-3333-3333-3333-333333333333',
    now() - interval '3 days',
    now() - interval '3 days'
  )
on conflict (id) do nothing;
