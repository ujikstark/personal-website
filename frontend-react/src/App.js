import React from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import './App.css';
import NavigationBar from './components/navigationBar';
import Home from './pages/Home';
import AuthProvider from './contexts/AuthContext';
import { Route, Routes } from 'react-router-dom';
import Todo from './pages/Todo';
import Profile from './pages/Profile';
import ResetPassword from './pages/ResetPassword';
import Messaging from './pages/Messaging';

function App() {
    return (
        <div className="App">
            <AuthProvider>
                <NavigationBar/>
                <Routes>
                    <Route path="/" exact element={<Home/>}/>
                    <Route path="/todo" element={<Todo/>}/>
                    <Route path="/me" element={<Profile/>}/>
                    <Route path="/reset-password/:token" element={<ResetPassword/>}/>
                    <Route path="/messaging" element={<Messaging/>}></Route>
                </Routes>
            </AuthProvider>
        </div>
    );
}

export default App;