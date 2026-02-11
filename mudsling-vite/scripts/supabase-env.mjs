import { execSync } from 'node:child_process';
import { writeFileSync } from 'node:fs';

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
    anonKey: env.ANON_KEY ?? env.SUPABASE_ANON_KEY,
  };
};

const extractFromStatus = (text) => {
  const cleanText = stripAnsi(text);
  const apiMatch = cleanText.match(/API URL:\s*(\S+)/i);
  const keyMatch = cleanText.match(/Publishable key:\s*(\S+)/i);
  return {
    apiUrl: apiMatch?.[1] ? normalizeUrl(apiMatch[1]) : undefined,
    anonKey: keyMatch?.[1] ? cleanValue(keyMatch[1]) : undefined,
  };
};

let apiUrl = process.env.SUPABASE_URL;
let anonKey = process.env.SUPABASE_ANON_KEY;
let envOutput = '';
let statusOutput = '';
const fallbackUrl = process.env.SUPABASE_URL || 'http://127.0.0.1:54321';

if (!apiUrl || !anonKey) {
  try {
    envOutput = run('supabase status --output env');
    ({ apiUrl, anonKey } = extractFromEnv(envOutput));
  } catch (error) {
    // noop, try plain status
  }
}

const isValidUrl = (value) => /^https?:\/\//i.test(value ?? '');

if (!apiUrl || !anonKey || !isValidUrl(apiUrl)) {
  statusOutput = run('supabase status');
  ({ apiUrl, anonKey } = extractFromStatus(statusOutput));
}

if (!apiUrl || !isValidUrl(apiUrl)) {
  const fromEnv = stripAnsi(envOutput).match(/https?:\/\/\S+/i)?.[0];
  const fromStatus = stripAnsi(statusOutput).match(/https?:\/\/\S+/i)?.[0];
  apiUrl = normalizeUrl(fromEnv || fromStatus || apiUrl);
}

if (!apiUrl || !isValidUrl(apiUrl)) {
  apiUrl = fallbackUrl;
}

if (!apiUrl || !anonKey) {
  console.error('Unable to read local Supabase credentials. Run `supabase status` and verify output.');
  process.exit(1);
}

const envContents = `VITE_SUPABASE_URL=${apiUrl}\nVITE_SUPABASE_ANON_KEY=${anonKey}\n`;
writeFileSync('.env.local', envContents, 'utf8');

console.log('Wrote .env.local with local Supabase credentials.');
