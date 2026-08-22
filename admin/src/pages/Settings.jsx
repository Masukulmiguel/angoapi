import { useState, useEffect } from 'react'
import { Save } from 'lucide-react'
import api from '../lib/axios'
import LoadingSpinner from '../components/LoadingSpinner'

export default function Settings() {
  const [settings, setSettings] = useState({
    platform_name: 'AngoAPI',
    support_email: '',
    maintenance_mode: false,
    registration_enabled: true,
    max_requests_per_day: 1000,
    default_rate_limit: 60,
  })
  const [loading, setLoading] = useState(true)
  const [saving, setSaving] = useState(false)
  const [saved, setSaved] = useState(false)

  useEffect(() => {
    const fetchSettings = async () => {
      try {
        const res = await api.get('/admin/settings')
        setSettings((prev) => ({ ...prev, ...res.data.data }))
      } catch (err) {
        console.error(err)
      } finally {
        setLoading(false)
      }
    }
    fetchSettings()
  }, [])

  const handleSave = async (e) => {
    e.preventDefault()
    setSaving(true)
    setSaved(false)
    try {
      await api.put('/admin/settings', settings)
      setSaved(true)
      setTimeout(() => setSaved(false), 3000)
    } catch (err) {
      console.error(err)
    } finally {
      setSaving(false)
    }
  }

  const update = (key, value) => setSettings((prev) => ({ ...prev, [key]: value }))

  if (loading) return <LoadingSpinner className="mt-20" size="lg" />

  return (
    <div className="space-y-6 max-w-2xl">
      <div>
        <h1 className="text-2xl font-bold text-gray-900">Settings</h1>
        <p className="text-sm text-gray-500 mt-1">Configure platform settings</p>
      </div>

      <form onSubmit={handleSave} className="space-y-6">
        <div className="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
          <h3 className="text-sm font-semibold text-gray-900">General</h3>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Platform Name</label>
            <input
              type="text"
              value={settings.platform_name}
              onChange={(e) => update('platform_name', e.target.value)}
              className="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
            />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Support Email</label>
            <input
              type="email"
              value={settings.support_email}
              onChange={(e) => update('support_email', e.target.value)}
              className="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
              placeholder="support@angoapi.com"
            />
          </div>
        </div>

        <div className="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
          <h3 className="text-sm font-semibold text-gray-900">API Limits</h3>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Max Requests Per Day</label>
            <input
              type="number"
              value={settings.max_requests_per_day}
              onChange={(e) => update('max_requests_per_day', parseInt(e.target.value) || 0)}
              className="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
            />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Default Rate Limit (per minute)</label>
            <input
              type="number"
              value={settings.default_rate_limit}
              onChange={(e) => update('default_rate_limit', parseInt(e.target.value) || 0)}
              className="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
            />
          </div>
        </div>

        <div className="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
          <h3 className="text-sm font-semibold text-gray-900">Toggles</h3>
          <label className="flex items-center justify-between cursor-pointer">
            <div>
              <div className="text-sm font-medium text-gray-900">Maintenance Mode</div>
              <div className="text-xs text-gray-500">Disable public API access</div>
            </div>
            <button
              type="button"
              onClick={() => update('maintenance_mode', !settings.maintenance_mode)}
              className={`relative w-11 h-6 rounded-full transition-colors ${
                settings.maintenance_mode ? 'bg-brand-600' : 'bg-gray-200'
              }`}
            >
              <span
                className={`absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform ${
                  settings.maintenance_mode ? 'translate-x-5' : ''
                }`}
              />
            </button>
          </label>
          <label className="flex items-center justify-between cursor-pointer">
            <div>
              <div className="text-sm font-medium text-gray-900">Registration Enabled</div>
              <div className="text-xs text-gray-500">Allow new user signups</div>
            </div>
            <button
              type="button"
              onClick={() => update('registration_enabled', !settings.registration_enabled)}
              className={`relative w-11 h-6 rounded-full transition-colors ${
                settings.registration_enabled ? 'bg-brand-600' : 'bg-gray-200'
              }`}
            >
              <span
                className={`absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform ${
                  settings.registration_enabled ? 'translate-x-5' : ''
                }`}
              />
            </button>
          </label>
        </div>

        <div className="flex items-center gap-3">
          <button
            type="submit"
            disabled={saving}
            className="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg transition-colors disabled:opacity-50 shadow-lg shadow-brand-600/25"
          >
            <Save size={16} /> {saving ? 'Saving...' : 'Save Changes'}
          </button>
          {saved && <span className="text-sm text-emerald-600 font-medium">Saved successfully</span>}
        </div>
      </form>
    </div>
  )
}
