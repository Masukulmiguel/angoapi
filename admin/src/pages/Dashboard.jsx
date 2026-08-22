import { useEffect } from 'react'
import { Users, UserCheck, Clock, CreditCard, Activity, AlertTriangle } from 'lucide-react'
import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, PieChart, Pie, Cell } from 'recharts'
import StatCard from '../components/StatCard'
import LoadingSpinner from '../components/LoadingSpinner'
import { useDashboardStore } from '../stores/dashboardStore'

const CHART_COLORS = ['#3378ff', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4']

export default function Dashboard() {
  const { stats, charts, loading, fetchDashboard } = useDashboardStore()

  useEffect(() => { fetchDashboard() }, [fetchDashboard])

  if (loading) return <LoadingSpinner className="mt-20" size="lg" />

  const statCards = [
    { title: 'Total Clients', value: stats?.total_users ?? 0, icon: Users, color: 'brand', change: 12 },
    { title: 'Active', value: stats?.active_users ?? 0, icon: UserCheck, color: 'green', change: 8 },
    { title: 'Pending', value: stats?.pending_users ?? 0, icon: Clock, color: 'yellow', change: -3 },
    { title: 'Revenue', value: `$${(stats?.revenue ?? 0).toLocaleString()}`, icon: CreditCard, color: 'blue', change: 15 },
    { title: 'API Requests', value: (stats?.api_requests ?? 0).toLocaleString(), icon: Activity, color: 'purple', change: 22 },
    { title: 'Errors', value: stats?.errors ?? 0, icon: AlertTriangle, color: 'red', change: -5 },
  ]

  const requestData = charts?.requests_over_time || [
    { date: 'Jan', requests: 4000 }, { date: 'Feb', requests: 3000 },
    { date: 'Mar', requests: 5000 }, { date: 'Apr', requests: 4500 },
    { date: 'May', requests: 6000 }, { date: 'Jun', requests: 5500 },
  ]

  const planData = charts?.plan_distribution || [
    { name: 'Free', value: 400 }, { name: 'Basic', value: 300 },
    { name: 'Pro', value: 200 }, { name: 'Enterprise', value: 100 },
  ]

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p className="text-sm text-gray-500 mt-1">Overview of your platform</p>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        {statCards.map((card) => (
          <StatCard key={card.title} {...card} />
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-6">
          <h3 className="text-sm font-semibold text-gray-900 mb-4">API Requests Over Time</h3>
          <ResponsiveContainer width="100%" height={300}>
            <LineChart data={requestData}>
              <CartesianGrid strokeDasharray="3 3" stroke="#f1f5f9" />
              <XAxis dataKey="date" tick={{ fontSize: 12 }} stroke="#94a3b8" />
              <YAxis tick={{ fontSize: 12 }} stroke="#94a3b8" />
              <Tooltip
                contentStyle={{
                  borderRadius: '8px',
                  border: '1px solid #e2e8f0',
                  boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.05)',
                }}
              />
              <Line
                type="monotone"
                dataKey="requests"
                stroke="#3378ff"
                strokeWidth={2}
                dot={{ fill: '#3378ff', strokeWidth: 2, r: 4 }}
                activeDot={{ r: 6 }}
              />
            </LineChart>
          </ResponsiveContainer>
        </div>

        <div className="bg-white rounded-xl border border-gray-200 p-6">
          <h3 className="text-sm font-semibold text-gray-900 mb-4">Plan Distribution</h3>
          <ResponsiveContainer width="100%" height={300}>
            <PieChart>
              <Pie
                data={planData}
                cx="50%"
                cy="50%"
                innerRadius={60}
                outerRadius={100}
                paddingAngle={4}
                dataKey="value"
              >
                {planData.map((_, i) => (
                  <Cell key={i} fill={CHART_COLORS[i % CHART_COLORS.length]} />
                ))}
              </Pie>
              <Tooltip
                contentStyle={{
                  borderRadius: '8px',
                  border: '1px solid #e2e8f0',
                }}
              />
            </PieChart>
          </ResponsiveContainer>
          <div className="flex flex-wrap gap-3 mt-2 justify-center">
            {planData.map((item, i) => (
              <div key={item.name} className="flex items-center gap-1.5 text-xs text-gray-600">
                <span
                  className="w-2.5 h-2.5 rounded-full"
                  style={{ background: CHART_COLORS[i % CHART_COLORS.length] }}
                />
                {item.name}
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  )
}
