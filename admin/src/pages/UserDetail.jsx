import { useState, useEffect } from 'react'
import { useParams, useNavigate } from 'react-router-dom'
import { ArrowLeft, Mail, Calendar, CreditCard, KeyRound, Activity } from 'lucide-react'
import api from '../lib/axios'
import StatusBadge from '../components/StatusBadge'
import LoadingSpinner from '../components/LoadingSpinner'
import DataTable from '../components/DataTable'

export default function UserDetail() {
  const { id } = useParams()
  const navigate = useNavigate()
  const [user, setUser] = useState(null)
  const [loading, setLoading] = useState(true)
  const [activeTab, setActiveTab] = useState('overview')

  useEffect(() => {
    const fetchUser = async () => {
      try {
        const res = await api.get(`/admin/users/${id}`)
        setUser(res.data.data)
      } catch (err) {
        console.error(err)
      } finally {
        setLoading(false)
      }
    }
    fetchUser()
  }, [id])

  if (loading) return <LoadingSpinner className="mt-20" size="lg" />
  if (!user) return <div className="text-center py-20 text-gray-500">User not found</div>

  const tabs = ['overview', 'subscriptions', 'payments', 'api-keys']

  const subscriptionColumns = [
    { header: 'Plan', accessor: 'plan_name' },
    { header: 'Status', render: (r) => <StatusBadge status={r.status} /> },
    { header: 'Start', render: (r) => new Date(r.start_date).toLocaleDateString() },
    { header: 'End', render: (r) => new Date(r.end_date).toLocaleDateString() },
  ]

  const paymentColumns = [
    { header: 'Amount', render: (r) => `$${r.amount}` },
    { header: 'Status', render: (r) => <StatusBadge status={r.status} /> },
    { header: 'Date', render: (r) => new Date(r.created_at).toLocaleDateString() },
    { header: 'Method', accessor: 'payment_method' },
  ]

  const apiKeyColumns = [
    { header: 'Name', accessor: 'name' },
    { header: 'Key', render: (r) => <code className="text-xs bg-gray-100 px-2 py-0.5 rounded">{r.key?.slice(0, 12)}...</code> },
    { header: 'Requests', accessor: 'requests_count' },
    { header: 'Status', render: (r) => <StatusBadge status={r.status} /> },
    { header: 'Created', render: (r) => new Date(r.created_at).toLocaleDateString() },
  ]

  return (
    <div className="space-y-6">
      <button
        onClick={() => navigate(-1)}
        className="flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition-colors"
      >
        <ArrowLeft size={16} /> Back
      </button>

      <div className="bg-white rounded-xl border border-gray-200 p-6">
        <div className="flex items-start gap-5">
          <div className="w-16 h-16 rounded-2xl bg-brand-100 text-brand-700 flex items-center justify-center text-2xl font-bold">
            {user.name?.charAt(0) || '?'}
          </div>
          <div className="flex-1">
            <div className="flex items-center gap-3">
              <h2 className="text-xl font-bold text-gray-900">{user.name}</h2>
              <StatusBadge status={user.status || user.subscription_status} />
            </div>
            <div className="flex flex-wrap gap-4 mt-2 text-sm text-gray-500">
              <span className="flex items-center gap-1.5"><Mail size={14} /> {user.email}</span>
              <span className="flex items-center gap-1.5"><Calendar size={14} /> Joined {new Date(user.created_at).toLocaleDateString()}</span>
            </div>
          </div>
        </div>
      </div>

      <div className="flex gap-1 border-b border-gray-200">
        {tabs.map((tab) => (
          <button
            key={tab}
            onClick={() => setActiveTab(tab)}
            className={`px-4 py-2.5 text-sm font-medium border-b-2 transition-colors ${
              activeTab === tab
                ? 'border-brand-600 text-brand-600'
                : 'border-transparent text-gray-500 hover:text-gray-700'
            }`}
          >
            {tab.replace(/-/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase())}
          </button>
        ))}
      </div>

      {activeTab === 'overview' && (
        <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div className="bg-white rounded-xl border border-gray-200 p-5">
            <div className="flex items-center gap-3 mb-2">
              <CreditCard className="text-brand-600" size={20} />
              <span className="text-sm font-medium text-gray-500">Current Plan</span>
            </div>
            <p className="text-lg font-bold text-gray-900">{user.plan || user.subscription?.plan_name || 'Free'}</p>
          </div>
          <div className="bg-white rounded-xl border border-gray-200 p-5">
            <div className="flex items-center gap-3 mb-2">
              <KeyRound className="text-brand-600" size={20} />
              <span className="text-sm font-medium text-gray-500">API Keys</span>
            </div>
            <p className="text-lg font-bold text-gray-900">{user.api_keys_count || user.api_keys?.length || 0}</p>
          </div>
          <div className="bg-white rounded-xl border border-gray-200 p-5">
            <div className="flex items-center gap-3 mb-2">
              <Activity className="text-brand-600" size={20} />
              <span className="text-sm font-medium text-gray-500">Total Requests</span>
            </div>
            <p className="text-lg font-bold text-gray-900">{(user.total_requests || 0).toLocaleString()}</p>
          </div>
        </div>
      )}

      {activeTab === 'subscriptions' && (
        <DataTable columns={subscriptionColumns} data={user.subscriptions || []} emptyMessage="No subscriptions" />
      )}
      {activeTab === 'payments' && (
        <DataTable columns={paymentColumns} data={user.payments || []} emptyMessage="No payments" />
      )}
      {activeTab === 'api-keys' && (
        <DataTable columns={apiKeyColumns} data={user.api_keys || []} emptyMessage="No API keys" />
      )}
    </div>
  )
}
