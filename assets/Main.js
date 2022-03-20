import React from 'react';
import ReactDOM from "react-dom";
import "./styles/app.css";

import Game from "./components/Game";

if (document.getElementById('app')) {
    ReactDOM.render(<Game/>, document.getElementById('app'));
}