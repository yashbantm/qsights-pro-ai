import { useState, useEffect } from 'react'
import { useNavigate, useSearchParams } from 'react-router-dom'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import * as z from 'zod'
import { authAPI, programsAPI } from '@/lib/api'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Alert, AlertDescription } from '@/components/ui/alert'
import { Loader2 } from 'lucide-react'

const participantLoginSchema = z.object({
  email: z.string().email('Invalid email address'),
  activity_id: z.string().uuid('Invalid activity'),
})

export default function ParticipantLogin() {
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)
  const [languages, setLanguages] = useState(['en'])
  const [selectedLanguage, setSelectedLanguage] = useState('en')
  const [programTheme, setProgramTheme] = useState(null)
  const [searchParams] = useSearchParams()
  const navigate = useNavigate()

  const activityId = searchParams.get('activity')

  const { register, handleSubmit, setValue, formState: { errors } } = useForm({
    resolver: zodResolver(participantLoginSchema),
    defaultValues: {
      activity_id: activityId || '',
    },
  })

  useEffect(() => {
    if (activityId) {
      setValue('activity_id', activityId)
      // Fetch program theme and language settings
      fetchProgramSettings(activityId)
    }
  }, [activityId, setValue])

  const fetchProgramSettings = async (activityId) => {
    try {
      // This would fetch the program settings including multilingual flag
      // For now, showing default structure
      setProgramTheme({
        color: '#3b82f6',
        banner: '',
        logo: '',
        heading: 'Welcome to Our Survey',
      })
      setLanguages(['en', 'es', 'fr']) // Example languages
    } catch (err) {
      console.error('Failed to fetch program settings', err)
    }
  }

  const onSubmit = async (data) => {
    try {
      setLoading(true)
      setError('')
      const response = await authAPI.participantLogin({
        ...data,
        language: selectedLanguage,
      })
      
      const { participant, token } = response.data
      
      // Store participant session
      localStorage.setItem('participant_token', token)
      localStorage.setItem('participant_language', selectedLanguage)
      
      // Navigate to questionnaire
      navigate(`/participate/${participant.type}/${data.activity_id}`)
    } catch (err) {
      setError(err.response?.data?.message || 'Participant not found or not authorized')
    } finally {
      setLoading(false)
    }
  }

  const themeColor = programTheme?.color || '#3b82f6'

  return (
    <div className="min-h-screen flex flex-col">
      {/* Custom Banner */}
      {programTheme?.banner && (
        <div 
          className="h-48 bg-cover bg-center"
          style={{ backgroundImage: `url(${programTheme.banner})` }}
        />
      )}

      <div className="flex-1 flex items-center justify-center p-4" style={{ backgroundColor: `${themeColor}10` }}>
        <Card className="w-full max-w-md shadow-2xl">
          <CardHeader className="space-y-1 text-center">
            {programTheme?.logo && (
              <img src={programTheme.logo} alt="Program Logo" className="h-20 mx-auto mb-4" />
            )}
            <CardTitle className="text-2xl font-bold" style={{ color: themeColor }}>
              {programTheme?.heading || 'Participant Portal'}
            </CardTitle>
            <CardDescription>
              Enter your email to access the survey
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
                  placeholder="participant@example.com"
                  {...register('email')}
                  className="h-12"
                />
                {errors.email && (
                  <p className="text-sm text-red-500">{errors.email.message}</p>
                )}
              </div>

              {languages.length > 1 && (
                <div className="space-y-2">
                  <Label htmlFor="language">Preferred Language</Label>
                  <Select value={selectedLanguage} onValueChange={setSelectedLanguage}>
                    <SelectTrigger className="h-12">
                      <SelectValue placeholder="Select language" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="en">English</SelectItem>
                      <SelectItem value="es">Español</SelectItem>
                      <SelectItem value="fr">Français</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              )}

              <input type="hidden" {...register('activity_id')} />

              <Button
                type="submit"
                className="w-full h-12 text-lg"
                style={{ backgroundColor: themeColor }}
                disabled={loading}
              >
                {loading && <Loader2 className="mr-2 h-4 w-4 animate-spin" />}
                Continue to Survey
              </Button>
            </form>

            <div className="mt-6 text-center text-sm text-gray-500">
              <a href="/login" className="text-primary hover:underline">
                Staff Login →
              </a>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  )
}
