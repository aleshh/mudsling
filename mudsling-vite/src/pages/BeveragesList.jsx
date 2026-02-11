import { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { supabase } from '../supabaseClient';
import { alcoholOunces } from '../lib/consumption';

export default function BeveragesList() {
  const navigate = useNavigate();
  const [beverages, setBeverages] = useState([]);
  const [error, setError] = useState('');

  const loadBeverages = async () => {
    const { data, error: fetchError } = await supabase
      .from('beverages')
      .select('id, name, size_oz, strength_pct')
      .eq('deleted', false)
      .order('created_at', { ascending: false });

    if (fetchError) {
      setError(fetchError.message);
      return;
    }

    if (!data.length) {
      navigate('/beverages/new');
      return;
    }

    setBeverages(data);
  };

  useEffect(() => {
    loadBeverages();
  }, []);

  const handleDelete = async (beverageId) => {
    const confirmed = window.confirm('Are you sure?');
    if (!confirmed) return;

    const { count, error: servingsError } = await supabase
      .from('servings')
      .select('id', { count: 'exact', head: true })
      .eq('beverage_id', beverageId);

    if (servingsError) {
      setError(servingsError.message);
      return;
    }

    if ((count ?? 0) > 0) {
      const { error: updateError } = await supabase
        .from('beverages')
        .update({ deleted: true })
        .eq('id', beverageId);

      if (updateError) {
        setError(updateError.message);
        return;
      }
    } else {
      const { error: deleteError } = await supabase
        .from('beverages')
        .delete()
        .eq('id', beverageId);

      if (deleteError) {
        setError(deleteError.message);
        return;
      }
    }

    loadBeverages();
  };

  return (
    <div>
      <h2>Beverages</h2>
      {error && <p className="form-error">{error}</p>}
      {beverages.map((beverage) => (
        <div className="border-bottom" key={beverage.id}>
          <Link to={`/beverages/${beverage.id}`}>
            <h3>{beverage.name}</h3>
            {beverage.size_oz} oz., {beverage.strength_pct}% ({' '}
            {Math.round(alcoholOunces(beverage.size_oz, beverage.strength_pct) * 10) / 10} oz. alcohol)
          </Link>
          <br />
          <Link className="small-button" to={`/beverages/${beverage.id}/edit`}>
            Edit
          </Link>
          <button
            type="button"
            className="small-button delete-button"
            onClick={() => handleDelete(beverage.id)}
          >
            Delete
          </button>
        </div>
      ))}

      <p>
        <Link className="submit-button" to="/beverages/new">
          Add a beverage
        </Link>
      </p>
    </div>
  );
}
