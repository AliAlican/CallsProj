import React from 'react';
import { Redirect, Switch, Route } from 'react-router-dom';
import Dashboard from '../screens/Dashboard/Dashboard';
import Users from '../screens/Users/Users';
import User from '../screens/User/User';

const Routes = () => (
	<Switch>
		<Route path="/" exact component={Dashboard} />
        <Route path="/users" exact component={Users} />
		<Route path="/users/:id" exact component={User} />
		<Route path="*">
			<Redirect to="/" />
		</Route>
	</Switch>
);

export default Routes;
