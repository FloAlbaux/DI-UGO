import React from 'react';
import { BrowserRouter as Router, Route, Link, Routes } from "react-router-dom";
import logo from './displaylogo.png';
import './App.css';
import Customer from './Components/Customer';
import Orders from './Components/Orders';

const Header = () => {
  return (
    <header className="super-header">
      <div className="logo-container">
        <img src={logo} alt="Logo" className="logo" />
      </div>
      <div className="title-container">
        <h1>UGO test fullstack</h1>
      </div>
    </header>
  );
};

const Footer = () => {
  return (
    <footer className="super-footer">
      <div className="social-buttons">
        <a href="https://github.com/FloAlbaux" target="_blank" rel="noopener noreferrer">
          <button className="icon-button">
            <i className="fab fa-github"></i>
          </button>
        </a>
        <a href="https://www.linkedin.com/in/florence-albaux-04756315b/" target="_blank" rel="noopener noreferrer">
          <button className="icon-button">
            <i className="fab fa-linkedin"></i>
          </button>
        </a>
      </div>
      <p>See you soon !</p>
    </footer>
  );
};


function App() {
  return (
    <Router>
      <Header />
      <Routes>
        <Route path="/" Component={Customer} />
        <Route path="/orders/:orderId" Component={Orders} />
      </Routes>
      <Footer />
    </Router>

  );
}

export default App;
