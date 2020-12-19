import 'react-date-range/dist/styles.css'; // main css file
import 'react-date-range/dist/theme/default.css'; // theme css file

import React from 'react';
import { DateRangePicker } from 'react-date-range';
import { Calendar } from 'react-date-range';

class Picker extends React.Component {

  handleSelect = ranges => {
        // console.log(ranges.selection)
        this.props.setRange( ranges.selection );
        this.props.toggleClass();
  }

  pickerClass = open => {
      if ( open ) {
          return 'picker-open';
      }
      return 'picker-closed';
  }
  render(){
   
    const selectionRange = {
        startDate: this.props.range.startDate,
        endDate: this.props.range.endDate,
        key: 'selection',
        
      }
    return ( <div className={this.pickerClass(this.props.pickerOpen)}>

      <DateRangePicker
        ranges={[selectionRange]}
        onChange={this.handleSelect}
        rangeColors={["#ec4444"]}
        months={2}
        direction="horizontal"
        maxDate={new Date()}
      /> 
    
    </div>
      
    )
  }
}

export default Picker;