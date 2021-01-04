import React from 'react';
import {render} from 'react-dom';
import ScoreBoard from '../components/ScoreBoard';


render( <ScoreBoard siteTitle={yardlineAdmin.siteTitle} restURL={yardlineAdmin.restURL}/>, document.querySelector('#score-board'));

