import React from 'react';
import logo from '../../../images/yardline-logo.png'

function Logo(props) {
    const {type} = props;
    return (
        <div>
            <img src={logo} alt="Yardline Analytics"/>
        </div>
    );
}

export default Logo;