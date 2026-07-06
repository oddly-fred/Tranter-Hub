(function(){
  document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.te-content-card,.te-template-card,.te-module,.te-mini-stat').forEach(function(card, index){
      card.style.animationDelay = (index * 35) + 'ms';
    });

    document.addEventListener('click', function(e){
      var copyBtn = e.target.closest('[data-te-copy-template]');
      if(copyBtn){
        var target = document.querySelector(copyBtn.getAttribute('data-target'));
        if(!target) return;
        target.classList.add('is-visible');
        target.focus();
        target.select();
        var value = target.value || target.textContent || '';
        var done = function(){
          var old = copyBtn.textContent;
          copyBtn.textContent = 'Copied';
          copyBtn.classList.add('is-copied');
          setTimeout(function(){ copyBtn.textContent = old; copyBtn.classList.remove('is-copied'); }, 1600);
        };
        if(navigator.clipboard && navigator.clipboard.writeText){
          navigator.clipboard.writeText(value).then(done).catch(function(){ document.execCommand('copy'); done(); });
        }else{
          document.execCommand('copy');
          done();
        }
      }

      var toggleBtn = e.target.closest('[data-te-toggle-template]');
      if(toggleBtn){
        var code = document.querySelector(toggleBtn.getAttribute('data-target'));
        if(!code) return;
        code.classList.toggle('is-visible');
        toggleBtn.textContent = code.classList.contains('is-visible') ? 'Hide HTML' : 'View HTML';
      }
    });
  });
})();
