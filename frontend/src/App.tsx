import React from 'react';
import { BrowserRouter as Router, Route, Link, Routes } from "react-router-dom";
import logo from './logo.svg';
import './App.css';
import Customer from './Components/Customer';
import Orders from './Components/Orders';

function App() {
  return (
    <Router>
      {/* <div className="App">
        <header className="App-header">
          <img src={logo} className="App-logo" alt="logo" />
          <p>
            Edit <code>src/App.tsx</code> and save to reload.
          </p>
          <a
            className="App-link"
            href="https://reactjs.org"
            target="_blank"
            rel="noopener noreferrer"
          >
            Learn React
          </a>
        </header>

      </div> */}
      <Routes>
        <Route path="/" Component={Customer} />
        <Route path="/orders/:orderId" Component={Orders} />
      </Routes>
    </Router>

  );
}

export default App;
