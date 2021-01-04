import { HashRouter, Route, Switch } from 'react-router-dom'
import React from 'react';
import {render} from 'react-dom';
//import ScoreBoard from './ScoreBoard'
import Other from './Other'
import Start from './Start'

const Router = () => {
    <HashRouter>
        <Switch>
            <Route  exact path="/" component={Start} />
                
            <Route exact  path="/other" component={Other} />
        </Switch>
    </HashRouter>
};

 export default Router;