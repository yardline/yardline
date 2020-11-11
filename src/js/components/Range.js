import React from 'react';

class Range extends React.Component {
    constructor() {
        super();
        this.rangeSelect = this.rangeSelect.bind(this);
    }
    render() {
       return( <div className="range-select">
            <form>
                <label>
                    Range: 
                <select>
                    <option value="yesterday">Yesterday</option>
                    <option>Last Week</option>
                    <option>This Month</option>
                    <option>Last Month</option>
                </select>
                </label>
            </form>
        </div>
       );
    }
}

export default Range;