import React from "react";
import ScoreBoard from "./ScoreBoard";
import Settings from "./Settings";
import Addons from "./Addons";
import { Switch, Route } from "react-router-dom";

function Main(props) {
  return (
    <main>
      <Switch {...props}>
        <Route exact path="/">
            <ScoreBoard {...props} />
        </Route>
        <Route exact path="/settings">
            <Settings />
        </Route>
        <Route exact path="/addons">
            <Addons />
        </Route>
      </Switch>
    </main>
  );
}

export default Main;
