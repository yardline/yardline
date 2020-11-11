import React from 'react';
import {
  ResponsiveContainer, BarChart, Bar, Cell, XAxis, YAxis, CartesianGrid, Tooltip, Legend,
} from 'recharts';

const data = [
  {
    name: 'Oct 2', uv: 4000, pv: 2400, amt: 2400,
  },
  {
    name: 'Oct 3', uv: 3000, pv: 1398, amt: 2210,
  },
  {
    name: 'Oct 4', uv: 2000, pv: 9800, amt: 2290,
  },
  {
    name: 'Oct 5', uv: 2780, pv: 3908, amt: 2000,
  },
  {
    name: 'Oct 6', uv: 1890, pv: 4800, amt: 2181,
  },
  {
    name: 'Oct 7', uv: 2390, pv: 3800, amt: 2500,
  },
  {
    name: 'Oct 8', uv: 3490, pv: 4300, amt: 2100,
  },
];



class Visitors extends React.Component {
    render() {
        return (
            <div className="visitors yl-white-bg yl-radius">
                <h4>Visitors</h4>
                <ResponsiveContainer width="100%" height={350}>
                  <BarChart
                   
                    data={data}
                    margin={{
                    top: 20, right: 30, left: 20, bottom: 5,
                    }}
                  >
                    <CartesianGrid strokeDasharray="3 3" />
                    <XAxis dataKey="name" />
                    <YAxis />
                    <Tooltip />
                    <Legend />
                    <Bar name="Visitors" dataKey="pv" stackId="a" fill="#ec4444" />
                    <Bar name="Page Views" dataKey="uv" stackId="b" fill="#0084c1" />
                  </BarChart>
                </ResponsiveContainer>
  
            </div>
        );
    }
}

export default Visitors;