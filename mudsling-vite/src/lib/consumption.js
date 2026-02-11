export function alcoholOunces(sizeOz, strengthPct) {
  return (Number(sizeOz) * Number(strengthPct)) / 100;
}

export function roundAlcohol(value) {
  if (value > 9.5) {
    return Math.round(value);
  }
  return Math.round(value * 10) / 10;
}

export function percentOfMax(alcohol, max) {
  if (!max || max <= 0) return 0;
  return (alcohol / max) * 100;
}
