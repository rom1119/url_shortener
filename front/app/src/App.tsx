import React from 'react';
import logo from './logo.svg';
import './App.css';
import ShortUrlForm from './ShortUrlForm';
import MainPage from './MainPage';
import Page from './Page';
import { Route, Routes, BrowserRouter } from 'react-router-dom';


function App() {
  return (
    <div className="App">
      <a href="/">Add URL</a>
      <br></br>
      <a href="/list-pages">List pages</a>
      <Routes>
        <Route path="/" element={<MainPage />} />
        <Route path="/:slug*" element={<Page />} />
      </Routes>
    </div>
  );
}

export default App;
