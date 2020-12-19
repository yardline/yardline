import React from 'react';
import { formatDate } from './helpers.js';
class CurrentRange extends React.Component {
    render() {
        return (
            <button className="yardline-current-range" onClick={this.props.toggleClass} >
                <span className="yl-start-date">{this.props.range.startDate.toDateString()}</span>
                <span> to </span>
                <span className="yl-end-date">{this.props.range.endDate.toDateString()}</span>     
            </button>
        );
    }

}

export default CurrentRange;