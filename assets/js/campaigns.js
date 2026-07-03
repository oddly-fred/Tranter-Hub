(function(){
  function send(payload){
    if(!window.TranterCampaigns || !payload.campaignId) return;
    var data = new FormData();
    data.append('action','te_campaign_track');
    data.append('nonce',TranterCampaigns.nonce);
    Object.keys(payload).forEach(function(k){ data.append(k,payload[k] || ''); });
    if(navigator.sendBeacon){
      data.append('beacon','1');
      navigator.sendBeacon(TranterCampaigns.ajaxUrl,data);
      return;
    }
    fetch(TranterCampaigns.ajaxUrl,{method:'POST',body:data,credentials:'same-origin'}).catch(function(){});
  }
  function utm(name){ return new URLSearchParams(window.location.search).get(name) || ''; }
  function labelFor(el){
    if(!el) return 'interaction';
    return (el.getAttribute('data-te-label') || el.getAttribute('aria-label') || el.innerText || el.value || el.name || el.id || el.tagName || 'interaction').trim().replace(/\s+/g,' ').slice(0,140);
  }
  function clickType(target){
    var explicit = target.getAttribute('data-te-track');
    if(explicit) return explicit === 'conversion' ? 'conversion' : 'click';
    var href = target.href || '';
    if(/^https?:\/\/wa\.me|api\.whatsapp\.com/i.test(href)) return 'whatsapp';
    if(/^tel:/i.test(href)) return 'phone';
    if(/^mailto:/i.test(href)) return 'email';
    if(href && target.hostname && target.hostname !== window.location.hostname) return 'outbound';
    return 'click';
  }
  document.addEventListener('DOMContentLoaded',function(){
    document.querySelectorAll('.te-campaign[data-campaign-id]').forEach(function(campaign){
      if(campaign.getAttribute('data-tracking') === '0') return;
      var id = campaign.getAttribute('data-campaign-id');
      send({campaignId:id,event:'view',utmSource:utm('utm_source'),utmMedium:utm('utm_medium'),utmCampaign:utm('utm_campaign'),utmContent:utm('utm_content'),utmTerm:utm('utm_term')});
      campaign.addEventListener('click',function(e){
        var target = e.target.closest('a,button,[role="button"],[data-te-track],input[type="submit"],input[type="button"]');
        if(!target) return;
        send({campaignId:id,event:clickType(target),label:labelFor(target),url:target.href || '',utmSource:utm('utm_source'),utmMedium:utm('utm_medium'),utmCampaign:utm('utm_campaign'),utmContent:utm('utm_content'),utmTerm:utm('utm_term')});
      },true);
      campaign.addEventListener('submit',function(e){
        var form = e.target;
        if(!form || !form.matches('form')) return;
        send({campaignId:id,event:'conversion',label:labelFor(form) || 'form submit',url:form.action || window.location.href,utmSource:utm('utm_source'),utmMedium:utm('utm_medium'),utmCampaign:utm('utm_campaign'),utmContent:utm('utm_content'),utmTerm:utm('utm_term')});
      },true);
    });
  });
})();
