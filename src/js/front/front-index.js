console.log('hello');

import Analytics from 'analytics';

/* Initialize analytics */
const analytics = Analytics({
    app: 'yardline',
    version: 100,
    debug: true,
    plugins: [
    ]
  });
analytics.page();
//analytics.track();
analytics.identify('abc');
  analytics.on('pageEnd', ({ payload }) => {
    console.log('event payload', payload)
    // Do your custom logic
    alert('Page view happened')
  });
  