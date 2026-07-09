/*
 * Header v3.0 GeoIP hotfix aligned to the working Tranter Contact Us page.
 * Live detection now mirrors the Contact Us page flow:
 * 1. URL override: ?region=ng, ?region=nigeria, ?region=global, ?region=us, ?region=usa
 * 2. Fresh public lookup: fetch('https://ipapi.co/json/', { cache:'no-store' })
 * 3. country_code === NG => Nigeria; every other country => Global
 * 4. Lookup failure => Global
 *
 * Important: stale localStorage region caching and timezone/language fallback were removed
 * from the live detection path because the Contact Us page does not use them.
 */
(function(){
  window.TRANTER_HEADER_GEOIP_PATTERN = {
    publicEndpoint: 'https://ipapi.co/json/',
    cache: 'no-store',
    usesLocalStorage: false,
    mapCountryCode: function(code){
      code = String(code || '').toUpperCase();
      return code === 'NG' ? 'ng' : 'global';
    },
    fallbackRegion: 'global'
  };
})();
