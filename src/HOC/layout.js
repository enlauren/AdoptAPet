import React, { Component } from "react";
import classes from "./layout.css";

//import containers and components
import Navbar from "../Containers/navbar";
import Sidebar from "../Components/sidebar";
import LastAddedPosts from "../Containers/lastAddedPosts";
import Footer from "../Components/footer";
import Notifications from "../Components/notifications";

import { withRouter } from "react-router-dom";
//import redux
import { connect } from "react-redux";

//notice
//modify aus-content in aus_content in css

class Layout extends Component {
  render() {
    let lastAdd = null;
    let ps = this.props.location.pathname.split("/");
    if (ps[1] === "post") {
      lastAdd = <LastAddedPosts />;
    }
    return (
      <div id={classes.layout}>
        <Navbar />
        <div className={[classes.container, classes.aus_content].join(" ")}>
          <div className={classes.row}>
            <Notifications />
            <main className={classes.mainContent}>{this.props.children}</main>
            <Sidebar />
          </div>
          {lastAdd}
          <Footer />
        </div>
      </div>
    );
  }
}

export default withRouter(
  connect(
    null,
    null
  )(Layout)
);
