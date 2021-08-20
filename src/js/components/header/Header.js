import React from "react";
import MainNav from './MainNav';
import Logo from "../common/Logo";

function Header() {
  return (
    <header className="yardline-header">
      <Logo type="white"/>
      <MainNav />
    </header>
  );
}

export default Header;
