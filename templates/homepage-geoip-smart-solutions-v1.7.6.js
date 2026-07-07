/* Homepage v1.7.6 GeoIP Smart Solutions rule
   Header shortcode exposes the resolved market on <html> as data-tranter-market.
   Use with: [te_header] + Elementor HTML homepage + [te_footer]. */
(function(){
  var root = document.getElementById('tranter-homepage-v2');
  if (!root) return;

  function currentMarket(){
    var html = document.documentElement;
    var body = document.body;
    var attr = (html && html.getAttribute('data-tranter-market')) || (body && body.getAttribute('data-tranter-market')) || '';
    if (attr) return attr.toLowerCase();
    if ((html && html.classList.contains('tranter-market-global')) || (body && body.classList.contains('tranter-market-global'))) return 'global';
    if ((html && html.classList.contains('tranter-market-ng')) || (body && body.classList.contains('tranter-market-ng'))) return 'ng';
    var match = document.cookie.match(/(?:^|; )tranter_market=([^;]+)/);
    return match ? decodeURIComponent(match[1]).toLowerCase() : 'ng';
  }

  function applyMarketRules(){
    var market = currentMarket();
    var smartCards = root.querySelectorAll('[data-tranter-market="ng"], [data-te-service-card="smart_solutions"]');
    smartCards.forEach(function(card){
      if (market !== 'ng') {
        card.setAttribute('hidden', 'hidden');
        card.setAttribute('aria-hidden', 'true');
      } else {
        card.removeAttribute('hidden');
        card.removeAttribute('aria-hidden');
      }
    });
  }

  applyMarketRules();
  document.addEventListener('tranter:market-ready', applyMarketRules);
})();
