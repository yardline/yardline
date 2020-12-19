import React from 'react';
import Marquee from './Marquee.js';
import PageViews from './PageViews.js';
import Visitors from './Visitors.js';
import Refers from './Refers.js';
import Range from './Range.js';

class ScoreBoard extends React.Component {
    state = {
        range: {
            startDate: new Date( Date.now() - 7 * 24 * 60 * 60 * 1000 ),
            endDate: new Date( Date.now() - 1 * 24 * 60 * 60 * 1000 ),
            key: 'selection',
        },
        pageViewsData : [
            {
              name: '/', pv: 4400,
            },
            {
                name: '/shop', pv: 3908,
            },
            {
              name: '/blog', pv: 1398,
            },
            {
              name: '/about', pv: 800,
            },
           
            {
              name: '/contact', pv: 780,
            },
            {
              name: '/team', pv: 500,
            },
            
          ],
          visitorsData: [
            {
              name: 'Dec 6', uv: 4000, pv: 2400, amt: 2400,
            },
            {
              name: 'Dec 7', uv: 3000, pv: 1398, amt: 2210,
            },
            {
              name: 'Dec 8', uv: 2000, pv: 1800, amt: 2290,
            },
            {
              name: 'Dec 9', uv: 2780, pv: 1908, amt: 2000,
            },
            {
              name: 'Dec 10', uv: 1890, pv: 800, amt: 2181,
            },
            {
              name: 'Dec 11', uv: 2390, pv: 1800, amt: 2500,
            },
            {
              name: 'Dec 12', uv: 3490, pv: 2300, amt: 2100,
            },
          ],
          refersData: [
            {
                name: 'facebook.com', pv: 9800,
              },
            {
                name: 'twitter.com', pv: 2400,
              },
              {
                name: 'google.com', pv: 1398,
              },
              {
                name: 'Page 4', pv: 800,
              },
          ]
    }
    setRange = (range) => {
        this.setState({range});
            
        
    }
    render() {
        return (
            <div>
                
                <h1>{this.props.siteTitle} Scoreboard</h1>
                <Range range={this.state.range} setRange={this.setRange} />
                {/* <Picker range={this.state.range} setRange={this.setRange} /> */}
                <Marquee />
                <Visitors visitorsData={this.state.visitorsData}/>
                <div className="scoreboard-row">
                    <PageViews pageViewsData={this.state.pageViewsData}/>
                    <Refers refersData={this.state.refersData}/>
                </div> 
                
            </div>
            
        )
    }
}

export default ScoreBoard;