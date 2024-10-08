import React, { ChangeEvent, useEffect, useState, FormEvent } from 'react';
import logo from './logo.svg';
import axios from 'axios';


import './App.css';


var urlField = ''
/* eslint-disable */

export default function ShortUrlForm() {
  
  const [state, setState] = useState<{ url: string, errorMsg: string }>({
    url: "",
    errorMsg: "",
  })

  cssProps: {
      color: "red"
  }

    var addURL = (e: FormEvent) => {
      e.preventDefault();
      // alert('here = ' + urlField);  
      axios.post('http://localhost:8000/make-short-url', { 'urlParam': state.url })
        .then((response) => {
          console.log('SUSS')

        console.log(response)
        if (response.status == 200) {
          setState({ url: state.url, errorMsg: 'new URL created http://localhost:3000/' + response.data.short_url })
        }
      }).catch((err) => {
        console.log('ERROR')
        console.log(err)
        if (err.response.status == 400) {
          setState({ url: state.url, errorMsg: err.response.data.err })
          
        } else {
          setState({ url: state.url, errorMsg: 'UNHANDLED ERROR' })
        }
      })
    }

    var onChange =  (e: ChangeEvent<HTMLInputElement>) => {
      // this.state.url =  e.target.value 
      setState({ url: e.target.value, errorMsg: "" })
      // alert(e.target.value)
    }

  

    return (
      <div>
          <style>
          {
            `.red-text {
                  color: red;
              }`
          }
        </style>
          <form onSubmit={addURL}>
            <input type='text' name='url' required value={state.url} onChange={onChange} placeholder='Type in URL...'/>
            <button type='submit'>
              Add URL
            </button>
          </form>
        <h2 className="red-text">{ state.errorMsg}</h2>
      </div>
    );
  
}

