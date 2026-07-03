(function(){
  document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.te-content-card').forEach(function(card, index){
      card.style.animationDelay = (index * 35) + 'ms';
    });

    var isWebsiteWorkspace = window.location.href.indexOf('page=tranter-engine-website') !== -1;
    if (!isWebsiteWorkspace || document.querySelector('[data-te-service-shortcodes]')) return;

    var main = document.querySelector('.te-main');
    if (!main) return;

    var panel = document.createElement('section');
    panel.className = 'te-panel';
    panel.setAttribute('data-te-service-shortcodes', 'true');
    panel.innerHTML = '' +
      '<div class="te-panel-head"><h2>Individual Service Page Shortcodes</h2><span>Service page reference</span></div>' +
      '<div class="te-guide-grid">' +
        '<article class="te-guide-card"><h3>IT Support Services</h3><pre class="te-code-block">[te_site_header]\n\n[te_it_support_page]\n\n[te_site_footer]</pre><p class="te-muted">Use this on the /wp/it-support-services/ WordPress page.</p></article>' +
        '<article class="te-guide-card"><h3>Smart Solutions</h3><pre class="te-code-block">[te_site_header]\n\n[te_smart_solutions_page]\n\n[te_site_footer]</pre><p class="te-muted">Use this on the /wp/smart-solutions/ WordPress page. Nigeria-only: hidden for non-Nigerian visitors.</p></article>' +
        '<article class="te-guide-card"><h3>HR Support Services</h3><pre class="te-code-block">[te_site_header]\n\n[te_hr_support_page]\n\n[te_site_footer]</pre><p class="te-muted">Use this on the /wp/hr-support-services/ WordPress page.</p></article>' +
      '</div>' +
      '<div class="te-shortcode-table">' +
        '<div class="te-shortcode-row"><code>[te_it_support_page]</code><span>Full IT Support Services page</span><b class="te-status-available">Available</b></div>' +
        '<div class="te-shortcode-row"><code>[te_it_support_services_page]</code><span>Alias for IT Support Services page</span><b class="te-status-available">Available</b></div>' +
        '<div class="te-shortcode-row"><code>[te_smart_solutions_page]</code><span>Full Smart Solutions page, Nigeria-only</span><b class="te-status-available">Available</b></div>' +
        '<div class="te-shortcode-row"><code>[te_smart_solution_page]</code><span>Alias for Smart Solutions page, Nigeria-only</span><b class="te-status-available">Available</b></div>' +
        '<div class="te-shortcode-row"><code>[te_hr_support_page]</code><span>Full HR Support Services page</span><b class="te-status-available">Available</b></div>' +
        '<div class="te-shortcode-row"><code>[te_hr_support_services_page]</code><span>Alias for HR Support Services page</span><b class="te-status-available">Available</b></div>' +
      '</div>';

    main.appendChild(panel);
  });
})();
