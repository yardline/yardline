import React from 'react';
import {render} from 'react-dom';
import ScoreBoard from '../components/ScoreBoard';
console.log('helll');
console.log(yardlineAdmin);
render( <ScoreBoard siteTitle={yardlineAdmin.siteTitle} />, document.querySelector('#score-board'));
//const element = <h1>Hello, world</h1>;
//render(element, document.querySelector('#score-board'));
