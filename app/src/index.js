import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter } from "react-router-dom";
import {Route, Switch} from "react-router";

import 'bootstrap/dist/css/bootstrap.css';
import "./index.css";

import {Header} from "./shared/Header";
import {Footer} from "./shared/Footer";
import {Home} from "./pages/Home";
import {Signup} from "./pages/Signup";
import {FourOhFour} from "./pages/FourOhFour";
import {RouteMap} from "./pages/RouteMap";


import { library } from '@fortawesome/fontawesome-svg-core';
import {
	faEnvelope,
	faPencilAlt,
	faSignInAlt,
	faSortDown,
	faUserCircle,
	faKey,
	faDog
} from "@fortawesome/free-solid-svg-icons";





library.add(faPencilAlt, faUserCircle, faSortDown, faEnvelope, faKey, faSignInAlt, faDog );


const App = () => (
	<>
	 	<BrowserRouter>
<<<<<<< HEAD
			<Header/>
			<Switch>
				<Route exact path="/" component={Home} />
				<Route exact path="/signup" component={Signup} />
				<Route exact path="/route" component={RouteMap} />
				<Route component={FourOhFour} />

			</Switch>
=======
			<div className="sfooter-content">
				<Header/>
				<Switch>
					<Route component={FourOhFour} />
					<Route exact path="/" component={Home} />
					<Route exact path="/signup" component={Signup} />
					<Route exact path="/route" component={SingleRoutePage} />
				</Switch>
			</div>
>>>>>>> static-ui
			<Footer/>
		 </BrowserRouter>
	</>
);
ReactDOM.render(<App/>, document.querySelector('#root'));