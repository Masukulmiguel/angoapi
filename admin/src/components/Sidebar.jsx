import { useState } from 'react'
import { Link, useLocation } from 'react-router-dom'
import {
  LayoutDashboard, Users, CreditCard, KeyRound, Settings, FileText,
  ChevronLeft, ChevronRight, Bell, LogOut, Search, Menu,
  Clock, Shield
} from 'lucide-react'
import { useAuthStore } from '../stores/authStore'

const navItems = [
  { path: '/', label: 'Dashboard', icon: LayoutDashboard },
  { path: '/users', label: 'Users', icon: Users },
  { path: '/pending-payments', label: 'Pending Payments', icon: Clock },
  { path: '/subscriptions', label: 'Subscriptions', icon: CreditCard },
  { path: '/api-keys', label: 'API Keys', icon: KeyRound },
  { path: '/audit-logs', label: 'Audit Logs', icon: FileText },
  { path: '/settings', label: 'Settings', icon: Settings },
]

export default function Sidebar({ collapsed, onToggle }) {
  const location = useLocation()

  return (
    <aside
      className={`fixed left-0 top-0 h-screen bg-dark-sidebar text-white flex flex-col z-40 transition-all duration-300 ${
        collapsed ? 'w-[72px]' : 'w-64'
      }`}
    >
      <div className="flex items-center justify-between px-4 h-16 border-b border-white/10">
        {!collapsed && (
          <div className="flex items-center gap-2">
            <Shield className="w-7 h-7 text-brand-400" />
            <span className="text-lg font-bold tracking-tight">AngoAPI</span>
          </div>
        )}
        {collapsed && <Shield className="w-7 h-7 text-brand-400 mx-auto" />}
        <button
          onClick={onToggle}
          className="hidden lg:flex p-1.5 rounded-md hover:bg-white/10 transition-colors"
        >
          {collapsed ? <ChevronRight size={16} /> : <ChevronLeft size={16} />}
        </button>
      </div>

      <nav className="flex-1 py-4 px-3 space-y-1 overflow-y-auto sidebar-scroll">
        {navItems.map((item) => {
          const Icon = item.icon
          const active = location.pathname === item.path ||
            (item.path !== '/' && location.pathname.startsWith(item.path))
          return (
            <Link
              key={item.path}
              to={item.path}
              className={`flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all ${
                active
                  ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/25'
                  : 'text-gray-300 hover:bg-white/10 hover:text-white'
              } ${collapsed ? 'justify-center' : ''}`}
              title={collapsed ? item.label : undefined}
            >
              <Icon size={20} />
              {!collapsed && <span>{item.label}</span>}
            </Link>
          )
        })}
      </nav>

      {!collapsed && (
        <div className="p-4 border-t border-white/10">
          <div className="text-xs text-gray-400 text-center">v1.0.0</div>
        </div>
      )}
    </aside>
  )
}
