import React from "react";
import { NavLink } from "react-router-dom";

function MainNav() {
  return (
    <nav>
      <ul>
        <li>
          <NavLink exact to="/" activeClassName="active">Score Board</NavLink>
        </li>
        <li>
          <NavLink to="/settings" activeClassName="active">Settings</NavLink>
        </li>
        <li>
          <NavLink to="/addons" activeClassName="active">Addons</NavLink>
        </li>
      </ul>
    </nav>
  );
}

export default MainNav;
