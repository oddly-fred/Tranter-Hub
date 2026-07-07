/* v1.7.9 Who We Are market rules
   Uses [te_header] market state. NG keeps Smart Solutions wording; Global/US gets neutral enterprise wording. */
(function(){
  var root=document.getElementById('tranter-homepage-v2');
  if(!root) return;

  function currentMarket(){
    var html=document.documentElement, body=document.body;
    var attr=(html&&html.getAttribute('data-tranter-market'))||(body&&body.getAttribute('data-tranter-market'))||'';
    if(attr) return attr.toLowerCase();
    if((html&&html.classList.contains('tranter-market-global'))||(body&&body.classList.contains('tranter-market-global'))) return 'global';
    if((html&&html.classList.contains('tranter-market-ng'))||(body&&body.classList.contains('tranter-market-ng'))) return 'ng';
    var match=document.cookie.match(/(?:^|; )tranter_market=([^;]+)/);
    return match?decodeURIComponent(match[1]).toLowerCase():'ng';
  }

  function setText(selector, ngText, globalText){
    root.querySelectorAll(selector).forEach(function(el){
      el.textContent=currentMarket()==='ng'?ngText:globalText;
    });
  }

  function applyMarketRules(){
    var market=currentMarket();
    setText('[data-tranter-market-copy="smart-engineers"]','Expert ICT & smart solutions engineers','Expert ICT & enterprise solutions engineers');
    root.querySelectorAll('.trh-faq .answer').forEach(function(el){
      if(market!=='ng'){
        el.textContent=el.textContent.replace('smart workflow solutions','workflow automation solutions');
      }
    });
    root.querySelectorAll('[data-tranter-market="ng"], [data-te-service-card="smart_solutions"], [data-te-service="smart_solutions"]').forEach(function(el){
      var target=el.closest('article')||el;
      if(market!=='ng'){
        target.setAttribute('hidden','hidden');
        target.setAttribute('aria-hidden','true');
      }else{
        target.removeAttribute('hidden');
        target.removeAttribute('aria-hidden');
      }
    });
  }

  applyMarketRules();
  document.addEventListener('tranter:market-ready',applyMarketRules);
})();
