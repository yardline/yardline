import React from "react";
import {
  ResponsiveContainer,
  BarChart,
  Bar,
  Cell,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  LineChart,
  Line,
  Legend,
} from "recharts";

class Visitors extends React.Component {
  render() {
    return (
      <div className="visitors yl-white-bg yl-radius yl-score-board-card">
        <h2>Visitors</h2>
        <ResponsiveContainer width="100%" height={350}>
          {/* <BarChart
            data={this.props.statsData}
            margin={{
              top: 20,
              right: 30,
              left: 20,
              bottom: 5,
            }}
          >
            <CartesianGrid strokeDasharray="3 3" />
            <XAxis dataKey="date" />
            <YAxis />
            <Tooltip />
            <Legend />
            <Bar
              name="Visitors"
              dataKey="visitors"
              stackId="a"
              fill="#ec4444"
            />
            <Bar
              name="Page Views"
              dataKey="pageviews"
              stackId="b"
              fill="#0084c1"
            />
          </BarChart> */}
          <LineChart
            width={500}
            height={200}
            data={this.props.statsDat}
            margin={{
              top: 10,
              right: 30,
              left: 0,
              bottom: 0,
            }}
          >
            <CartesianGrid strokeDasharray="3 3" />
            <XAxis dataKey="date" />
            <YAxis />
            <Tooltip />
            <Line
              connectNulls
              type="monotone"
              stroke="#8884d8"
              name="Visitors"
              dataKey="visitors"
              stackId="a"
              fill="#ec4444"
            />
            <Line
              connectNulls
              type="monotone"
              stroke="#8884d8"
              name="Page Views"
              dataKey="pageviews"
              stackId="b"
              fill="#0084c1"
            />
          </LineChart>
        </ResponsiveContainer>
      </div>
    );
  }
}

export default Visitors;
