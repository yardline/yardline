import React from 'react';
import {
  ResponsiveContainer, BarChart, Bar, Cell, XAxis, YAxis, Tooltip, CartesianGrid, Legend, LabelList
} from 'recharts';
    
class Referrers extends React.Component {
    render() {
        return (
            <div className="refers yl-white-bg yl-radius yl-score-board-card">
                <h2>Referrers</h2>
                <ResponsiveContainer width="100%" height={350}>
                  <BarChart
                      data={this.props.referrersData}
                      margin={{
                      top: 20, right: 30, left: 20, bottom: 5,
                      }}
                      layout="vertical"
                  >
                    <XAxis hide="true" type="number" />
                    <YAxis hide="true" dataKey="url" type="category"   />
                    <Tooltip />
                    <Bar  name="Referrers" dataKey="pageviews" stackId="a" fill="rgba(0,132,193,0.5)" >
                        <LabelList dataKey="url" position="insideLeft" />
                        <LabelList dataKey="url" position="insideLeft" />
                        <LabelList dataKey="pageviews" position="right" />
                    </Bar>
                  </BarChart>
                </ResponsiveContainer>
            </div>
            
        )
    }
}

export default Referrers;