import React from 'react';
import BigStat from './BigStat.js';

class Marquee extends React.Component {
    render() {
        return (
            <div className="marquee yl-dark-bg yl-radius">
                
            <div className="stat-wrap">
                {Object.keys(this.props.marqueeData).map( 
                    key => <BigStat stat={this.props.marqueeData[key]}/>
                )}
                
            </div>
            </div>
        );
    }
}

export default Marquee;