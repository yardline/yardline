import React from "react";
import { formatDate } from "./helpers.js";
function CurrentRange(props) {
  return (
    <button className="yardline-current-range" onClick={props.toggleClass}>
      <span className="yl-start-date">
        {props.range.startDate.toDateString()}
      </span>
      <span> to </span>
      <span className="yl-end-date">{props.range.endDate.toDateString()}</span>
    </button>
  );
}

export default CurrentRange;
