import React from "react";
import { Link } from "react-router-dom";

function MainNav() {
  return (
    <nav>
      <ul>
        <li>
          <Link to="/">Score Board</Link>
        </li>
        <li>
          <Link to="/settings">Settings</Link>
        </li>
        <li>
          <Link to="/addons">Addons</Link>
        </li>
      </ul>
    </nav>
  );
}

export default MainNav;
