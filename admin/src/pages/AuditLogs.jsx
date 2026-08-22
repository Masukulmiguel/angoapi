import { useState, useEffect } from 'react'
import api from '../lib/axios'
import DataTable from '../components/DataTable'
import LoadingSpinner from '../components/LoadingSpinner'

export default function AuditLogs() {
  const [logs, setLogs] = useState([])
  const [loading, setLoading] = useState(true)
  const [page, setPage] = useState(1)
  const [totalPages, setTotalPages] = useState(1)

  const fetchLogs = async () => {
    setLoading(true)
    try {
      const res = await api.get('/admin/audit-logs', { params: { page, limit: 20 } })
      setLogs(res.data.data.logs || res.data.data || [])
      setTotalPages(res.data.data.total_pages || 1)
    } catch (err) {
      console.error(err)
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => { fetchLogs() }, [page])

  const actionColors = {
    create: 'text-emerald-600',
    update: 'text-blue-600',
    delete: 'text-red-600',
    login: 'text-purple-600',
    approve: 'text-emerald-600',
    reject: 'text-red-600',
  }

  const columns = [
    {
      header: 'User',
      render: (row) => (
        <span className="font-medium text-gray-900">{row.user?.name || row.user_name || 'System'}</span>
      ),
    },
    {
      header: 'Action',
      render: (row) => (
        <span className={`font-medium capitalize ${actionColors[row.action?.toLowerCase()] || 'text-gray-700'}`}>
          {row.action}
        </span>
      ),
    },
    { header: 'Resource', accessor: 'resource' },
    {
      header: 'Details',
      render: (row) => (
        <span className="text-gray-500 truncate max-w-xs block">{row.details || row.description || '-'}</span>
      ),
    },
    { header: 'IP Address', accessor: 'ip_address' },
    {
      header: 'Time',
      render: (row) => (
        <span className="text-gray-500 whitespace-nowrap">{new Date(row.created_at).toLocaleString()}</span>
      ),
    },
  ]

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-bold text-gray-900">Audit Logs</h1>
        <p className="text-sm text-gray-500 mt-1">Track all platform activity</p>
      </div>

      {loading ? (
        <LoadingSpinner className="mt-12" />
      ) : (
        <DataTable
          columns={columns}
          data={logs}
          page={page}
          totalPages={totalPages}
          onPageChange={setPage}
        />
      )}
    </div>
  )
}
