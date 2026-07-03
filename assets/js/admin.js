(function(){
  document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.te-content-card').forEach(function(card, index){
      card.style.animationDelay = (index * 35) + 'ms';
    });
  });
})();
