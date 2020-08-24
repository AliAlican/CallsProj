import React, { useEffect, useState, useRef } from 'react';
import './User.css';
import axios from 'axios';
import { ToastContainer, toast } from 'react-toastify';
import {
    LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, Legend,
  } from 'recharts';
  
const User = ({match}) => {
    const { params: { id } } = match;

    const [user, setUser] = useState({});
    const [last5Calls, setLast5Calls] = useState([]);
    const chartRef = useRef(null);

    useEffect(() => {
        getLast5Calls();
        getUser();
    }, []);

    const getUser = () => {
        axios({
            method: 'get',
            url: `http://localhost:80/api/averages?user_name=${id}` ,
            })
            .then((response) => {
              setUser(response.data);
            })
            .catch(() => {
              toast.error("Error");
            });
    }

    const getLast5Calls = () => {
        axios({
            method: 'get',
            url: `http://localhost:80/api/lastCalls?user_name=${id}` ,
            })
            .then((response) => {
                setLast5Calls(response.data);
            })
            .catch(() => {
              toast.error("Error");
            });
    }

  return (
    <div>
      <div>
        <p className="name">{id}</p>
      </div>
      <p className="score">Average: {user?.total_score}</p>
      <div className="user-container">
          <div className="chart" ref={chartRef}>
            <LineChart
                width={chartRef?.current?.getBoundingClientRect()?.width - 10}
                height={300}
                data={user?.scores}
                margin={{
                    top: 5, right: 20, left: 20, bottom: 5,
                }}
            >
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis dataKey="date" />
                <YAxis />
                <Tooltip />
                <Legend />
                <Line type="monotone" dataKey="external_call_score" stroke="#8884d8" activeDot={{ r: 8 }} />
                <Line type="monotone" dataKey="duration" stroke="#82ca9d" />
            </LineChart>
          </div>
          <div className="table">
              {
                  last5Calls?.map(call => {
                      return (
                          <div key={call.id}>
                              {call.client}
                          </div>
                      )
                  })
              }
          </div>
      </div>
      <ToastContainer />
    </div>
  );
}

export default User;
