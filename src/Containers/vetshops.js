import React, { Component } from "react";
import classes from "./vetshops.css";

//import redux
import { connect } from "react-redux";

class VetShops extends Component {
  render() {
    return <div className={classes.vetshops}>this all vetshops in db</div>;
  }
}

export default connect(
  null,
  null
)(VetShops);
