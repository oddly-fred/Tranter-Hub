/* What We Do v2 market rules review patch. NG keeps Smart Solutions visible. US/Global hide Smart Solutions and switch Smart Solutions wording to neutral enterprise wording. */
(function(){
  var scope=document.getElementById('tranter-whatwedo-sections');
  if(!scope) return;

  function normaliseMarket(value){
    value=String(value||'').toLowerCase().trim();
    if(value==='ng'||value==='nigeria') return 'ng';
    if(value==='us'||value==='usa'||value==='global'||value==='world') return 'global';
    return value || 'ng';
  }

  function currentMarket(){
    var html=document.documentElement;
    var body=document.body;
    var attr=(html&&html.getAttribute('data-tranter-market'))||(body&&body.getAttribute('data-tranter-market'))||'';
    if(attr) return normaliseMarket(attr);
    if((html&&html.classList.contains('tranter-market-global'))||(body&&body.classList.contains('tranter-market-global'))) return 'global';
    if((html&&html.classList.contains('tranter-market-us'))||(body&&body.classList.contains('tranter-market-us'))) return 'global';
    if((html&&html.classList.contains('tranter-market-ng'))||(body&&body.classList.contains('tranter-market-ng'))) return 'ng';
    var match=document.cookie.match(/(?:^|; )tranter_market=([^;]+)/);
    return match?normaliseMarket(decodeURIComponent(match[1])):'ng';
  }

  function setText(selector, ngText, globalText){
    scope.querySelectorAll(selector).forEach(function(el){
      el.textContent=currentMarket()==='ng'?ngText:globalText;
    });
  }

  function applyMarketRules(){
    var market=currentMarket();
    var isNg=market==='ng';
    var grid=scope.querySelector('.twd2-services-grid');
    if(grid){ grid.classList.toggle('is-global-market', !isNg); }
    scope.querySelectorAll('[data-tranter-market="ng"], [data-te-service-card="smart_solutions"], [data-te-service="smart_solutions"]').forEach(function(el){
      var target=el.closest('.twd2-service-card')||el.closest('article')||el;
      if(!isNg){
        target.setAttribute('hidden','hidden');
        target.setAttribute('aria-hidden','true');
      }else{
        target.removeAttribute('hidden');
        target.removeAttribute('aria-hidden');
      }
    });
    setText('[data-tranter-market-copy="smart-engineers"]','Expert ICT & smart solutions engineers','Expert ICT & enterprise solutions engineers');
    scope.querySelectorAll('.twd2-faq .answer').forEach(function(el){
      if(!isNg){ el.textContent=el.textContent.replace(/smart workflow solutions/gi,'workflow automation solutions'); }
    });
  }

  applyMarketRules();
  document.addEventListener('tranter:market-ready',applyMarketRules);
  document.addEventListener('tranter:geoip-ready',applyMarketRules);
  window.addEventListener('storage',applyMarketRules);
  setTimeout(applyMarketRules, 300);
  setTimeout(applyMarketRules, 1200);
})();
