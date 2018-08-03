import React, { Component } from "react";
import classes from "./lastAddedPosts.css";

class lastAddedPosts extends Component {
    componentWillMount() {
        // make request for last 10 posts or make a request to see if new posts have been added from 5 to 5 seconds
        //let s just gently add the last post and remove last // // https://medium.com/@joethedave/achieving-ui-animations-with-react-the-right-way-562fa8a91935
    }
    render() {
        return (
            <div className={classes.lastAddedPosts}>
                this is the last added posts
            </div>
        );
    }
}

export default lastAddedPosts;
