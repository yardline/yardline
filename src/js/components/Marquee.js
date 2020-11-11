import React from 'react';
import BigStat from './BigStat.js';

const stat = {
    visitors: {
        name:'Visitors',
        number: 4500,
        percent: 2,
    },
    pageviews: {
        name:'Pageviews',
        number: 6230,
        percent: 1,
    }
};
class Marquee extends React.Component {
    render() {
        return (
            <div className="marquee yl-dark-bg yl-radius">
                
            <div className="stat-wrap">
                <BigStat stat={stat.visitors}/>
                <BigStat stat={stat.pageviews}/>
            </div>
            </div>
        );
    }
}

export default Marquee;