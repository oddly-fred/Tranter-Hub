(function(){
  function reveal(root){
    root = root || document;
    var els = root.querySelectorAll('.trh-reveal');
    if(!('IntersectionObserver' in window)){ els.forEach(function(e){e.classList.add('trh-in')}); return; }
    var obs = new IntersectionObserver(function(entries){
      entries.forEach(function(entry){ if(entry.isIntersecting){ entry.target.classList.add('trh-in'); obs.unobserve(entry.target); } });
    }, {threshold:.1, rootMargin:'0px 0px -25px 0px'});
    els.forEach(function(e){ obs.observe(e); });
  }
  document.addEventListener('DOMContentLoaded', function(){ reveal(document); });
  document.addEventListener('click', function(e){
    if(e.target.closest('[data-te-open-demo]')){
      var modal = document.querySelector('[data-te-demo-modal]');
      if(modal){ e.preventDefault(); modal.classList.add('is-open'); }
    }
    if(e.target.closest('[data-te-close-demo]')){
      var modal2 = document.querySelector('[data-te-demo-modal]');
      if(modal2) modal2.classList.remove('is-open');
    }
  });
  document.addEventListener('keydown', function(e){
    if(e.key === 'Escape') document.querySelectorAll('[data-te-demo-modal]').forEach(function(m){m.classList.remove('is-open')});
  });
})();
