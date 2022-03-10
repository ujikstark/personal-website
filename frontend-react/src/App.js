import React from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import './App.css';
import NavigationBar from './components/navigationBar';
import Home from './pages/Home';

function App() {
    return (
        <div className="App">
            <NavigationBar/>
            <Home></Home>
        </div>
    );
}

export default App;