/* What We Do v1.7.8 GeoIP Smart Solutions rule
   Header shortcode exposes the resolved market on <html> as data-tranter-market.
   Use with: [te_header] + Elementor HTML What We Do page + [te_footer].

   Nigeria market: Smart Solutions is visible.
   Global/US market: Smart Solutions is hidden.
*/
(function(){
  var root = document.getElementById('tranter-whatwedo-sections') || document.getElementById('tranter-whatwedo-hero');
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
    var scope = document.getElementById('tranter-whatwedo-sections') || document;
    var smartCards = scope.querySelectorAll('[data-tranter-market="ng"], [data-te-service-card="smart_solutions"], [data-te-service="smart_solutions"]');
    smartCards.forEach(function(card){
      var target = card.closest('article') || card;
      if (market !== 'ng') {
        target.setAttribute('hidden', 'hidden');
        target.setAttribute('aria-hidden', 'true');
      } else {
        target.removeAttribute('hidden');
        target.removeAttribute('aria-hidden');
      }
    });
  }

  applyMarketRules();
  document.addEventListener('tranter:market-ready', applyMarketRules);
})();
