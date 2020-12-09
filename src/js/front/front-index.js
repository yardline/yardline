window.addEventListener('load', function () {

  const yardline = window.yardlineObject;
  
  function getCookie(name) {
    var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
    return v ? v[2] : '';
  }

  function setCookie(name, value, days) {
    var d = new Date;
    d.setTime(d.getTime() + 24*60*60*1000*days);
    document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
  }

  function trackPageview () {
    // do not track if "Do Not Track" is enabled
    if ('doNotTrack' in navigator && navigator.doNotTrack === '1' && yardline.honorDNT) {
      return
    }

    // do not track if this is a prerender request
    if ('visibilityState' in document && document.visibilityState === 'prerender') {
      return
    }

    // do not track if user agent looks like a bot
    if ((/bot|crawler|spider|crawling|seo|chrome-lighthouse/i).test(navigator.userAgent)) {
      return
    }

    // do not track if page is inside an iframe
    if (window.location !== window.parent.location) {
      return
    }
    var Yardline_http = new XMLHttpRequest();
    const cookie = yardline.useCookie ? getCookie('yardline_pages_viewed') : ''
    const pagesViewed = cookie.split(',').filter(function (id) {
      return id !== ''
    })
    console.log(pagesViewed);
    let isNewVisitor = cookie.length === 0
    let pageName = encodeURIComponent(window.location.pathname);
    let isUniquePageview = pagesViewed.indexOf(pageName) === -1
    let referrer = ''

    // add referrer if not from same-site & try to detect returning visitors from referrer URL
    if (typeof (document.referrer) === 'string' && document.referrer !== '') {
      if (document.referrer.indexOf(pageName) === 0) {
        isNewVisitor = false // referred by same-site, so not a new visitor

        if (document.referrer === window.location.href) {
          isUniquePageview = false // referred by same-url, so not a unique pageview
        }
      } else {
        referrer = document.referrer // referred by external site, so send referrer URL to be stored
      }
    }
  

    //cookie
    if (yardlineObject.useCookie) {
      if (pagesViewed.indexOf(pageName) === -1) {
        pagesViewed.push(pageName)
      }
      const expires = new Date()
      expires.setHours(expires.getHours() + 6)
      setCookie('yardline_pages_viewed', pagesViewed.join(','), 1)
    }
   
    // build tracker URL
    let queryStr = ''
   
    
    queryStr += '_=' + Math.floor(Date.now() / 1000)
    queryStr += '&_wpnonce=' + yardlineObject.wpnonce + '&yardline_hit_rest=yes'
    queryStr += '&ua=' + navigator.userAgent 
    queryStr += '&url=' + window.location.href 
    queryStr += '&nv=' + (isNewVisitor ? '1' : '0')
    queryStr += '&up=' + (isUniquePageview ? '1' : '0')
   queryStr += '&r=' + encodeURIComponent(referrer)
   // queryStr += '&referred=' + document.referrer
    console.log(queryStr);
    Yardline_http.open('GET', yardlineObject.restURL + 'yardline/v1/hit' + (yardlineObject.restURL.includes("?") ? '&' : '?') + queryStr, true);
    Yardline_http.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    Yardline_http.send(null);
  }
  
  trackPageview();


 });


//  console.log(yardlineObject.restURL + 'yardline/v1/hit' + (yardlineObject.restURL.includes("?") ? '&' : '?') + '_=' + Math.floor(Date.now() / 1000) + '&_wpnonce=' + yardlineObject.wpnonce + '&yardline_hit_rest=yes&ua=' + navigator.userAgent + '&url=' + window.location.href + '&referred=' + document.referrer, true);
//     var WP_Statistics_http = new XMLHttpRequest();
//     WP_Statistics_http.open('GET', yardlineObject.restURL + 'yardline/v1/hit' + (yardlineObject.restURL.includes("?") ? '&' : '?') + '_=' + Math.floor(Date.now() / 1000) + '&_wpnonce=' + yardlineObject.wpnonce + '&yardline_hit_rest=yes&ua=' + navigator.userAgent + '&url=' + window.location.href + '&referred=' + document.referrer, true);
//     WP_Statistics_http.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
//     WP_Statistics_http.send(null);

  