import { useState } from 'react'
import { Outlet, Link, useLocation, useNavigate } from 'react-router-dom'
import { useAuthStore } from '@/store/authStore'
import { 
  LayoutDashboard, 
  Building2, 
  FolderKanban, 
  ClipboardList, 
  Users, 
  FileQuestion,
  BarChart3,
  Menu,
  X,
  LogOut,
  ChevronRight
} from 'lucide-react'
import { Button } from '@/components/ui/button'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'

const navigation = [
  { name: 'Dashboard', href: '/', icon: LayoutDashboard },
  { name: 'Organizations', href: '/organizations', icon: Building2 },
  { name: 'Programs', href: '/programs', icon: FolderKanban },
  { name: 'Activities', href: '/activities', icon: ClipboardList },
  { name: 'Questionnaires', href: '/questionnaires', icon: FileQuestion },
  { name: 'Participants', href: '/participants', icon: Users },
  { name: 'Analytics', href: '/analytics', icon: BarChart3 },
]

export default function MainLayout() {
  const [sidebarOpen, setSidebarOpen] = useState(true)
  const location = useLocation()
  const navigate = useNavigate()
  const { user, logout } = useAuthStore()

  const handleLogout = () => {
    logout()
    navigate('/login')
  }

  const getBreadcrumbs = () => {
    const paths = location.pathname.split('/').filter(Boolean)
    return paths.map((path, index) => ({
      name: path.charAt(0).toUpperCase() + path.slice(1),
      href: '/' + paths.slice(0, index + 1).join('/'),
      current: index === paths.length - 1,
    }))
  }

  const breadcrumbs = getBreadcrumbs()

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Sidebar */}
      <aside
        className={`fixed top-0 left-0 z-40 h-screen transition-transform bg-white border-r border-gray-200 ${
          sidebarOpen ? 'w-64' : 'w-20'
        }`}
      >
        <div className="h-full px-3 py-4 overflow-y-auto">
          <div className="flex items-center justify-between mb-8">
            {sidebarOpen && (
              <h1 className="text-xl font-bold text-primary">QSights 2.0</h1>
            )}
            <Button
              variant="ghost"
              size="icon"
              onClick={() => setSidebarOpen(!sidebarOpen)}
            >
              {sidebarOpen ? <X className="h-5 w-5" /> : <Menu className="h-5 w-5" />}
            </Button>
          </div>

          <nav className="space-y-2">
            {navigation.map((item) => {
              const Icon = item.icon
              const isActive = location.pathname === item.href || 
                (item.href !== '/' && location.pathname.startsWith(item.href))
              
              return (
                <Link
                  key={item.name}
                  to={item.href}
                  className={`flex items-center gap-3 px-3 py-2 rounded-lg transition-colors ${
                    isActive
                      ? 'bg-primary text-white'
                      : 'text-gray-700 hover:bg-gray-100'
                  }`}
                >
                  <Icon className="h-5 w-5 flex-shrink-0" />
                  {sidebarOpen && <span>{item.name}</span>}
                </Link>
              )
            })}
          </nav>
        </div>
      </aside>

      {/* Main Content */}
      <div className={`${sidebarOpen ? 'ml-64' : 'ml-20'} transition-all`}>
        {/* Top Bar */}
        <header className="bg-white border-b border-gray-200 sticky top-0 z-30">
          <div className="px-6 py-4">
            <div className="flex items-center justify-between">
              {/* Breadcrumbs */}
              <nav className="flex" aria-label="Breadcrumb">
                <ol className="flex items-center space-x-2">
                  <li>
                    <Link to="/" className="text-gray-500 hover:text-gray-700">
                      Home
                    </Link>
                  </li>
                  {breadcrumbs.map((crumb) => (
                    <li key={crumb.href} className="flex items-center">
                      <ChevronRight className="h-4 w-4 text-gray-400 mx-2" />
                      <Link
                        to={crumb.href}
                        className={`${
                          crumb.current
                            ? 'text-gray-900 font-medium'
                            : 'text-gray-500 hover:text-gray-700'
                        }`}
                      >
                        {crumb.name}
                      </Link>
                    </li>
                  ))}
                </ol>
              </nav>

              {/* User Menu */}
              <div className="flex items-center gap-4">
                <div className="text-right">
                  <p className="text-sm font-medium">{user?.name}</p>
                  <p className="text-xs text-gray-500">{user?.role}</p>
                </div>
                <Avatar>
                  <AvatarImage src={user?.avatar} />
                  <AvatarFallback>
                    {user?.name?.charAt(0).toUpperCase()}
                  </AvatarFallback>
                </Avatar>
                <Button variant="ghost" size="icon" onClick={handleLogout}>
                  <LogOut className="h-5 w-5" />
                </Button>
              </div>
            </div>
          </div>
        </header>

        {/* Page Content */}
        <main className="p-6">
          <Outlet />
        </main>
      </div>
    </div>
  )
}
