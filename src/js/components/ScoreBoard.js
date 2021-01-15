import React from 'react';
import Marquee from './Marquee.js';
import PageViews from './PageViews.js';
import Visitors from './Visitors.js';
import Refers from './Refers.js';
import Range from './Range.js';
import { format } from 'date-fns';

class ScoreBoard extends React.Component {
    formatDate(date){
        let year = date.getFullYear();
        let month = date.getMonth() + 1;
        let day = date.getDate();
        return year+'-'+month+'-'+day;
    };
    state = {
        range: {
           // startDate: format(new Date( Date.now() - 7 * 24 * 60 * 60 * 1000 ), 'yyyy-MM-dd'),
            //endDate: format(new Date( Date.now() - 1 * 24 * 60 * 60 * 1000 ), 'yyyy-MM-dd'),
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
          ],
          statsData : [],
          marqueeData : {
            visitors: {
                name:'Visitors',
                number: 4500,
                percent: 2,
            },
            pageviews: {
                name:'Pageviews',
                number: 230,
                percent: 1,
            },
            numberOfOrders: {
                name: 'Number of Sales',
                number: 28,
                percent: -3
            },
            formSubmissions: {
                name: 'Form Submissions',
                number: 18,
                percent: 8
            }
          }
    }
    setRange = (range) => {
        this.getStats(range); 
        this.getPageViews(range);
        this.setState({range});  
        
    }
    getStats = (range) => {
        let queryStr = ''
        queryStr = this.props.restURL + 'yardline/v1/stats?'
        queryStr += 'start_date=' + this.formatDate(range.startDate)
        queryStr += '&end_date=' + this.formatDate(range.endDate)
       
        fetch(queryStr)
          .then(response => response.json())
          .then(data => this.setState( {
              statsData: data,
              marqueeData: {
                  visitors: {
                name:'Visitors',
                number: this.sumStats(data).visitors,
                percent: 2,
            },
            pageviews: {
                name:'Page Views',
                number: this.sumStats(data).pageviews,
                percent: 2,
            },
        }
        }))
          
    }

    getPageViews = (range) => {
      let queryStr = ''
        queryStr = this.props.restURL + 'yardline/v1/pageviews?'
        queryStr += 'start_date=' + this.formatDate(range.startDate)
        queryStr += '&end_date=' + this.formatDate(range.endDate)
       
        fetch(queryStr)
          .then(response => response.json())
          .then(data => this.setState( {
            pageViewsData: data
          }))
    }

    sumStats = (data) => {
        let statsSum = data;
        let visitors = 0;
        let pageviews = 0;
        statsSum.forEach( stat => {
            
            visitors += parseInt(stat.visitors);
            pageviews += parseInt(stat.pageviews);
           
        }
            
            );
       
        return { visitors: visitors, pageviews: pageviews };
    }
    
    componentDidMount() {
        this.getStats(this.state.range)
        this.getPageViews(this.state.range)
        
    }
    render() {
        
        return (
            <div>

                <h1>{this.props.siteTitle} Scoreboard</h1>
                <Range range={this.state.range} setRange={this.setRange} />
                {/* <Picker range={this.state.range} setRange={this.setRange} /> */}
                <Marquee marqueeData={this.state.marqueeData}/>
                <Visitors statsData={this.state.statsData}/>
                <div className="scoreboard-row">
                    <PageViews pageViewsData={this.state.pageViewsData}/>
                    <Refers refersData={this.state.refersData}/>
                </div> 
                
            </div>
            
        )
    }
}

export default ScoreBoard;