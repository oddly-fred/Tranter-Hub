/*
 * Header v3.0 GeoIP correction lifted from the Tranter contact-page region logic.
 * Pattern:
 * 1. URL override: ?region=ng, ?region=nigeria, ?region=global, ?region=us, ?region=usa
 * 2. Optional WordPress geo endpoint when configured
 * 3. Public lookup: https://ipapi.co/json/
 * 4. country_code === NG => Nigeria; every other country => Global
 * 5. Fallback: timezone/language check, then Global
 */
(function(){
  window.TRANTER_HEADER_GEOIP_PATTERN = {
    publicEndpoint: 'https://ipapi.co/json/',
    storageKey: 'tmh_region',
    cacheTtl: 86400000,
    mapCountryCode: function(code){
      code = String(code || '').toUpperCase();
      return code === 'NG' || code === 'NIGERIA' ? 'ng' : 'global';
    }
  };
})();
