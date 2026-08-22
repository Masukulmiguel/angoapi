import { useState, useEffect } from 'react'
import { CheckCircle, XCircle } from 'lucide-react'
import api from '../lib/axios'
import DataTable from '../components/DataTable'
import StatusBadge from '../components/StatusBadge'
import Modal from '../components/Modal'
import LoadingSpinner from '../components/LoadingSpinner'

export default function PendingPayments() {
  const [payments, setPayments] = useState([])
  const [loading, setLoading] = useState(true)
  const [page, setPage] = useState(1)
  const [totalPages, setTotalPages] = useState(1)
  const [modal, setModal] = useState({ open: false, payment: null, action: '' })
  const [processing, setProcessing] = useState(false)

  const fetchPayments = async () => {
    setLoading(true)
    try {
      const res = await api.get('/admin/payments', { params: { status: 'pending', page, limit: 20 } })
      setPayments(res.data.data.payments || res.data.data || [])
      setTotalPages(res.data.data.total_pages || 1)
    } catch (err) {
      console.error(err)
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => { fetchPayments() }, [page])

  const handleAction = async () => {
    setProcessing(true)
    try {
      await api.patch(`/admin/payments/${modal.payment.id}/${modal.action}`)
      setModal({ open: false, payment: null, action: '' })
      fetchPayments()
    } catch (err) {
      console.error(err)
    } finally {
      setProcessing(false)
    }
  }

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
    { header: 'Amount', render: (row) => <span className="font-semibold">${row.amount}</span> },
    { header: 'Plan', accessor: 'plan_name' },
    { header: 'Date', render: (row) => new Date(row.created_at).toLocaleDateString() },
    {
      header: 'Actions',
      render: (row) => (
        <div className="flex gap-2" onClick={(e) => e.stopPropagation()}>
          <button
            onClick={() => setModal({ open: true, payment: row, action: 'approve' })}
            className="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-colors"
          >
            <CheckCircle size={14} /> Approve
          </button>
          <button
            onClick={() => setModal({ open: true, payment: row, action: 'reject' })}
            className="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors"
          >
            <XCircle size={14} /> Reject
          </button>
        </div>
      ),
    },
  ]

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-bold text-gray-900">Pending Payments</h1>
        <p className="text-sm text-gray-500 mt-1">Review and process pending payments</p>
      </div>

      {loading ? (
        <LoadingSpinner className="mt-12" />
      ) : (
        <DataTable
          columns={columns}
          data={payments}
          page={page}
          totalPages={totalPages}
          onPageChange={setPage}
        />
      )}

      <Modal
        open={modal.open}
        onClose={() => setModal({ open: false, payment: null, action: '' })}
        title={`${modal.action === 'approve' ? 'Approve' : 'Reject'} Payment`}
      >
        <div className="space-y-4">
          <p className="text-sm text-gray-600">
            Are you sure you want to <strong>{modal.action}</strong> the payment of{' '}
            <strong>${modal.payment?.amount}</strong> from{' '}
            <strong>{modal.payment?.user?.name || modal.payment?.user_name}</strong>?
          </p>
          <div className="flex gap-3 justify-end">
            <button
              onClick={() => setModal({ open: false, payment: null, action: '' })}
              className="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
            >
              Cancel
            </button>
            <button
              onClick={handleAction}
              disabled={processing}
              className={`px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors disabled:opacity-50 ${
                modal.action === 'approve'
                  ? 'bg-emerald-600 hover:bg-emerald-700'
                  : 'bg-red-600 hover:bg-red-700'
              }`}
            >
              {processing ? 'Processing...' : modal.action === 'approve' ? 'Approve' : 'Reject'}
            </button>
          </div>
        </div>
      </Modal>
    </div>
  )
}
