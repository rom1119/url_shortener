import React from 'react';
import logo from './logo.svg';
import './App.css';
import  ShortUrlForm  from './ShortUrlForm';
import  MainPage  from './MainPage';
import  ListPages  from './ListPages';
import { Route, Routes, BrowserRouter } from 'react-router-dom';


function App() {
  return (
    <div className="App">
      <Routes>
          <Route path="/" element={<MainPage />} />     
          <Route path="/list-pages" element={<ListPages />} />     
      </Routes>
    </div>
  );
}

export default App;
