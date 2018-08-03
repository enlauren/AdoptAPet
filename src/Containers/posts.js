import React, { Component } from "react";
import classes from "./posts.css";

//import redux
import { connect } from "react-redux";

class Posts extends Component {
  render() {
    return <div className={classes.posts}>this are all posts paginated</div>;
  }
}

export default connect(
  null,
  null
)(Posts);
