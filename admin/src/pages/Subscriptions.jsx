import { useState, useEffect } from 'react'
import api from '../lib/axios'
import DataTable from '../components/DataTable'
import StatusBadge from '../components/StatusBadge'
import LoadingSpinner from '../components/LoadingSpinner'

export default function Subscriptions() {
  const [subscriptions, setSubscriptions] = useState([])
  const [loading, setLoading] = useState(true)
  const [page, setPage] = useState(1)
  const [totalPages, setTotalPages] = useState(1)
  const [statusFilter, setStatusFilter] = useState('')

  const fetchSubscriptions = async () => {
    setLoading(true)
    try {
      const params = { page, limit: 20 }
      if (statusFilter) params.status = statusFilter
      const res = await api.get('/admin/subscriptions', { params })
      setSubscriptions(res.data.data.subscriptions || res.data.data || [])
      setTotalPages(res.data.data.total_pages || 1)
    } catch (err) {
      console.error(err)
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => { fetchSubscriptions() }, [page, statusFilter])

  const columns = [
    {
      header: 'User',
      render: (row) => (
        <div>
          <div className="font-medium text-gray-900">{row.user?.name || row.user_name}</div>
          <div className="text-xs text-gray-500">{row.user?.email || row.user_email}</div>
        </div>
      ),
    },
    { header: 'Plan', accessor: 'plan_name' },
    { header: 'Status', render: (row) => <StatusBadge status={row.status} /> },
    { header: 'Amount', render: (row) => `$${row.amount || row.price || '-'}` },
    { header: 'Start', render: (row) => new Date(row.start_date || row.created_at).toLocaleDateString() },
    { header: 'End', render: (row) => row.end_date ? new Date(row.end_date).toLocaleDateString() : '-' },
  ]

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Subscriptions</h1>
          <p className="text-sm text-gray-500 mt-1">All platform subscriptions</p>
        </div>
        <select
          value={statusFilter}
          onChange={(e) => { setStatusFilter(e.target.value); setPage(1) }}
          className="px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20"
        >
          <option value="">All Status</option>
          <option value="active">Active</option>
          <option value="pending">Pending</option>
          <option value="expired">Expired</option>
          <option value="cancelled">Cancelled</option>
        </select>
      </div>

      {loading ? (
        <LoadingSpinner className="mt-12" />
      ) : (
        <DataTable
          columns={columns}
          data={subscriptions}
          page={page}
          totalPages={totalPages}
          onPageChange={setPage}
        />
      )}
    </div>
  )
}
