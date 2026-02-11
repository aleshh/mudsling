import { useEffect, useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { supabase } from '../supabaseClient';

const categories = ['Beer', 'Wine', 'Liquor', 'Cocktail'];

export default function BeverageForm() {
  const navigate = useNavigate();
  const { id } = useParams();
  const isEdit = Boolean(id);
  const [formState, setFormState] = useState({
    category: 'Beer',
    name: '',
    size: '',
    strength: '',
  });
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    const load = async () => {
      if (!isEdit) return;
      const { data, error: fetchError } = await supabase
        .from('beverages')
        .select('id, name, category, size_oz, strength_pct')
        .eq('id', id)
        .maybeSingle();

      if (fetchError) {
        setError(fetchError.message);
        return;
      }

      if (data) {
        setFormState({
          category: data.category,
          name: data.name,
          size: data.size_oz,
          strength: data.strength_pct,
        });
      }
    };

    load();
  }, [id, isEdit]);

  const updateField = (field) => (event) => {
    setFormState((prev) => ({ ...prev, [field]: event.target.value }));
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    setError('');
    setLoading(true);

    const sizeValue = Number(formState.size);
    const strengthValue = Number(formState.strength);

    if (!formState.name.trim()) {
      setError('Name is required.');
      setLoading(false);
      return;
    }

    if (!formState.category) {
      setError('Category is required.');
      setLoading(false);
      return;
    }

    if (!sizeValue || Number.isNaN(sizeValue)) {
      setError('Size must be a number.');
      setLoading(false);
      return;
    }

    if (Number.isNaN(strengthValue)) {
      setError('Strength must be a number.');
      setLoading(false);
      return;
    }

    const payload = {
      name: formState.name.trim(),
      category: formState.category,
      size_oz: sizeValue,
      strength_pct: strengthValue,
    };

    if (isEdit) {
      const { error: updateError } = await supabase
        .from('beverages')
        .update(payload)
        .eq('id', id);

      if (updateError) {
        setError(updateError.message);
        setLoading(false);
        return;
      }

      navigate('/beverages');
      return;
    }

    const action = event.nativeEvent.submitter?.value ?? 'saveAndDrink';

    const { data: created, error: createError } = await supabase
      .from('beverages')
      .insert(payload)
      .select('id')
      .single();

    if (createError) {
      setError(createError.message);
      setLoading(false);
      return;
    }

    if (action === 'saveAndDrink' && created?.id) {
      await supabase.from('servings').insert({
        beverage_id: created.id,
        local_time: new Date().toISOString(),
      });

      await supabase
        .from('beverages')
        .update({ updated_at: new Date().toISOString() })
        .eq('id', created.id);
    }

    navigate('/drink');
  };

  return (
    <div>
      <h2>{isEdit ? 'Edit Beverage' : 'What ya drinking?'}</h2>
      <form className="input-form" onSubmit={handleSubmit}>
        <label htmlFor="category">Category</label>
        <select id="category" value={formState.category} onChange={updateField('category')}>
          {categories.map((category) => (
            <option key={category} value={category}>
              {category}
            </option>
          ))}
        </select>

        <label htmlFor="name">Name</label>
        <input
          id="name"
          type="text"
          value={formState.name}
          onChange={updateField('name')}
          placeholder="Name of Drink"
        />

        <label htmlFor="size">Size</label>
        <input
          id="size"
          type="number"
          step="0.1"
          value={formState.size}
          onChange={updateField('size')}
          placeholder="Ounces"
        />

        <label htmlFor="strength">Strength</label>
        <input
          id="strength"
          type="number"
          step="0.1"
          value={formState.strength}
          onChange={updateField('strength')}
          placeholder="% alcohol"
        />

        {error && <p className="form-error">{error}</p>}

        {isEdit ? (
          <button type="submit" className="btn" disabled={loading}>
            {loading ? 'Saving...' : 'Save'}
          </button>
        ) : (
          <div className="button-row">
            <button type="submit" className="btn" value="saveAndDrink" disabled={loading}>
              {loading ? 'Saving...' : 'Save and Drink one!'}
            </button>
            <button
              type="submit"
              className="btn btn-small btn-secondary"
              value="saveDontDrink"
              disabled={loading}
            >
              Just save for later
            </button>
          </div>
        )}
      </form>
    </div>
  );
}
