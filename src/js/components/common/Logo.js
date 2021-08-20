import React from 'react';

function Logo(props) {
    const {type} = props;
    return (
        <div>
            Logo{type}
        </div>
    );
}

export default Logo;