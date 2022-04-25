import React from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import './App.css';
import NavigationBar from './components/navigationBar';
import AuthProvider from './contexts/AuthContext';
import { Route, Routes } from 'react-router-dom';
import Loader from './components/Loader';


function App() {

    const Home = React.lazy(() => import('./pages/Home'));
    const Profile = React.lazy(() => import('./pages/Profile'))
    const Resume = React.lazy(() => import('./pages/Resume'));
    const Todos = React.lazy(() => import('./pages/Todo'));
    const ResetPassword = React.lazy(() => import('./pages/ResetPassword'));
    const Messaging = React.lazy(() => import('./pages/Messaging'));

    return (
        <div className="App">
            <AuthProvider>
                <NavigationBar/>
                <React.Suspense fallback={<Loader/>}>
                    
                    <Routes>
                        <Route path="/" index element={<Home/>}/>
                        <Route path="/todo" element={<Todos/>}/>
                        <Route path="/me" element={<Profile/>}/>
                        <Route path="/reset-password/:token" element={<ResetPassword/>}/>
                        <Route path="/resume" element={<Resume/>}/>
                        <Route path="/messaging" element={<Messaging/>}/>
                    </Routes>
                </React.Suspense>
                
            </AuthProvider>
        </div>
    );
}

export default App;