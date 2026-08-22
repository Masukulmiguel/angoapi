import { useState, useEffect } from 'react'
import api from '../lib/axios'
import DataTable from '../components/DataTable'
import StatusBadge from '../components/StatusBadge'
import LoadingSpinner from '../components/LoadingSpinner'

export default function ApiKeys() {
  const [keys, setKeys] = useState([])
  const [loading, setLoading] = useState(true)
  const [page, setPage] = useState(1)
  const [totalPages, setTotalPages] = useState(1)

  const fetchKeys = async () => {
    setLoading(true)
    try {
      const res = await api.get('/admin/api-keys', { params: { page, limit: 20 } })
      setKeys(res.data.data.api_keys || res.data.data || [])
      setTotalPages(res.data.data.total_pages || 1)
    } catch (err) {
      console.error(err)
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => { fetchKeys() }, [page])

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
    { header: 'Name', accessor: 'name' },
    {
      header: 'Key',
      render: (row) => (
        <code className="text-xs bg-gray-100 px-2 py-1 rounded font-mono">
          {row.key?.slice(0, 16)}...
        </code>
      ),
    },
    { header: 'Requests', accessor: 'requests_count' },
    { header: 'Status', render: (row) => <StatusBadge status={row.status} /> },
    { header: 'Created', render: (row) => new Date(row.created_at).toLocaleDateString() },
  ]

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-bold text-gray-900">API Keys</h1>
        <p className="text-sm text-gray-500 mt-1">All API keys across the platform</p>
      </div>

      {loading ? (
        <LoadingSpinner className="mt-12" />
      ) : (
        <DataTable
          columns={columns}
          data={keys}
          page={page}
          totalPages={totalPages}
          onPageChange={setPage}
        />
      )}
    </div>
  )
}
