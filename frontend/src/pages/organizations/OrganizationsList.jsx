import { Link } from 'react-router-dom'
import { Button } from '@/components/ui/button'
import { Plus } from 'lucide-react'

export default function OrganizationsList() {
  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold">Organizations</h1>
          <p className="text-gray-500 mt-1">Manage your organizations</p>
        </div>
        <Link to="/organizations/new">
          <Button>
            <Plus className="mr-2 h-4 w-4" />
            Add Organization
          </Button>
        </Link>
      </div>
      {/* List implementation */}
    </div>
  )
}
