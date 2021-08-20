import React from "react";
import { render } from "react-dom";
import { HashRouter } from "react-router-dom";
import ScoreBoard from "../components/ScoreBoard";
import App from "../components/App";

render(
  <HashRouter>
    <App siteTitle={yardlineAdmin.siteTitle} restURL={yardlineAdmin.restURL} />
  </HashRouter>,
  document.querySelector("#score-board")
);

{
  /* <ScoreBoard
    siteTitle={yardlineAdmin.siteTitle}
    restURL={yardlineAdmin.restURL}
  /> */
}
