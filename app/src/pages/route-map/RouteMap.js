import React, {useEffect, useState} from "react";
import {Link} from "react-router-dom";
import ReactMapboxGl, { Layer, Feature } from 'react-mapbox-gl';

import Container from "react-bootstrap/Container";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";

import {CommentCard} from "../comments/CommentCard";
import {CommentForm} from "../comments/CommentForm";
import {useDispatch, useSelector} from "react-redux";
import {getCommentsAndUsersByRouteId} from "../../shared/actions/get-comment";
import {getRouteByRouteId, getRouteByRouteType} from "../../shared/actions/get-route";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import Badge from "react-bootstrap/Badge";
import Button from "react-bootstrap/Button";
import {UseJwt} from "../../shared/utils/JwtHelpers";
import {ZoomControl} from "react-mapbox-gl";

export const RouteMap = ({match}) => {

	const Map = ReactMapboxGl({
		accessToken: 'pk.eyJ1Ijoid2hhcnJpcyIsImEiOiJjanp3cmVkdHMwMnkzM2JwbThiYXd3YWJtIn0.LYO1SzQdH7Q8p1as8N3dMA',
	});

	const routeId =  match.params.routeId;
	const routeType = match.params.routeType;

	const comments = useSelector(state => (state.comments ? state.comments : []));

	const routes = useSelector(state => (state.route ? state.route :[]));

	const dispatch = useDispatch();

	const effects = () => {
		dispatch(getCommentsAndUsersByRouteId(routeId));
		dispatch(getRouteByRouteId(routeId));
		dispatch(getRouteByRouteType(routeType))
	};

	const inputs = [routeId];

	useEffect(effects, inputs);

	let tempCoordinates = match.params.routeFile;

	const [coordinates, setCoordinates] = useState(tempCoordinates);

	return (
		<>
			<main>
				<section>
					<Container>
						<Row>
							<Col>
								<h2>Bosque Trail</h2>
								<Map
									style="mapbox://styles/mapbox/streets-v9"
									containerStyle={{
										height: '50vh',
										width: '50vw'
									}}
									center={[-106.6505556, 35.0844444]}
								>
									<ZoomControl/>
									<Layer
										type="line"
										layout={{
										'line-cap': 'round',
										'line-join': 'round'
									}}
										paint={{
										'line-color': '#4790E5',
										'line-width': 4
									}}
									>
										{/*{coordinates.map(point => <Feature coordinates={point} />)}*/}
									</Layer>
								</Map>
							</Col>
						</Row>
					</Container>
				</section>

				<section className="py-5">
					<Container fluid="true">
						<Row>
							<Button variant="outline-danger" size="sm">
								<FontAwesomeIcon icon="heart"/>&nbsp;
								<Badge variant="danger">94</Badge>
							</Button>
							<Col md="4">
								<div id="comment-wrapper">
									<h3>Post a Comment</h3>
									<CommentForm routeId={routeId}/>
								</div>
							</Col>

							<Col md={{span:7, offset: 1}}>
								{comments.map(comment =>
									<CommentCard comment={comment} key={comment.commentId} />
								)}
							</Col>
						</Row>
					</Container>
				</section>
			</main>
		</>
	)
};
