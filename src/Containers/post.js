import React, { Component } from "react";
import classes from "./post.css";

//import redux
import { connect } from "react-redux";

class Post extends Component {
  componentWillMount() {
    // get this.props.params.handle and pass to axios req
  }
  render() {
    return <div className={classes.post}>this is main post</div>;
  }
}

export default connect(
  null,
  null
)(Post);
