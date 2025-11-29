import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import * as z from 'zod'
import { authAPI } from '@/lib/api'
import { useAuthStore } from '@/store/authStore'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Card, CardHeader, CardTitle, CardDescription, CardContent, CardFooter } from '@/components/ui/card'
import { Alert, AlertDescription } from '@/components/ui/alert'
import { Loader2 } from 'lucide-react'

const loginSchema = z.object({
  email: z.string().email('Invalid email address'),
  password: z.string().min(6, 'Password must be at least 6 characters'),
})

export default function CommonLogin() {
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)
  const navigate = useNavigate()
  const { login } = useAuthStore()

  const { register, handleSubmit, formState: { errors } } = useForm({
    resolver: zodResolver(loginSchema),
  })

  const onSubmit = async (data) => {
    try {
      setLoading(true)
      setError('')
      const response = await authAPI.login(data)
      const { user, token, role } = response.data
      
      login(user, token)
      
      // Redirect based on role
      if (role === 'super_admin' || role === 'admin') {
        navigate('/organizations')
      } else if (role === 'organization_admin') {
        navigate('/programs')
      } else if (role === 'group_head') {
        navigate('/programs')
      } else if (role.startsWith('program_')) {
        navigate('/activities')
      } else {
        navigate('/')
      }
    } catch (err) {
      setError(err.response?.data?.message || 'Invalid credentials')
    } finally {
      setLoading(false)
    }
  }

  return (
    <Card className="shadow-2xl">
      <CardHeader className="space-y-1 text-center">
        <div className="mx-auto mb-4 h-16 w-16 rounded-full bg-primary flex items-center justify-center">
          <span className="text-3xl font-bold text-white">Q</span>
        </div>
        <CardTitle className="text-3xl font-bold">QSights 2.0</CardTitle>
        <CardDescription className="text-lg">
          Enterprise Survey Platform
        </CardDescription>
      </CardHeader>
      <CardContent>
        {error && (
          <Alert variant="destructive" className="mb-4">
            <AlertDescription>{error}</AlertDescription>
          </Alert>
        )}
        
        <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
          <div className="space-y-2">
            <Label htmlFor="email">Email Address</Label>
            <Input
              id="email"
              type="email"
              placeholder="admin@qsights.com"
              {...register('email')}
              className="h-12"
            />
            {errors.email && (
              <p className="text-sm text-red-500">{errors.email.message}</p>
            )}
          </div>

          <div className="space-y-2">
            <Label htmlFor="password">Password</Label>
            <Input
              id="password"
              type="password"
              placeholder="••••••••"
              {...register('password')}
              className="h-12"
            />
            {errors.password && (
              <p className="text-sm text-red-500">{errors.password.message}</p>
            )}
          </div>

          <Button
            type="submit"
            className="w-full h-12 text-lg"
            disabled={loading}
          >
            {loading && <Loader2 className="mr-2 h-4 w-4 animate-spin" />}
            Sign In
          </Button>
        </form>
      </CardContent>
      <CardFooter className="flex flex-col space-y-2 text-center text-sm text-gray-500">
        <p>For system administrators, group heads, and program managers</p>
        <a href="/participant-login" className="text-primary hover:underline">
          Participant Login →
        </a>
      </CardFooter>
    </Card>
  )
}
