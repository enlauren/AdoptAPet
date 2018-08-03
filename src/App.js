import React, { Component } from "react";

// import styling
import "./App.css";

// import routing
import { Route, Switch, withRouter } from "react-router-dom";

//import components and container
import Posts from "./Containers/posts";
import VetShops from "./Containers/vetshops";
import Post from "./Containers/post";

//import hoc Layout
import Layout from "./HOC/layout";

class App extends Component {
  render() {
    return (
      <Layout>
        <Switch>
          <Route exact path="/" component={Posts} />
          <Route exact path="/vetshops" component={VetShops} />
          <Route exact path="/post/:handle" component={Post} />
        </Switch>
      </Layout>
    );
  }
}

export default withRouter(App);
