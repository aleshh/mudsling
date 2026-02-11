import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { supabase } from '../supabaseClient';
import { alcoholOunces, roundAlcohol } from '../lib/consumption';
import { formatRelative } from '../lib/date';

export default function BeverageDetail() {
  const { id } = useParams();
  const [beverage, setBeverage] = useState(null);
  const [error, setError] = useState('');

  useEffect(() => {
    const load = async () => {
      const { data, error: fetchError } = await supabase
        .from('beverages')
        .select('id, name, category, size_oz, strength_pct, servings (id, local_time)')
        .eq('id', id)
        .order('local_time', { ascending: false, foreignTable: 'servings' })
        .maybeSingle();

      if (fetchError) {
        setError(fetchError.message);
        return;
      }

      setBeverage(data);
    };

    load();
  }, [id]);

  if (error) {
    return <p className="form-error">{error}</p>;
  }

  if (!beverage) {
    return <p>Loading...</p>;
  }

  const alcohol = roundAlcohol(alcoholOunces(beverage.size_oz, beverage.strength_pct));

  return (
    <div>
      <h2>{beverage.name}</h2>
      <p className="border-bottom">
        <strong>Category:</strong> {beverage.category}
        <br />
        <strong>Size:</strong> {beverage.size_oz} oz.
        <br />
        <strong>Alcohol:</strong> {beverage.strength_pct}%
        <br />({alcohol} oz. of alcohol)
      </p>

      {beverage.servings?.length > 0 && <h3>Servings</h3>}
      <p>
        {beverage.servings?.map((serving) => (
          <span key={serving.id}>
            Serving: {formatRelative(serving.local_time)}
            <br />
          </span>
        ))}
      </p>
    </div>
  );
}
