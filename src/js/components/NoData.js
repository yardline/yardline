import React from 'react';
import { __ } from '@wordpress/i18n';

function NoData() {
    return(
        <div className="no-data">
            <h3>{__('No Data', 'yardline')}</h3>
        </div>
    )
}

export default NoData;