import React from 'react';

class BigStat extends React.Component {

    render() {
        return (
            <div className="stat">
                <h3>{this.props.stat.name}</h3>
                <span className="number">{this.props.stat.number}</span>
                <span className="compare"><span className="stat-up dashicons dashicons-arrow-up-alt"></span>{this.props.stat.percent}%</span>
            </div>
        );
    }
}

export default BigStat;