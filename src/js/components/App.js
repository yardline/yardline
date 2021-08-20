import React from 'react';
import Header from './header/Header';
import Main from './Main';

function App(props) {
    return(
        <div>
            <Header />
            <Main siteTitle={props.siteTitle} restURL={props.restURL}/>
        </div>
    )
}

export default App;