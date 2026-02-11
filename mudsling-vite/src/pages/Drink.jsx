import { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { supabase } from '../supabaseClient';

export default function Drink() {
  const navigate = useNavigate();
  const [beverages, setBeverages] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  const loadBeverages = async () => {
    setLoading(true);
    const { data, error: fetchError } = await supabase
      .from('beverages')
      .select('id, name, size_oz, strength_pct')
      .eq('deleted', false)
      .order('updated_at', { ascending: false });

    if (fetchError) {
      setError(fetchError.message);
      setLoading(false);
      return;
    }

    if (!data.length) {
      navigate('/beverages/new');
      return;
    }

    setBeverages(data);
    setLoading(false);
  };

  useEffect(() => {
    loadBeverages();
  }, []);

  const handleAddServing = async (beverageId) => {
    setError('');
    const { error: insertError } = await supabase.from('servings').insert({
      beverage_id: beverageId,
      local_time: new Date().toISOString(),
    });

    if (insertError) {
      setError(insertError.message);
      return;
    }

    await supabase
      .from('beverages')
      .update({ updated_at: new Date().toISOString() })
      .eq('id', beverageId);
  };

  if (loading) {
    return <p>Loading...</p>;
  }

  return (
    <div>
      <h2>What ya drinking?</h2>
      {error && <p className="form-error">{error}</p>}
      {beverages.map((beverage) => (
        <button
          key={beverage.id}
          type="button"
          className="add-serving-button"
          onClick={() => handleAddServing(beverage.id)}
        >
          <h3>{beverage.name}</h3>
          ({beverage.size_oz}oz., {beverage.strength_pct}%)
        </button>
      ))}

      <div>
        <Link to="/beverages/new" className="add-serving-button">
          <h3>Something else?</h3>
          Add a new beverage
        </Link>
      </div>
    </div>
  );
}
