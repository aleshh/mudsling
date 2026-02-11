import { spawn } from 'node:child_process';
import { setTimeout as delay } from 'node:timers/promises';

const runCommand = (command, args, { capture = true, env } = {}) =>
  new Promise((resolve) => {
    const child = spawn(command, args, {
      env: env ?? process.env,
      stdio: capture ? ['inherit', 'pipe', 'pipe'] : 'inherit',
    });

    let output = '';

    if (capture) {
      child.stdout.on('data', (data) => {
        output += data.toString();
        process.stdout.write(data);
      });

      child.stderr.on('data', (data) => {
        output += data.toString();
        process.stderr.write(data);
      });
    }

    child.on('close', (code) => {
      resolve({ code: code ?? 0, output });
    });
  });

const runOrExit = async (command, args, options) => {
  const result = await runCommand(command, args, options);
  if (result.code !== 0) {
    process.exit(result.code);
  }
  return result;
};

const resetDatabase = async () => {
  const result = await runCommand('supabase', ['db', 'reset']);
  if (result.code === 0) return true;

  if (/Error status 502/i.test(result.output)) {
    return false;
  }

  process.exit(result.code || 1);
  return false;
};

const parseEnvOutput = (text) => {
  const entries = text
    .split('\n')
    .map((line) => line.trim())
    .filter((line) => line && line.includes('='))
    .map((line) => {
      const [rawKey, ...rest] = line.split('=');
      const key = rawKey.replace(/^export\s+/i, '').trim();
      const value = rest.join('=').replace(/^['"]|['"]$/g, '').trim();
      return [key, value];
    });

  const env = Object.fromEntries(entries);

  return {
    apiUrl: env.API_URL ?? env.SUPABASE_URL,
    anonKey: env.ANON_KEY ?? env.SUPABASE_ANON_KEY,
    serviceKey:
      env.SERVICE_ROLE_KEY ??
      env.SUPABASE_SERVICE_ROLE_KEY ??
      env.SECRET_KEY ??
      env.SUPABASE_SECRET_KEY,
  };
};

const main = async () => {
  await runOrExit('supabase', ['start']);

  let success = false;
  for (let attempt = 1; attempt <= 3; attempt += 1) {
    const ok = await resetDatabase();
    if (ok) {
      success = true;
      break;
    }

    if (attempt < 3) {
      console.warn('Supabase reset returned 502; retrying in 5 seconds...');
      await delay(5000);
    }
  }

  if (!success) {
    console.error('Supabase db reset failed after retries.');
    process.exit(1);
  }

  let apiUrl;
  let anonKey;
  let serviceKey;

  const statusEnv = await runCommand('supabase', ['status', '--output', 'env']);
  if (statusEnv.code === 0) {
    ({ apiUrl, anonKey, serviceKey } = parseEnvOutput(statusEnv.output));
  }

  const sharedEnv = {
    ...process.env,
    ...(apiUrl ? { SUPABASE_URL: apiUrl } : {}),
    ...(anonKey ? { SUPABASE_ANON_KEY: anonKey } : {}),
    ...(serviceKey ? { SUPABASE_SERVICE_ROLE_KEY: serviceKey } : {}),
  };

  const seedResult = await runCommand('node', ['scripts/seed-local.mjs'], {
    env: sharedEnv,
  });
  if (seedResult.code !== 0) {
    console.warn('Seed step failed; continuing without demo user.');
  }
  await runOrExit('node', ['scripts/supabase-env.mjs'], { env: sharedEnv });

  const vite = spawn('vite', { stdio: 'inherit' });
  vite.on('close', (code) => process.exit(code ?? 0));
};

main();
