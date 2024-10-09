import React, { ChangeEvent, useEffect, useState, FormEvent, ReactElement } from 'react';
import logo from './logo.svg';
import './App.css';
import ShortUrlForm from './ShortUrlForm';
import { Route, Routes, BrowserRouter } from 'react-router-dom';
import axios from 'axios';
import { useParams } from 'react-router-dom';


export default function Page() {
  type ItemType = {short_code: string};
  const [state, setState] = useState<{ real_url: string, customPage: boolean, itemList: ItemType[] }>({
    real_url: '',
    customPage: false,
    itemList: [],
  })

  const [html, setHtml] = useState< ReactElement >( <h2>LOADING</h2>)

  const params = useParams();
  var slug = params['slug']
  if (params['*']) {
    slug = slug  + '/' + params['*']
  }

  async function fetchItem() {
    // alert('here = ' + urlField);  
    axios.get('http://localhost:8000/from-short-url?code=' + slug, {})
      .then((response) => {
        console.log('SUCC')

        console.log(response)
        if (response.status === 200) {
          setState({ real_url: response.data.real_url, customPage: state.customPage, itemList: state.itemList })
          window.location.href = response.data.real_url
        }
      }).catch((err) => {

        axios.get('http://localhost:8000/from-real-url?real_url=' + slug, {})
        .then((response) => {
          if (response.status === 200) {
            setState({ real_url: response.data.real_url, customPage: state.customPage, itemList: state.itemList })
            setHtml(<h2>CURRENT PAGE:  /{ slug }</h2>)

          }
        }).catch((err) => {
          setHtml(<h2>PAGE NOT FOUND</h2>)
        })

      })
    
  }

  const css = `
    .list a {
        color: white !important;
    }
`

  async function fetchList() {
    // alert('here = ' + urlField);  
    axios.get('http://localhost:8000/list-url', {})
      .then((response) => {
        console.log('SUCC')

      console.log(response)
        if (response.status === 200) {
          var items: ItemType[] = []
          for (var el of response.data) {
            items.push({ short_code: el.short_code})
          }
          setState({ real_url: state.real_url, customPage: state.customPage, itemList: items })
          
          setHtml(<>
            <h2>List of URL's</h2>
            <style>{css}</style>
            <ul className='list'>
            {items.map(el => (
              <li key={el.short_code}><a href={el.short_code}>{el.short_code}</a></li>
            ))}
              </ul>
            </>
          )
      }
    }).catch((err) => {
      console.log('ERROR')
      console.log(err)
  
    })
  }

   useEffect(() => {
    console.log('ERROR')
    console.log(slug)
    if (slug === 'list-pages') {
      fetchList()

    } else {
      // setState({ real_url: state.real_url , customPage: true, itemList: state.itemList})
     fetchItem()



    }
  }, []);
  return (
    <div className="App">
      <header className="App-header">
        <img src={logo} className="App-logo" alt="logo" />
        {html}
      </header>
    </div>
  );
}
