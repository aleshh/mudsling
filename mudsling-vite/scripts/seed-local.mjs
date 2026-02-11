import { execSync } from 'node:child_process';
import { createClient } from '@supabase/supabase-js';

const run = (command) => execSync(command, { encoding: 'utf8' }).trim();
const stripAnsi = (text) => text.replace(/\x1b\[[0-9;]*m/g, '');

const cleanValue = (value) => value.replace(/^['"]|['"]$/g, '').trim();
const normalizeUrl = (value) => {
  if (!value) return value;
  const cleaned = cleanValue(value);
  const match = cleaned.match(/https?:\/\/\S+/i);
  return match ? match[0].replace(/['"]/g, '') : cleaned;
};

const extractFromEnv = (text) => {
  const entries = stripAnsi(text)
    .split('\n')
    .map((line) => line.trim())
    .filter((line) => line && line.includes('='))
    .map((line) => {
      const [rawKey, ...rest] = line.split('=');
      const key = rawKey.replace(/^export\s+/i, '').trim();
      const value = rest.join('=');
      return [key, cleanValue(value)];
    });

  const env = Object.fromEntries(entries);

  return {
    apiUrl: normalizeUrl(env.API_URL ?? env.SUPABASE_URL),
    serviceKey:
      env.SERVICE_ROLE_KEY ??
      env.SUPABASE_SERVICE_ROLE_KEY ??
      env.SECRET_KEY ??
      env.SUPABASE_SECRET_KEY,
  };
};

const extractFromStatus = (text) => {
  const cleanText = stripAnsi(text);
  const apiMatch = cleanText.match(/API URL:\s*(\S+)/i);
  const keyMatch = cleanText.match(/Secret key:\s*(\S+)/i);
  return {
    apiUrl: apiMatch?.[1] ? normalizeUrl(apiMatch[1]) : undefined,
    serviceKey: keyMatch?.[1] ? cleanValue(keyMatch[1]) : undefined,
  };
};

let apiUrl = process.env.SUPABASE_URL;
let serviceKey = process.env.SUPABASE_SERVICE_ROLE_KEY;
let envOutput = '';
let statusOutput = '';
const debug = process.env.SEED_DEBUG === '1';
const fallbackUrl = process.env.SUPABASE_URL || 'http://127.0.0.1:54321';

if (!apiUrl || !serviceKey) {
  try {
    envOutput = run('supabase status --output env');
    ({ apiUrl, serviceKey } = extractFromEnv(envOutput));
  } catch (error) {
    // noop, try plain status
  }
}

const isValidUrl = (value) => /^https?:\/\//i.test(value ?? '');

if (!apiUrl || !serviceKey || !isValidUrl(apiUrl)) {
  statusOutput = run('supabase status');
  ({ apiUrl, serviceKey } = extractFromStatus(statusOutput));
}

if (!apiUrl || !isValidUrl(apiUrl)) {
  const fromEnv = stripAnsi(envOutput).match(/https?:\/\/\S+/i)?.[0];
  const fromStatus = stripAnsi(statusOutput).match(/https?:\/\/\S+/i)?.[0];
  apiUrl = normalizeUrl(fromEnv || fromStatus || apiUrl);
}

if (!apiUrl || !isValidUrl(apiUrl)) {
  apiUrl = fallbackUrl;
}

if (debug) {
  const keyPreview = serviceKey ? `${serviceKey.slice(0, 8)}…` : 'missing';
  console.log('[seed-local] apiUrl:', apiUrl);
  console.log('[seed-local] serviceKey:', keyPreview);
}

if (!apiUrl || !serviceKey) {
  console.error('Unable to read local Supabase service credentials.');
  process.exit(1);
}

const supabase = createClient(apiUrl, serviceKey, {
  auth: { autoRefreshToken: false, persistSession: false },
});

const demoEmail = 'demo@mudsling.test';
const demoPassword = 'password123';
const demoName = 'Demo Drinker';

const findUserByEmail = async () => {
  const { data, error } = await supabase.auth.admin.listUsers({
    page: 1,
    perPage: 1000,
  });

  if (error) {
    return null;
  }

  return data?.users?.find((user) => user.email === demoEmail) ?? null;
};

const ensureUser = async () => {
  const existing = await findUserByEmail();
  if (existing) return existing;

  const { data, error } = await supabase.auth.admin.createUser({
    email: demoEmail,
    password: demoPassword,
    email_confirm: true,
    user_metadata: { display_name: demoName },
  });

  if (error) {
    const alreadyExists =
      error.status === 422 ||
      /already|exists|registered/i.test(error.message ?? '');
    if (alreadyExists) {
      const retry = await findUserByEmail();
      if (retry) return retry;
    }

    console.error('Failed to create demo user:', error.message);
    process.exit(1);
  }

  return data.user;
};

const main = async () => {
  const user = await ensureUser();

  await supabase
    .from('profiles')
    .upsert({ id: user.id, display_name: demoName, maximum_consumption: 2.0 });

  const beverages = [
    {
      id: '33333333-3333-3333-3333-333333333333',
      user_id: user.id,
      name: 'Pale Ale',
      category: 'Beer',
      size_oz: 12.0,
      strength_pct: 5.0,
      deleted: false,
    },
    {
      id: '44444444-4444-4444-4444-444444444444',
      user_id: user.id,
      name: 'Cabernet',
      category: 'Wine',
      size_oz: 5.0,
      strength_pct: 13.5,
      deleted: false,
    },
  ];

  const servings = [
    {
      id: '55555555-5555-5555-5555-555555555555',
      user_id: user.id,
      beverage_id: beverages[0].id,
      local_time: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString(),
    },
    {
      id: '66666666-6666-6666-6666-666666666666',
      user_id: user.id,
      beverage_id: beverages[1].id,
      local_time: new Date(Date.now() - 24 * 60 * 60 * 1000).toISOString(),
    },
    {
      id: '77777777-7777-7777-7777-777777777777',
      user_id: user.id,
      beverage_id: beverages[0].id,
      local_time: new Date(Date.now() - 3 * 24 * 60 * 60 * 1000).toISOString(),
    },
  ];

  await supabase.from('beverages').upsert(beverages, { onConflict: 'id' });
  await supabase.from('servings').upsert(servings, { onConflict: 'id' });

  console.log('Seeded demo user + sample data.');
};

main();
