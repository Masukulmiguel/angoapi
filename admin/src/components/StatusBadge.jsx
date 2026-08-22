export default function StatusBadge({ status }) {
  const styles = {
    active: 'bg-emerald-50 text-emerald-700 border-emerald-200',
    pending: 'bg-amber-50 text-amber-700 border-amber-200',
    rejected: 'bg-red-50 text-red-700 border-red-200',
    inactive: 'bg-gray-50 text-gray-600 border-gray-200',
    expired: 'bg-orange-50 text-orange-700 border-orange-200',
    cancelled: 'bg-red-50 text-red-700 border-red-200',
    paid: 'bg-emerald-50 text-emerald-700 border-emerald-200',
    unpaid: 'bg-amber-50 text-amber-700 border-amber-200',
    failed: 'bg-red-50 text-red-700 border-red-200',
    approved: 'bg-emerald-50 text-emerald-700 border-emerald-200',
    suspended: 'bg-red-50 text-red-700 border-red-200',
  }

  const labels = {
    active: 'Active',
    pending: 'Pending',
    rejected: 'Rejected',
    inactive: 'Inactive',
    expired: 'Expired',
    cancelled: 'Cancelled',
    paid: 'Paid',
    unpaid: 'Unpaid',
    failed: 'Failed',
    approved: 'Approved',
    suspended: 'Suspended',
  }

  const key = status?.toLowerCase() || 'inactive'

  return (
    <span
      className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border ${
        styles[key] || styles.inactive
      }`}
    >
      <span
        className={`w-1.5 h-1.5 rounded-full mr-1.5 ${
          key === 'active' || key === 'approved' || key === 'paid'
            ? 'bg-emerald-500'
            : key === 'pending'
            ? 'bg-amber-500'
            : key === 'rejected' || key === 'failed' || key === 'cancelled' || key === 'suspended'
            ? 'bg-red-500'
            : 'bg-gray-400'
        }`}
      />
      {labels[key] || status}
    </span>
  )
}
