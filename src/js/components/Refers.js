import React from 'react';
import {
  ResponsiveContainer, BarChart, Bar, Cell, XAxis, YAxis, Tooltip, CartesianGrid, Legend, LabelList
} from 'recharts';
    
class Refers extends React.Component {
    render() {
        return (
            <div className="refers yl-white-bg yl-radius yl-score-board-card">
                <h2>Refers</h2>
                <ResponsiveContainer width="100%" height={350}>
                  <BarChart
                      data={this.props.refersData}
                      margin={{
                      top: 20, right: 30, left: 20, bottom: 5,
                      }}
                      layout="vertical"
                  >
                    <XAxis hide="true" type="number" />
                    <YAxis hide="true" dataKey="name" type="category"   />
                    <Tooltip />
                    <Bar  name="Refers" dataKey="pv" stackId="a" fill="rgba(0,132,193,0.5)" >
                        <LabelList dataKey="name" position="insideLeft" />
                    </Bar>
                  </BarChart>
                </ResponsiveContainer>
            </div>
            
        )
    }
}

export default Refers;