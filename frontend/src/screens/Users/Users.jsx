import React, { useEffect, useState } from 'react';
import './Users.css';
import axios from 'axios';
import { ToastContainer, toast } from 'react-toastify';
import { useHistory } from 'react-router-dom';

const Users = () => {
  const history = useHistory();
  const [users, setUsers] = useState([]);

  useEffect(() => {
    getUsers();
  }, []);

  const getUsers = () => {
    axios({
        method: 'get',
        url: 'http://localhost:80/api/users',
        })
        .then((response) => {
          setUsers(response.data);
        })
        .catch(() => {
          toast.error("Error");
        });
  };

  const onUserClick = (user) => {
    history.push('/users/' + user.user);
  }

  return (
    <div>
      <div className="users">
        {
          users?.map(user => {
            return (
              <div className="user" key={user.user}>
                {user.user}
                <button onClick={() => onUserClick(user)}>View</button>
              </div>
            )
          })
        }
      </div>
        <ToastContainer />
    </div>
  );
}

export default Users;
