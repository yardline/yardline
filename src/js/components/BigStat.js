import React from 'react';

class BigStat extends React.Component {
    arrowClass = percent => {
        return percent >= 0 ? 'stat-up dashicons dashicons-arrow-up-alt' : 'stat-down dashicons dashicons-arrow-down-alt';
    }
    render() {
        return (
            <div className="stat">
                <h3>{this.props.stat.name}</h3>
                <span className="number">{this.props.stat.number}</span>
                <span className="compare"><span className={this.arrowClass(this.props.stat.percent)}></span>{this.props.stat.percent}%</span>
            </div>
        );
    }
}

export default BigStat;