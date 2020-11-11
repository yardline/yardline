import React from 'react';
import {
  ResponsiveContainer, BarChart, Bar, Cell, XAxis, YAxis, Tooltip, CartesianGrid, Legend, LabelList
} from 'recharts';

const mata = [
    {
      name: 'home', pv: 2400,
    },
    {
      name: 'blog', pv: 1398,
    },
    {
      name: 'Page 3', pv: 9800,
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
    
class PageViews extends React.Component {
    render() {
        return (
            <div className="page-views yl-white-bg yl-radius">
                <h4>Page Views</h4>
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
                  
                    <Bar  name="Visitors" dataKey="pv" stackId="a" fill="rgba(236,68,68,0.5)" >
                        <LabelList dataKey="name" position="insideLeft" />
                    </Bar>  
                  </BarChart>
                </ResponsiveContainer>
            </div>
            
        )
    }
}

export default PageViews;