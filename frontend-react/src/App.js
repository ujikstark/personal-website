import React from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import './App.css';
import NavigationBar from './components/navigationBar';
import Home from './pages/Home';
import AuthProvider from './contexts/AuthContext';

function App() {
    return (
        <div className="App">
            <AuthProvider>
                <NavigationBar/>
                <Home></Home>
            </AuthProvider>
        </div>
    );
}

export default App;