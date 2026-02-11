const millisecondsPerDay = 24 * 60 * 60 * 1000;

export function startOfDay(date) {
  return new Date(date.getFullYear(), date.getMonth(), date.getDate());
}

export function daysBetween(a, b) {
  const startA = startOfDay(a).getTime();
  const startB = startOfDay(b).getTime();
  return Math.floor((startA - startB) / millisecondsPerDay);
}

export function formatDayName(isoDate) {
  const target = new Date(isoDate);
  const today = new Date();
  const diff = daysBetween(today, target);

  if (diff === 0) return 'Today';
  if (diff === 1) return 'Yesterday';
  if (diff < 7) {
    return new Intl.DateTimeFormat(undefined, { weekday: 'long' }).format(target);
  }

  return new Intl.DateTimeFormat(undefined, {
    month: 'long',
    day: '2-digit',
    year: 'numeric',
  }).format(target);
}

export function formatTime(isoDate) {
  const date = new Date(isoDate);
  return new Intl.DateTimeFormat(undefined, {
    hour: 'numeric',
    minute: '2-digit',
  }).format(date);
}

export function formatRelative(isoDate) {
  const date = new Date(isoDate);
  const diff = date.getTime() - Date.now();
  const rtf = new Intl.RelativeTimeFormat(undefined, { numeric: 'auto' });
  const minutes = Math.round(diff / (1000 * 60));
  if (Math.abs(minutes) < 60) {
    return rtf.format(minutes, 'minute');
  }

  const hours = Math.round(diff / (1000 * 60 * 60));
  if (Math.abs(hours) < 24) {
    return rtf.format(hours, 'hour');
  }

  const days = Math.round(diff / millisecondsPerDay);
  return rtf.format(days, 'day');
}
