import React from 'react';
import Goal from './Goal';

class Goals extends React.Component {
    goalsData = {
        one: {},

        two: {}
    }
    render() {
        return(
            <div className="stat-wrap">
            {Object.keys(this.goalsData).map( 
                key => <Goal stat={this.goalsData[key]}/>
            )}
            
        </div>
        )
       
    }
}

export default Goals;