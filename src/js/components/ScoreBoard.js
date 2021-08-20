import React from 'react';
import Marquee from './Marquee.js';
import PageViews from './PageViews.js';
import Visitors from './Visitors.js';
import Referrers from './Referrers.js';
import Range from './Range.js';
import { format } from 'date-fns';

class ScoreBoard extends React.Component {
    formatDate(date){
        let year = date.getFullYear();
        let month =  '0' + (date.getMonth() + 1);
        let day = '0' + date.getDate();
        return year+'-'+month.slice(-2)+'-'+day.slice(-2);
    };
    state = {
        range: {
           // startDate: format(new Date( Date.now() - 7 * 24 * 60 * 60 * 1000 ), 'yyyy-MM-dd'),
            //endDate: format(new Date( Date.now() - 1 * 24 * 60 * 60 * 1000 ), 'yyyy-MM-dd'),
            startDate: new Date( Date.now() - 7 * 24 * 60 * 60 * 1000 ),
            endDate: new Date( Date.now() - 1 * 24 * 60 * 60 * 1000 ),
            key: 'selection',
        },
        pageViewsData : [],
        referrersData : [],
        statsData : [],
        marqueeData : {}
    }
    setRange = (range) => {
        this.getStats(range); 
        this.getPageViews(range);
        this.getReferrers(range);
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
              statsData: this.fillDates(data),
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
            bouncerate: {
              name: 'Bounce Rate',
              number: '24%',
              percent: 0
            }
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

    getReferrers = (range) => {
      let queryStr = ''
        queryStr = this.props.restURL + 'yardline/v1/referrers?'
        queryStr += 'start_date=' + this.formatDate(range.startDate)
        queryStr += '&end_date=' + this.formatDate(range.endDate)
       
        fetch(queryStr)
          .then(response => response.json())
          .then(data => this.setState( {
            referrersData: data
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

    // Combine array of dates for the entire range with the data from the API
    fillDates = (data) => {
      
      let daysArray = this.getDaysArray(this.state.range.startDate, this.state.range.endDate)
      let returnArray = []
      daysArray.forEach(function (day) {
        if( data.findIndex( item => item.date == day.date) >=0) {
          
          returnArray.push(data[data.findIndex( item => item.date == day.date)])
        } else {
          returnArray.push(day)
        }
      });
      return returnArray;
    }
    //build array for all days in the date range
    getDaysArray = (start, end) => {
      for(var arr=[],dt=new Date(start); dt<=end; dt.setDate(dt.getDate()+1)){
          arr.push({
            date: this.formatDate(new Date(dt)),
            visitors : 0,
            pageviews : 0
          });
      }
      return arr;
  };
    
    componentDidMount() {
        this.getStats(this.state.range)
        this.getPageViews(this.state.range)
        this.getReferrers(this.state.range)
        
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
                    <Referrers referrersData={this.state.referrersData}/>
                </div> 
                
            </div>
            
        )
    }
}

export default ScoreBoard;