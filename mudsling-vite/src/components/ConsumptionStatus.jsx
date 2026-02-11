import { useEffect, useState } from 'react';
import { supabase } from '../supabaseClient';
import { useAuth } from '../contexts/AuthContext';
import { alcoholOunces, percentOfMax, roundAlcohol } from '../lib/consumption';
import { startOfDay } from '../lib/date';

export default function ConsumptionStatus() {
  const { user, profile } = useAuth();
  const [stats, setStats] = useState({ count: 0, alcohol: 0, percent: 0 });

  useEffect(() => {
    if (!user) return;

    const load = async () => {
      const start = startOfDay(new Date());
      const { data, error } = await supabase
        .from('servings')
        .select('id, local_time, beverage:beverages (size_oz, strength_pct)')
        .gte('local_time', start.toISOString());

      if (error) {
        console.error('Failed to load today stats', error);
        return;
      }

      const count = data.length;
      const alcohol = data.reduce(
        (total, serving) =>
          total + alcoholOunces(serving.beverage?.size_oz ?? 0, serving.beverage?.strength_pct ?? 0),
        0,
      );

      const roundedAlcohol = roundAlcohol(alcohol);
      const max = Number(profile?.maximum_consumption ?? 0);
      const percent = percentOfMax(alcohol, max);

      setStats({ count, alcohol: roundedAlcohol, percent });
    };

    load();

    const channel = supabase
      .channel('servings-status')
      .on(
        'postgres_changes',
        { event: '*', schema: 'public', table: 'servings', filter: `user_id=eq.${user.id}` },
        () => load(),
      )
      .subscribe();

    return () => {
      supabase.removeChannel(channel);
    };
  }, [user, profile?.maximum_consumption]);

  const maxSet = Number(profile?.maximum_consumption ?? 0) > 0;

  return (
    <div
      className={`consumption-graph${maxSet ? '' : ' consumption-graph-no-max-consumption'}`}
    >
      <div
        className={`consumption-graph-today${stats.percent > 100 ? ' consumption-graph-over-limit' : ''}`}
        style={{ width: `${Math.min(stats.percent, 100)}%` }}
      />
      <div className="consumption-graph-message">
        {stats.count > 0 ? (
          <>
            Today: {stats.count} {stats.count === 1 ? 'drink' : 'drinks'}, {stats.alcohol} oz. alcohol
          </>
        ) : (
          <>No drinks yet today</>
        )}
      </div>
    </div>
  );
}
