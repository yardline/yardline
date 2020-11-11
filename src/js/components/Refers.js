import React from 'react';
import {
  ResponsiveContainer, BarChart, Bar, Cell, XAxis, YAxis, Tooltip, CartesianGrid, Legend, LabelList
} from 'recharts';

const mata = [
    {
      name: 'twitter.com', pv: 2400,
    },
    {
      name: 'google.com', pv: 1398,
    },
    {
      name: 'facebook.com', pv: 9800,
    },
    {
      name: 'Page 4', pv: 3908,
    },
    {
      name: 'Page 5', pv: 4800,
    },
    {
      name: 'Page 6', pv: 3800,
    },
    {
      name: 'Page 7', pv: 4300,
    },
    {
        name: 'Page 8', pv: 4800,
      },
      {
        name: 'Page 9', pv: 3800,
      },
      {
        name: 'Page 10', pv: 300,
      },
  ];
    
class Refers extends React.Component {
    render() {
        return (
            <div className="refers yl-white-bg yl-radius">
                <h4>Refers</h4>
                <ResponsiveContainer width="100%" height={350}>
                  <BarChart
                      data={mata}
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