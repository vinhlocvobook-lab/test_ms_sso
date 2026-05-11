import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Login from './components/Login';
import AuthCallback from './components/AuthCallback';
import './App.css';

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Login />} />
        <Route path="/auth/callback" element={<AuthCallback />} />
      </Routes>
    </Router>
  );
}

export default App;
