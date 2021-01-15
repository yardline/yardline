import React from 'react';
import {
  ResponsiveContainer, BarChart, Bar, Cell, XAxis, YAxis, Tooltip, CartesianGrid, Legend, LabelList
} from 'recharts';

    
class PageViews extends React.Component {
    render() {
        return (
            <div className="page-views yl-white-bg yl-radius yl-score-board-card">
                <h2>Page Views</h2>
                <ResponsiveContainer width="100%" height={350}>
                  <BarChart
                    data={this.props.pageViewsData}
                    margin={{
                    top: 20, right: 30, left: 20, bottom: 5,
                    }}
                    layout="vertical"
                  >
                   
                    <XAxis hide="true" type="number" />
                    <YAxis hide="true" dataKey="path" type="category"   />
                    <Tooltip active={true}/>
                  
                    <Bar  name="Visitors" dataKey="pageviews" stackId="a" fill="rgba(236,68,68,0.5)" >
                        <LabelList dataKey="path" position="insideLeft" />
                        <LabelList dataKey="pageviews" position="right" />
                    </Bar>  
                  </BarChart>
                </ResponsiveContainer>
            </div>
            
        )
    }
}

export default PageViews;