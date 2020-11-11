import React from 'react';
import Marquee from './Marquee.js';
import PageViews from './PageViews.js';
import Range from './Range.js';
import Visitors from './Visitors.js';
import Refers from './Refers.js';

class ScoreBoard extends React.Component {

    render() {
        return (
            <div>
                <Range />
                <Marquee />
                <Visitors />
                <div className="scoreboard-row">
                    <PageViews />
                    <Refers />
                </div>
                
            </div>
            
        )
    }
}

export default ScoreBoard;