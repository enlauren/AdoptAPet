import React, { Component } from "react";
import "./navbar.css";

import { NavLink } from "react-router-dom";

//import redux
import { connect } from "react-redux";

class Navbar extends Component {
    state = {
        category: ["caine", "pisica"],
        expanded: null
    };

    render() {
        return <div>This is the navbar!</div>;
    }
}

export default connect(
    null,
    null
)(Navbar);
