import 'react-date-range/dist/styles.css'; // main css file
import 'react-date-range/dist/theme/default.css'; // theme css file

import React from 'react';
import { DateRangePicker, defaultStaticRanges, createStaticRanges } from 'react-date-range';
import { Calendar } from 'react-date-range';

class Picker extends React.Component {

  handleSelect = ranges => {
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

      const staticRanges = createStaticRanges([
          {
            label: 'Begining of Time',
            range: () => ({
              startDate: START_DATE,
              endDate: new Date()
            })
          }
        ]);
  
      console.log(staticRanges);
    return ( <div className={this.pickerClass(this.props.pickerOpen)}>

      <DateRangePicker
        ranges={[selectionRange]}
        onChange={this.handleSelect}
        rangeColors={["#ec4444"]}
        months={2}
        direction="horizontal"
        maxDate={new Date()}
        // staticRanges={[staticRanges]}
      /> 
    
    </div>
      
    )
  }
}

export default Picker;