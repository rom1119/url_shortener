import React from 'react';
import logo from './logo.svg';
import './App.css';
import  ShortUrlForm  from './ShortUrlForm';
import { Route, Routes, BrowserRouter } from 'react-router-dom';


function MainPage() {
  return (
    <div className="App">
      <header className="App-header">
        <img src={logo} className="App-logo" alt="logo" />
        <ShortUrlForm />
      </header>
    </div>
  );
}

export default MainPage;
