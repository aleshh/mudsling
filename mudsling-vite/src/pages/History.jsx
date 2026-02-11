import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { supabase } from '../supabaseClient';
import { useAuth } from '../contexts/AuthContext';
import { alcoholOunces, percentOfMax, roundAlcohol } from '../lib/consumption';
import { formatDayName, formatTime, startOfDay } from '../lib/date';

const makeDayKey = (isoDate) => {
  const date = new Date(isoDate);
  return startOfDay(date).toISOString();
};

export default function History() {
  const { profile } = useAuth();
  const [days, setDays] = useState([]);
  const [maxPercent, setMaxPercent] = useState(100);
  const [expanded, setExpanded] = useState({});
  const [error, setError] = useState('');

  const maxConsumption = Number(profile?.maximum_consumption ?? 0);
  const maxConsumptionSet = maxConsumption > 0;
  const maxForPercent = maxConsumptionSet ? maxConsumption : 1;

  const loadHistory = async () => {
    setError('');
    const { data, error: fetchError } = await supabase
      .from('servings')
      .select('id, local_time, beverage:beverages (id, name, size_oz, strength_pct)')
      .order('local_time', { ascending: false });

    if (fetchError) {
      setError(fetchError.message);
      return;
    }

    const grouped = data.reduce((acc, serving) => {
      const dayKey = makeDayKey(serving.local_time);
      if (!acc[dayKey]) {
        acc[dayKey] = { date: dayKey, servings: [] };
      }
      acc[dayKey].servings.push(serving);
      return acc;
    }, {});

    let maxPercentValue = 100;
    const list = Object.values(grouped).map((day) => {
      const alcohol = day.servings.reduce(
        (total, serving) =>
          total + alcoholOunces(serving.beverage?.size_oz ?? 0, serving.beverage?.strength_pct ?? 0),
        0,
      );
      const percent = percentOfMax(alcohol, maxForPercent);
      if (percent > maxPercentValue) maxPercentValue = percent;

      return {
        ...day,
        alcohol: roundAlcohol(alcohol),
        drinks: day.servings.length,
        percent,
      };
    });

    list.sort((a, b) => new Date(b.date) - new Date(a.date));
    setDays(list);
    setMaxPercent(maxPercentValue);
  };

  useEffect(() => {
    loadHistory();
  }, [profile?.maximum_consumption]);

  const handleUndo = async () => {
    const confirmed = window.confirm('Undo the last drink?');
    if (!confirmed) return;

    const { data, error: latestError } = await supabase
      .from('servings')
      .select('id')
      .order('local_time', { ascending: false })
      .limit(1);

    if (latestError) {
      setError(latestError.message);
      return;
    }

    if (!data.length) return;

    const { error: deleteError } = await supabase.from('servings').delete().eq('id', data[0].id);

    if (deleteError) {
      setError(deleteError.message);
      return;
    }

    loadHistory();
  };

  const hasDays = days.length > 0;

  return (
    <div>
      <button type="button" className="btn btn-small btn-delete" onClick={handleUndo}>
        Undo last drink
      </button>

      <h2>History</h2>
      {error && <p className="form-error">{error}</p>}

      {!hasDays && <p>No history yet.</p>}

      {days.map((day) => {
        const isOpen = expanded[day.date];
        const width = Math.min((day.percent / maxPercent) * 100, 100);
        return (
          <div className="day border-top" key={day.date}>
            <button
              type="button"
              className={`show-hide ${isOpen ? 'rotate' : 'revert'}`}
              onClick={() =>
                setExpanded((prev) => ({
                  ...prev,
                  [day.date]: !prev[day.date],
                }))
              }
              aria-expanded={isOpen}
            >
              <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" strokeWidth="2" />
              </svg>
            </button>

            <strong>{formatDayName(day.date)}</strong>

            <div className="history-graph-outer">
              <div
                className="history-graph-inner"
                style={{
                  width: `${width}%`,
                  backgroundColor: maxConsumptionSet ? (day.percent > 100 ? '#d00' : 'green') : '#666',
                }}
              />
            </div>

            <div className="details" style={{ display: isOpen ? 'block' : 'none' }}>
              {day.drinks} {day.drinks === 1 ? 'drink' : 'drinks'} &middot; {day.alcohol} oz. alcohol
              {maxConsumptionSet && (
                <>
                  {' '}&middot; {Math.round(day.percent)}% of max. goal.
                </>
              )}

              {day.servings.map((serving) => (
                <div key={serving.id}>
                  <br />
                  <strong>{serving.beverage?.name}</strong>
                  <br />
                  At {formatTime(serving.local_time)}
                </div>
              ))}
            </div>
          </div>
        );
      })}

      {!maxConsumptionSet && (
        <p>
          You can <Link to="/account">set a maximum daily target</Link>.
        </p>
      )}
    </div>
  );
}
