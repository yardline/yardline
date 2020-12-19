import React from 'react';
import 'react-date-range/dist/styles.css'; // main css file
import 'react-date-range/dist/theme/default.css'; // theme css file
import './range/range.css'
import CurrentRange from './CurrentRange.js';
import Picker from './Picker.js';
class Range extends React.Component {
    

   state = {
       pickerOpen : false
   }

   toggleClass = e => {
        open = this.state.pickerOpen ? false : true;
        this.setState({ pickerOpen: open });
   }   
    render() {
       
       return( <div className="range-select">
            <CurrentRange range={this.props.range} pickerClass={this.state.pickerClass} toggleClass={this.toggleClass} />

            <Picker range={this.props.range} setRange={this.props.setRange} pickerOpen={this.state.pickerOpen} toggleClass={this.toggleClass}/>
        </div>
       );
    }
}

export default Range;