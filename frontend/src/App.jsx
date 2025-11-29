import { Routes, Route, Navigate } from 'react-router-dom'
import { Toaster } from '@/components/ui/toaster'
import { useAuthStore } from '@/store/authStore'

// Layouts
import MainLayout from '@/layouts/MainLayout'
import AuthLayout from '@/layouts/AuthLayout'

// Auth Pages
import CommonLogin from '@/pages/auth/CommonLogin'
import ParticipantLogin from '@/pages/auth/ParticipantLogin'

// Dashboard
import Dashboard from '@/pages/Dashboard'

// Organizations
import OrganizationsList from '@/pages/organizations/OrganizationsList'
import OrganizationForm from '@/pages/organizations/OrganizationForm'
import OrganizationDetails from '@/pages/organizations/OrganizationDetails'

// Programs
import ProgramsList from '@/pages/programs/ProgramsList'
import ProgramForm from '@/pages/programs/ProgramForm'
import ProgramDetails from '@/pages/programs/ProgramDetails'

// Activities
import ActivitiesList from '@/pages/activities/ActivitiesList'
import ActivityForm from '@/pages/activities/ActivityForm'
import ActivityDetails from '@/pages/activities/ActivityDetails'

// Questionnaires
import QuestionnairesList from '@/pages/questionnaires/QuestionnairesList'
import QuestionnaireBuilder from '@/pages/questionnaires/QuestionnaireBuilder'

// Participants
import ParticipantsList from '@/pages/participants/ParticipantsList'
import ParticipantForm from '@/pages/participants/ParticipantForm'

// Approval
import ApprovalPage from '@/pages/approval/ApprovalPage'

// Participant Experience
import ParticipantQuestionnaire from '@/pages/participant/ParticipantQuestionnaire'
import ThankYou from '@/pages/participant/ThankYou'

// Analytics
import Analytics from '@/pages/Analytics'

function ProtectedRoute({ children }) {
  const { isAuthenticated } = useAuthStore()
  return isAuthenticated ? children : <Navigate to="/login" replace />
}

function App() {
  return (
    <>
      <Routes>
        {/* Auth Routes */}
        <Route path="/login" element={<AuthLayout><CommonLogin /></AuthLayout>} />
        <Route path="/participant-login" element={<AuthLayout><ParticipantLogin /></AuthLayout>} />
        
        {/* Manager Approval */}
        <Route path="/approve/:token" element={<ApprovalPage />} />
        
        {/* Participant Experience */}
        <Route path="/participate/general/:activityId" element={<ParticipantQuestionnaire type="general" />} />
        <Route path="/participate/guest/:activityId" element={<ParticipantQuestionnaire type="guest" />} />
        <Route path="/thank-you" element={<ThankYou />} />

        {/* Protected Routes */}
        <Route path="/" element={<ProtectedRoute><MainLayout /></ProtectedRoute>}>
          <Route index element={<Dashboard />} />
          
          {/* Organizations */}
          <Route path="organizations" element={<OrganizationsList />} />
          <Route path="organizations/new" element={<OrganizationForm />} />
          <Route path="organizations/:id" element={<OrganizationDetails />} />
          <Route path="organizations/:id/edit" element={<OrganizationForm />} />
          
          {/* Programs */}
          <Route path="programs" element={<ProgramsList />} />
          <Route path="programs/new" element={<ProgramForm />} />
          <Route path="programs/:id" element={<ProgramDetails />} />
          <Route path="programs/:id/edit" element={<ProgramForm />} />
          
          {/* Activities */}
          <Route path="activities" element={<ActivitiesList />} />
          <Route path="activities/new" element={<ActivityForm />} />
          <Route path="activities/:id" element={<ActivityDetails />} />
          <Route path="activities/:id/edit" element={<ActivityForm />} />
          
          {/* Questionnaires */}
          <Route path="questionnaires" element={<QuestionnairesList />} />
          <Route path="questionnaires/new" element={<QuestionnaireBuilder />} />
          <Route path="questionnaires/:id/edit" element={<QuestionnaireBuilder />} />
          
          {/* Participants */}
          <Route path="participants" element={<ParticipantsList />} />
          <Route path="participants/new" element={<ParticipantForm />} />
          <Route path="participants/:id/edit" element={<ParticipantForm />} />
          
          {/* Analytics */}
          <Route path="analytics" element={<Analytics />} />
        </Route>
      </Routes>
      <Toaster />
    </>
  )
}

export default App
