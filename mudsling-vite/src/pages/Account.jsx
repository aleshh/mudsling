import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { supabase } from '../supabaseClient';
import { useAuth } from '../contexts/AuthContext';

export default function Account() {
  const { user, profile, refreshProfile } = useAuth();
  const [maximumConsumption, setMaximumConsumption] = useState('');
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    if (profile) {
      setMaximumConsumption(profile.maximum_consumption ?? '');
    }
  }, [profile]);

  const handleUpdate = async (event) => {
    event.preventDefault();
    setError('');
    setMessage('');
    setSaving(true);

    const value = Number(maximumConsumption);
    if (Number.isNaN(value) || value < 0) {
      setError('Please enter a valid number.');
      setSaving(false);
      return;
    }

    const { error: updateError } = await supabase
      .from('profiles')
      .update({ maximum_consumption: value })
      .eq('id', user.id);

    if (updateError) {
      setError(updateError.message);
      setSaving(false);
      return;
    }

    await refreshProfile(user.id);
    setMessage('Saved!');
    setSaving(false);
  };

  const handleLogout = async () => {
    await supabase.auth.signOut();
  };

  return (
    <div>
      <h2>{profile?.display_name ?? user.email}</h2>

      <form className="input-form border-bottom" onSubmit={handleUpdate}>
        <label htmlFor="maximumConsumption">Daily Maximum Alcohol Target (oz.)</label>
        <input
          id="maximumConsumption"
          type="number"
          step="0.1"
          value={maximumConsumption}
          onChange={(event) => setMaximumConsumption(event.target.value)}
          placeholder="in Oz"
        />
        <p style={{ marginBottom: 0 }}>
          For example, three 12oz. beers with 5% alcohol:
          <br />3 × 12 × .05 = 1.8
        </p>

        {error && <p className="form-error">{error}</p>}
        {message && <p className="form-success">{message}</p>}

        <button type="submit" className="submit-button" disabled={saving}>
          {saving ? 'Saving...' : 'Submit'}
        </button>
      </form>

      <div className="border-bottom">
        <h2>Beverages</h2>
        <p>
          <Link to="/beverages">View, edit, or delete your beverages</Link>
        </p>
      </div>

      <h2>Logged in</h2>
      <p>
        <button type="button" className="submit-button" onClick={handleLogout}>
          Logout
        </button>
      </p>
    </div>
  );
}
